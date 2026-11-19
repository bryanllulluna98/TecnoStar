@extends('layouts.app')

@section('title', 'Cuentas Madre y Proveedores')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span>🔑 Cuentas Madre (Proveedores)</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-400 font-normal">
                    {{ $accounts->total() }} registradas
                </span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Gestión de cuentas adquiridas con proveedores mayoristas y control de stock.</p>
        </div>

        <div>
            <a href="{{ route('provider-accounts.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                <span>+ Nueva Cuenta Madre</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('provider-accounts.index') }}" class="flex-1 w-full flex flex-wrap items-center gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Buscar por correo o etiqueta..." 
                class="flex-1 min-w-[200px] px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
            >

            <select name="service_id" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                <option value="">Todas las plataformas</option>
                @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ $serviceId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-medium border border-slate-700 transition-colors">
                Filtrar
            </button>
            @if($search || $serviceId)
                <a href="{{ route('provider-accounts.index') }}" class="text-xs text-slate-400 hover:text-white px-2">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Grid of Accounts -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($accounts as $account)
            @php
                $diff = now()->startOfDay()->diffInDays($account->billing_due_date->copy()->startOfDay(), false);
                $occupied = $account->occupied_profiles;
                $total = $account->total_profiles;
                $percentage = $total > 0 ? round(($occupied / $total) * 100) : 0;
            @endphp
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl flex flex-col justify-between hover:border-slate-700 transition-colors">
                <div>
                    <!-- Header -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                {{ $account->service->name }}
                            </span>
                            @if($account->account_identifier)
                                <span class="text-xs text-slate-400 ml-1 font-medium">{{ $account->account_identifier }}</span>
                            @endif
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $account->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400' }}">
                            {{ ucfirst($account->status) }}
                        </span>
                    </div>

                    <!-- Email & Cost -->
                    <div class="space-y-1 mb-4">
                        <div class="text-sm font-semibold text-white font-mono truncate" title="{{ $account->email }}">
                            {{ $account->email }}
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Costo mensual:</span>
                            <span class="text-rose-400 font-bold">${{ number_format($account->cost, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Corte proveedor:</span>
                            <span class="{{ $diff <= 3 ? 'text-rose-400 font-bold' : 'text-slate-200' }}">
                                {{ $account->billing_due_date->format('d/m/Y') }}
                                <span class="text-[10px] text-slate-500">({{ $diff >= 0 ? "en {$diff}d" : "hace " . abs($diff) . "d" }})</span>
                            </span>
                        </div>
                    </div>

                    <!-- Stock progress -->
                    <div class="bg-slate-950/60 p-3 rounded-xl border border-slate-800/80 mb-4">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="text-slate-400">Ocupación:</span>
                            <span class="font-semibold text-white">
                                <strong class="text-amber-400">{{ $occupied }}</strong> / {{ $total }} ocupados
                            </span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-amber-600 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="text-[10px] text-emerald-400 mt-1 font-medium">
                            {{ $account->free_profiles }} perfiles disponibles para venta
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                    <a href="{{ route('provider-accounts.show', $account) }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300">
                        Gestionar Perfiles &rarr;
                    </a>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('provider-accounts.edit', $account) }}" title="Editar" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs">
                            ✏️
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500 bg-slate-900 border border-slate-800 rounded-2xl">
                No hay cuentas madre registradas. ¡Haz clic en "+ Nueva Cuenta Madre" para agregar una!
            </div>
        @endforelse
    </div>

    @if($accounts->hasPages())
        <div class="p-4 border-t border-slate-800">
            {{ $accounts->links() }}
        </div>
    @endif
</div>
@endsection
