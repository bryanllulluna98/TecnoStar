<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProviderAccountRequest;
use App\Http\Requests\UpdateProviderAccountRequest;
use App\Models\Profile;
use App\Models\ProviderAccount;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderAccountController extends Controller
{
    public function index(Request $request): View
    {
        $serviceId = $request->query('service_id');
        $search = $request->query('search');

        $query = ProviderAccount::with(['service', 'profiles.activeSubscription.client'])
            ->withCount([
                'profiles as total_profiles',
                'profiles as free_profiles' => function ($q) {
                    $q->where('status', 'free');
                },
                'profiles as occupied_profiles' => function ($q) {
                    $q->where('status', 'occupied');
                },
            ]);

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('account_identifier', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderBy('billing_due_date', 'asc')->paginate(12)->withQueryString();
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('provider-accounts.index', compact('accounts', 'services', 'serviceId', 'search'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('provider-accounts.create', compact('services'));
    }

    public function store(StoreProviderAccountRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $account = ProviderAccount::create($data);

        // Generar perfiles automáticamente según la capacidad
        $service = $account->service;
        $max = (int) $account->max_profiles;

        if ($service && $service->type === 'profile') {
            for ($i = 1; $i <= $max; $i++) {
                Profile::create([
                    'provider_account_id' => $account->id,
                    'name' => "Perfil {$i}",
                    'pin' => null,
                    'status' => 'free',
                ]);
            }
        } else {
            Profile::create([
                'provider_account_id' => $account->id,
                'name' => 'Cuenta Completa',
                'pin' => null,
                'status' => 'free',
            ]);
        }

        return redirect()->route('provider-accounts.show', $account)
            ->with('success', "Cuenta {$account->email} creada con {$max} perfil(es) disponibles.");
    }

    public function show(ProviderAccount $providerAccount): View
    {
        $providerAccount->load([
            'service',
            'profiles.activeSubscription.client',
        ]);

        return view('provider-accounts.show', compact('providerAccount'));
    }

    public function edit(ProviderAccount $providerAccount): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('provider-accounts.edit', compact('providerAccount', 'services'));
    }

    public function update(UpdateProviderAccountRequest $request, ProviderAccount $providerAccount): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $providerAccount->update($data);

        return redirect()->route('provider-accounts.show', $providerAccount)
            ->with('success', "Cuenta {$providerAccount->email} actualizada.");
    }

    public function destroy(ProviderAccount $providerAccount): RedirectResponse
    {
        // Verificar si algún perfil tiene suscripciones activas
        $hasActive = $providerAccount->profiles()
            ->whereHas('subscriptions', function ($q) {
                $q->whereIn('status', ['active', 'expiring_soon']);
            })
            ->exists();

        if ($hasActive) {
            return back()->with('error', 'No se puede eliminar la cuenta madre porque tiene perfiles con clientes activos.');
        }

        $email = $providerAccount->email;
        $providerAccount->delete();

        return redirect()->route('provider-accounts.index')
            ->with('success', "Cuenta {$email} eliminada.");
    }
}
