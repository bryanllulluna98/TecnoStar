<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ProviderAccount;
use App\Models\Subscription;
use App\Services\WhatsAppMessageService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpirationController extends Controller
{
    public function index(Request $request, WhatsAppMessageService $waService): View
    {
        $today = Carbon::today();
        $tab = $request->query('tab', 'today');

        $query = Subscription::with(['client', 'profile.providerAccount.service'])
            ->whereIn('status', ['active', 'expiring_soon', 'expired']);

        if ($tab === 'today') {
            $query->whereDate('expires_at', $today->toDateString());
        } elseif ($tab === 'upcoming') {
            $query->whereBetween('expires_at', [
                $today->copy()->addDay()->toDateString(),
                $today->copy()->addDays(3)->toDateString(),
            ]);
        } elseif ($tab === 'expired') {
            $query->where('expires_at', '<', $today->toDateString());
        }

        $subscriptions = $query->orderBy('expires_at', 'asc')->paginate(20)->withQueryString();

        // Enlazar enlaces de WhatsApp
        foreach ($subscriptions as $sub) {
            $sub->wa_charge_url = $waService->generateUrl('billing_reminder', $sub);
            $sub->wa_update_url = $waService->generateUrl('password_update', $sub);
            $sub->wa_profile_url = $waService->generateUrl('credential_delivery', $sub);
        }

        // Cuentas proveedor para la pestaña 'provider'
        $providerAccounts = [];
        if ($tab === 'provider') {
            $providerAccounts = ProviderAccount::with('service')
                ->where('status', 'active')
                ->orderBy('billing_due_date', 'asc')
                ->paginate(20)
                ->withQueryString();
        }

        // Conteo rápido para insignias de pestañas
        $todayCount = Subscription::whereIn('status', ['active', 'expiring_soon'])
            ->whereDate('expires_at', $today->toDateString())
            ->count();

        $upcomingCount = Subscription::whereIn('status', ['active', 'expiring_soon'])
            ->whereBetween('expires_at', [
                $today->copy()->addDay()->toDateString(),
                $today->copy()->addDays(3)->toDateString(),
            ])
            ->count();

        $expiredCount = Subscription::whereIn('status', ['active', 'expiring_soon', 'expired'])
            ->where('expires_at', '<', $today->toDateString())
            ->count();

        $providerCount = ProviderAccount::where('status', 'active')
            ->where('billing_due_date', '<=', $today->copy()->addDays(7)->toDateString())
            ->count();

        return view('expirations.index', compact(
            'subscriptions',
            'providerAccounts',
            'tab',
            'todayCount',
            'upcomingCount',
            'expiredCount',
            'providerCount'
        ));
    }

    /**
     * Renovación rápida de suscripción con registro de pago.
     */
    public function quickRenew(Request $request, Subscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'months' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $months = (int) ($validated['months'] ?? 1);
        $currentExpiry = $subscription->expires_at->isPast() ? Carbon::today() : $subscription->expires_at;
        $newExpiry = $currentExpiry->copy()->addMonths($months);

        $subscription->update([
            'expires_at' => $newExpiry,
            'status' => 'active',
        ]);

        // Registrar pago efectivo
        Payment::create([
            'subscription_id' => $subscription->id,
            'amount' => $validated['amount'],
            'payment_date' => Carbon::today(),
            'payment_method' => $validated['payment_method'],
            'notes' => 'Renovación rápida (' . $months . ' mes/es) desde el tablero de vencimientos.',
        ]);

        return back()->with('success', "¡Suscripción de {$subscription->client->name} renovada con éxito hasta el {$newExpiry->format('d/m/Y')}!");
    }
}
