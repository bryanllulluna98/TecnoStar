<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Subscription::with(['client', 'profile.providerAccount.service', 'payments']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->orderBy('expires_at', 'desc')->paginate(15)->withQueryString();

        return view('subscriptions.index', compact('subscriptions', 'status', 'search'));
    }

    public function create(Request $request): View
    {
        $preselectedClientId = $request->query('client_id');
        $preselectedProfileId = $request->query('profile_id');

        $clients = Client::orderBy('name')->get();

        // Obtener solo perfiles libres (o el preseleccionado)
        $freeProfiles = Profile::with('providerAccount.service')
            ->where(function ($q) use ($preselectedProfileId) {
                $q->where('status', 'free');
                if ($preselectedProfileId) {
                    $q->orWhere('id', $preselectedProfileId);
                }
            })
            ->get();

        return view('subscriptions.create', compact('clients', 'freeProfiles', 'preselectedClientId', 'preselectedProfileId'));
    }

    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $recordPayment = $request->boolean('record_payment', true);
        $paymentMethod = $data['payment_method'] ?? 'other';

        unset($data['record_payment'], $data['payment_method']);

        $data['status'] = 'active';

        $subscription = Subscription::create($data);

        // Cambiar el perfil a 'occupied'
        $profile = Profile::findOrFail($data['profile_id']);
        $profile->update(['status' => 'occupied']);

        // Registrar pago inicial si está marcado
        if ($recordPayment && (float) $data['price'] > 0) {
            Payment::create([
                'subscription_id' => $subscription->id,
                'amount' => $data['price'],
                'payment_date' => Carbon::parse($data['starts_at']),
                'payment_method' => $paymentMethod,
                'notes' => 'Pago inicial al activar la suscripción.',
            ]);
        }

        return redirect()->route('expirations.index')
            ->with('success', "¡Suscripción de {$subscription->client->name} creada con éxito!");
    }

    public function cancel(Subscription $subscription): RedirectResponse
    {
        $subscription->update(['status' => 'cancelled']);

        // Liberar el perfil
        $subscription->profile->update(['status' => 'free']);

        return back()->with('info', "Suscripción cancelada y perfil '{$subscription->profile->name}' liberado para nueva venta.");
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $profile = $subscription->profile;
        $subscription->delete();

        // Si no quedan suscripciones activas en este perfil, liberarlo
        if (!$profile->subscriptions()->whereIn('status', ['active', 'expiring_soon'])->exists()) {
            $profile->update(['status' => 'free']);
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Registro de suscripción eliminado.');
    }
}
