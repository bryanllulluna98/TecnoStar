<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $onlyReview = $request->boolean('review');

        $query = Client::withCount([
            'subscriptions as active_subscriptions_count' => function ($q) {
                $q->whereIn('status', ['active', 'expiring_soon']);
            },
            'subscriptions as total_subscriptions_count',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($onlyReview) {
            $query->where('needs_contact_review', true);
        }

        $clients = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();

        $reviewCount = Client::where('needs_contact_review', true)->count();

        return view('clients.index', compact('clients', 'search', 'onlyReview', 'reviewCount'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Normalización básica de celular
        if (!empty($data['phone'])) {
            $digits = preg_replace('/\D+/', '', $data['phone']);
            if (str_starts_with($digits, '09') && strlen($digits) === 10) {
                $data['phone'] = '+593 ' . substr($digits, 1, 2) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6);
            }
        } else {
            $data['needs_contact_review'] = true;
        }

        $client = Client::create($data);

        return redirect()->route('clients.show', $client)
            ->with('success', "Cliente {$client->name} registrado exitosamente.");
    }

    public function show(Client $client): View
    {
        $client->load([
            'subscriptions.profile.providerAccount.service',
            'subscriptions.payments' => function ($q) {
                $q->orderBy('payment_date', 'desc');
            },
        ]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['phone'])) {
            $digits = preg_replace('/\D+/', '', $data['phone']);
            if (str_starts_with($digits, '09') && strlen($digits) === 10) {
                $data['phone'] = '+593 ' . substr($digits, 1, 2) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6);
            }
        }

        $client->update($data);

        return redirect()->route('clients.show', $client)
            ->with('success', "Datos de {$client->name} actualizados.");
    }

    public function destroy(Client $client): RedirectResponse
    {
        if ($client->subscriptions()->exists()) {
            return back()->with('error', 'No se puede eliminar un cliente con suscripciones registradas.');
        }

        $name = $client->name;
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', "Cliente {$name} eliminado.");
    }
}
