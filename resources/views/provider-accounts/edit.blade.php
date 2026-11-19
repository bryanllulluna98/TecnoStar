@extends('layouts.app')

@section('title', 'Editar Cuenta Madre')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white">Editar Cuenta Madre: {{ $providerAccount->email }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">Modifica los parámetros o credenciales de la cuenta mayorista.</p>
        </div>
        <a href="{{ route('provider-accounts.show', $providerAccount) }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Volver
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <form action="{{ route('provider-accounts.update', $providerAccount) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="service_id" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Plataforma / Servicio <span class="text-rose-400">*</span>
                    </label>
                    <select 
                        id="service_id" 
                        name="service_id" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                        @foreach($services as $s)
                            <option value="{{ $s->id }}" {{ old('service_id', $providerAccount->service_id) == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="account_identifier" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Identificador
                    </label>
                    <input 
                        type="text" 
                        id="account_identifier" 
                        name="account_identifier" 
                        value="{{ old('account_identifier', $providerAccount->account_identifier) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Correo o Usuario <span class="text-rose-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $providerAccount->email) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Nueva Contraseña (Opcional)
                    </label>
                    <input 
                        type="text" 
                        id="password" 
                        name="password" 
                        placeholder="Dejar en blanco para conservar actual"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="cost" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Costo Proveedor ($) <span class="text-rose-400">*</span>
                    </label>
                    <input 
                        type="number" 
                        step="0.01" 
                        id="cost" 
                        name="cost" 
                        value="{{ old('cost', $providerAccount->cost) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>

                <div>
                    <label for="max_profiles" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Perfiles Máximos
                    </label>
                    <input 
                        type="number" 
                        id="max_profiles" 
                        name="max_profiles" 
                        value="{{ old('max_profiles', $providerAccount->max_profiles) }}" 
                        required 
                        min="1" 
                        max="20"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>

                <div>
                    <label for="status" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Estado <span class="text-rose-400">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                        <option value="active" {{ old('status', $providerAccount->status) === 'active' ? 'selected' : '' }}>Activa</option>
                        <option value="suspended" {{ old('status', $providerAccount->status) === 'suspended' ? 'selected' : '' }}>Suspendida</option>
                        <option value="cancelled" {{ old('status', $providerAccount->status) === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="billing_start_date" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Fecha Inicio
                    </label>
                    <input 
                        type="date" 
                        id="billing_start_date" 
                        name="billing_start_date" 
                        value="{{ old('billing_start_date', $providerAccount->billing_start_date?->format('Y-m-d')) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>

                <div>
                    <label for="billing_due_date" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Fecha Corte Proveedor <span class="text-rose-400">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="billing_due_date" 
                        name="billing_due_date" 
                        value="{{ old('billing_due_date', $providerAccount->billing_due_date->format('Y-m-d')) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                    Notas
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="2" 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                >{{ old('notes', $providerAccount->notes) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                <a href="{{ route('provider-accounts.show', $providerAccount) }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
                    Actualizar Cuenta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
