<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::withCount(['providerAccounts', 'profiles'])
            ->orderBy('name')
            ->get();

        return view('services.index', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:services,name'],
            'type' => ['required', 'in:profile,full_account'],
            'suggested_price' => ['required', 'numeric', 'min:0'],
            'default_profiles' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $validated['is_active'] = true;

        Service::create($validated);

        return back()->with('success', "Servicio '{$validated['name']}' agregado con éxito.");
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'suggested_price' => ['required', 'numeric', 'min:0'],
            'default_profiles' => ['required', 'integer', 'min:1', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);

        return back()->with('success', "Servicio '{$service->name}' actualizado.");
    }
}
