@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Top Welcome & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span>Tablero Principal</span>
                <span class="text-xs px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-medium">En vivo</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Resumen financiero, inventario y alertas operativas de TecnoStar.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('expirations.index') }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                <span>⏰ Ir a Vencimientos</span>
            </a>
            <a href="{{ route('subscriptions.create') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium border border-slate-700 transition-all flex items-center gap-2">
                <span>+ Nueva Venta</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Ingresos -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between text-xs text-slate-400 font-medium uppercase tracking-wider">
                <span>Ingresos del Mes</span>
                <span class="text-lg">💵</span>
            </div>
            <div class="mt-3 text-2xl font-bold text-emerald-400">
                ${{ number_format($monthlyIncome, 2) }}
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Cobros efectivos registrados</p>
        </div>

        <!-- Egresos -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between text-xs text-slate-400 font-medium uppercase tracking-wider">
                <span>Egresos del Mes</span>
                <span class="text-lg">💳</span>
            </div>
            <div class="mt-3 text-2xl font-bold text-rose-400">
                ${{ number_format($monthlyExpense, 2) }}
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Costo de cuentas mayorista</p>
        </div>

        <!-- Ganancia Neta -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between text-xs text-slate-400 font-medium uppercase tracking-wider">
                <span>Ganancia Neta</span>
                <span class="text-lg">📈</span>
            </div>
            <div class="mt-3 text-2xl font-bold {{ $monthlyNetProfit >= 0 ? 'text-amber-400' : 'text-rose-400' }}">
                ${{ number_format($monthlyNetProfit, 2) }}
            </div>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[11px] text-slate-400">Margen:</span>
                <span class="text-[11px] font-semibold {{ $profitMargin >= 50 ? 'text-emerald-400' : 'text-amber-400' }}">{{ $profitMargin }}%</span>
            </div>
        </div>

        <!-- Stock Perfiles -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between text-xs text-slate-400 font-medium uppercase tracking-wider">
                <span>Stock Disponible</span>
                <span class="text-lg">💻</span>
            </div>
            <div class="mt-3 text-2xl font-bold text-white">
                {{ $freeProfiles }} <span class="text-xs text-slate-500 font-normal">/ {{ $freeProfiles + $occupiedProfiles }} perfiles</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">{{ $totalClients }} clientes registrados</p>
        </div>
    </div>

    <!-- Main 2-Column Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Quick Expirations Table (2 cols) -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-semibold text-white flex items-center gap-2">
                        <span>⏰ Vencimientos Inmediatos</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-medium">Urgente</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Suscripciones que vencen en los próximos días con acceso directo a WhatsApp.</p>
                </div>
                <a href="{{ route('expirations.index') }}" class="text-xs text-amber-400 hover:text-amber-300 font-medium">
                    Ver todos &rarr;
                </a>
            </div>

            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800">
                            <th class="pb-3 font-medium">Cliente</th>
                            <th class="pb-3 font-medium">Servicio / Perfil</th>
                            <th class="pb-3 font-medium">Vencimiento</th>
                            <th class="pb-3 font-medium text-right">Cobro WhatsApp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($expiringSubscriptions as $sub)
                            @php
                                $diff = now()->startOfDay()->diffInDays($sub->expires_at->copy()->startOfDay(), false);
                            @endphp
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="py-3">
                                    <div class="font-medium text-white">{{ $sub->client->name ?? 'Sin nombre' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $sub->client->phone ?? 'Sin celular' }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="text-slate-200 font-medium">{{ $sub->profile->providerAccount->service->name ?? 'Servicio' }}</div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $sub->profile->name ?? 'Perfil' }} 
                                        @if($sub->profile->pin)
                                            <span class="text-amber-400">({{ $sub->profile->pin }})</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-slate-300 font-medium">{{ $sub->expires_at->format('d/m/Y') }}</div>
                                    @if($diff == 0)
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">HOY</span>
                                    @elseif($diff > 0)
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-amber-500/10 text-amber-400">en {{ $diff }} días</span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-slate-800 text-slate-400">hace {{ abs($diff) }} días</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        @if($sub->wa_charge_url)
                                            <a href="{{ $sub->wa_charge_url }}" target="_blank" title="Enviar recordatorio de cobro (${{ number_format($sub->price, 2) }})" class="p-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 transition-colors">
                                                💰
                                            </a>
                                        @endif
                                        @if($sub->wa_update_url)
                                            <a href="{{ $sub->wa_update_url }}" target="_blank" title="Enviar actualización de contraseña" class="p-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/20 transition-colors">
                                                🔐
                                            </a>
                                        @endif
                                        @if($sub->wa_profile_url)
                                            <a href="{{ $sub->wa_profile_url }}" target="_blank" title="Enviar datos de bienvenida y perfil" class="p-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/20 transition-colors">
                                                💻
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-500">
                                    No hay suscripciones próximas a vencer en este momento. ✨
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Stock by Service & Provider Alerts (1 col) -->
        <div class="space-y-6">
            <!-- Stock by Service -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <h3 class="text-sm font-semibold text-white mb-3 flex items-center justify-between">
                    <span>📦 Stock por Servicio</span>
                    <a href="{{ route('provider-accounts.index') }}" class="text-[11px] text-amber-400 hover:text-amber-300">Ver cuentas</a>
                </h3>

                <div class="space-y-3">
                    @forelse($servicesStock as $service)
                        @php
                            $percentage = $service->total_profiles > 0 ? round(($service->occupied_profiles / $service->total_profiles) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-medium text-slate-300">{{ $service->name }}</span>
                                <span class="text-[11px] text-slate-400">
                                    <strong class="text-emerald-400">{{ $service->free_profiles }}</strong> disp. / {{ $service->total_profiles }}
                                </span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-500 to-amber-600 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">No hay servicios registrados.</p>
                    @endforelse
                </div>
            </div>

            <!-- Provider Accounts Expiring Alert -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <h3 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                    <span>🔔 Cuentas Madre por Vencer</span>
                </h3>

                <div class="space-y-2.5">
                    @forelse($expiringProviderAccounts as $account)
                        <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center justify-between text-xs">
                            <div>
                                <div class="font-medium text-slate-200">{{ $account->service->name }} ({{ $account->account_identifier ?? 'Cuenta' }})</div>
                                <div class="text-[11px] text-slate-500">{{ $account->email }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-rose-400 font-semibold">{{ $account->billing_due_date->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-500">${{ number_format($account->cost, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2">No hay cuentas de proveedores próximas a vencer en los próximos 7 días.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
