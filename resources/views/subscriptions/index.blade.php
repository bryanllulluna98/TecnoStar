@extends('layouts.app')

@section('title', 'Historial de Suscripciones')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span>📋 Suscripciones y Ventas</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-400 font-normal">
                    {{ $subscriptions->total() }} registros
                </span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Control histórico de asignaciones de clientes a perfiles.</p>
        </div>

        <div>
            <a href="{{ route('subscriptions.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                <span>+ Nueva Venta / Suscripción</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('subscriptions.index') }}" class="flex-1 w-full flex flex-wrap items-center gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Buscar por cliente o teléfono..." 
                class="flex-1 min-w-[200px] px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
            >

            <select name="status" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                <option value="">Todos los estados</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Activa</option>
                <option value="expiring_soon" {{ $status === 'expiring_soon' ? 'selected' : '' }}>Por vencer</option>
                <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Vencida</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-medium border border-slate-700 transition-colors">
                Filtrar
            </button>
            @if($search || $status)
                <a href="{{ route('subscriptions.index') }}" class="text-xs text-slate-400 hover:text-white px-2">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Cliente</th>
                        <th class="py-3 px-4">Servicio & Cuenta</th>
                        <th class="py-3 px-4">Perfil</th>
                        <th class="py-3 px-4">Precio Pactado</th>
                        <th class="py-3 px-4">Vigencia</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-white">
                                <a href="{{ route('clients.show', $sub->client) }}" class="hover:text-amber-400">
                                    {{ $sub->client->name }}
                                </a>
                                <span class="text-[11px] text-slate-500 block font-normal">{{ $sub->client->phone ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-200">{{ $sub->profile->providerAccount->service->name ?? 'Servicio' }}</span>
                                <span class="text-[11px] text-slate-500 block truncate max-w-[180px]">{{ $sub->profile->providerAccount->email ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-slate-300 font-medium">{{ $sub->profile->name ?? 'Perfil' }}</span>
                                @if($sub->profile->pin)
                                    <span class="text-[11px] text-amber-400 block font-mono">PIN: {{ $sub->profile->pin }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-bold text-emerald-400">
                                ${{ number_format($sub->price, 2) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $sub->starts_at->format('d/m/Y') }} &rarr; <strong>{{ $sub->expires_at->format('d/m/Y') }}</strong>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($sub->status === 'active')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Activa</span>
                                @elseif($sub->status === 'expired')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">Vencida</span>
                                @elseif($sub->status === 'cancelled')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-800 text-slate-400">Cancelada</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400">{{ ucfirst($sub->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($sub->status !== 'cancelled')
                                    <form action="{{ route('subscriptions.cancel', $sub) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta suscripción? El perfil quedará libre nuevamente.')" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded bg-slate-800 hover:bg-rose-500/20 text-rose-400 text-[11px] font-medium border border-slate-700 hover:border-rose-500/30 transition-colors cursor-pointer">
                                            Cancelar / Liberar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                No se encontraron suscripciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
