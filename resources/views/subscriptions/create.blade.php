@extends('layouts.app')

@section('title', 'Nueva Venta / Suscripción')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white">Nueva Venta: Asignar Perfil a Cliente</h1>
            <p class="text-xs text-slate-400 mt-0.5">Asigna un perfil disponible a un cliente y define el precio pactado.</p>
        </div>
        <a href="{{ route('subscriptions.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Volver
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <form action="{{ route('subscriptions.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Client Select -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="client_id" class="block text-xs font-medium text-slate-300 uppercase tracking-wider">
                        Cliente <span class="text-rose-400">*</span>
                    </label>
                    <a href="{{ route('clients.create') }}" target="_blank" class="text-[11px] text-amber-400 hover:text-amber-300">
                        + Registrar nuevo cliente
                    </a>
                </div>
                <select 
                    id="client_id" 
                    name="client_id" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                >
                    <option value="">Selecciona el cliente...</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ (old('client_id', $preselectedClientId) == $c->id) ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->phone ?: 'Sin cel' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Profile Select -->
            <div>
                <label for="profile_id" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                    Perfil Disponible para Venta <span class="text-rose-400">*</span>
                </label>
                <select 
                    id="profile_id" 
                    name="profile_id" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                >
                    <option value="">Selecciona perfil libre...</option>
                    @foreach($freeProfiles as $p)
                        <option value="{{ $p->id }}" {{ (old('profile_id', $preselectedProfileId) == $p->id) ? 'selected' : '' }}>
                            [{{ $p->providerAccount->service->name }}] {{ $p->name }} {{ $p->pin ? '(PIN: ' . $p->pin . ')' : '' }} &mdash; {{ $p->providerAccount->email }}
                        </option>
                    @endforeach
                </select>
                @if($freeProfiles->isEmpty())
                    <p class="text-[11px] text-rose-400 mt-1">⚠️ No hay perfiles libres en stock. Debes crear una cuenta madre primero.</p>
                @endif
            </div>

            <!-- Price & Period -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Precio de Venta ($) <span class="text-rose-400">*</span>
                    </label>
                    <input 
                        type="number" 
                        step="0.01" 
                        id="price" 
                        name="price" 
                        value="{{ old('price', '5.00') }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>

                <div>
                    <label for="renewal_period" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Ciclo de Cobro <span class="text-rose-400">*</span>
                    </label>
                    <select 
                        id="renewal_period" 
                        name="renewal_period" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                        <option value="monthly" {{ old('renewal_period') === 'monthly' ? 'selected' : '' }}>Mensual</option>
                        <option value="quarterly" {{ old('renewal_period') === 'quarterly' ? 'selected' : '' }}>Trimestral (3 meses)</option>
                        <option value="biannual" {{ old('renewal_period') === 'biannual' ? 'selected' : '' }}>Semestral (6 meses)</option>
                        <option value="annual" {{ old('renewal_period') === 'annual' ? 'selected' : '' }}>Anual (12 meses)</option>
                    </select>
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="starts_at" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Fecha de Inicio <span class="text-rose-400">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="starts_at" 
                        name="starts_at" 
                        value="{{ old('starts_at', now()->format('Y-m-d')) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>

                <div>
                    <label for="expires_at" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Fecha de Vencimiento <span class="text-rose-400">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="expires_at" 
                        name="expires_at" 
                        value="{{ old('expires_at', now()->addMonth()->format('Y-m-d')) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>
            </div>

            <!-- Initial Payment section -->
            <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-3">
                <div class="flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        id="record_payment" 
                        name="record_payment" 
                        value="1" 
                        {{ old('record_payment', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-amber-500 focus:ring-amber-500/40"
                    >
                    <label for="record_payment" class="text-xs font-semibold text-white cursor-pointer">
                        Registrar cobro inicial inmediatamente en ingresos
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label for="payment_method" class="block text-[11px] text-slate-400 mb-1">Método de Cobro:</label>
                        <select name="payment_method" id="payment_method" class="w-full px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-200">
                            <option value="transfer_pichincha">Transferencia Banco Pichincha</option>
                            <option value="transfer_guayaquil">Transferencia Banco Guayaquil</option>
                            <option value="deuna">Deuna / PayPhone</option>
                            <option value="cash">Efectivo</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                    Notas u Observaciones
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="2" 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    placeholder="Dispositivo permitido, acuerdo especial, etc."
                >{{ old('notes') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                <a href="{{ route('subscriptions.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
                    Confirmar Venta y Asignar Perfil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
