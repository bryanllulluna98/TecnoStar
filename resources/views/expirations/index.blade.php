@extends('layouts.app')

@section('title', 'Tablero de Vencimientos y Cobros')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span>⏰ Tablero de Vencimientos</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Centro de cobranzas automáticas por WhatsApp y control de ciclos de corte.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-400">
                Fecha del sistema: <strong class="text-white">{{ now()->format('d/m/Y') }}</strong>
            </span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-800 pb-3">
        <a href="{{ route('expirations.index', ['tab' => 'today']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-colors {{ $tab === 'today' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
            <span>🔴 Vencen Hoy</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'today' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $todayCount }}</span>
        </a>

        <a href="{{ route('expirations.index', ['tab' => 'upcoming']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-colors {{ $tab === 'upcoming' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
            <span>🟡 Vencen en 1-3 días</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'upcoming' ? 'bg-slate-950/20 text-slate-950' : 'bg-slate-800 text-slate-400' }}">{{ $upcomingCount }}</span>
        </a>

        <a href="{{ route('expirations.index', ['tab' => 'expired']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-colors {{ $tab === 'expired' ? 'bg-slate-700 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
            <span>⚫ Vencidos</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-800 text-slate-400">{{ $expiredCount }}</span>
        </a>

        <a href="{{ route('expirations.index', ['tab' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-colors {{ $tab === 'all' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
            <span>📋 Todos</span>
        </a>

        <div class="h-5 w-px bg-slate-800 mx-1 hidden sm:block"></div>

        <a href="{{ route('expirations.index', ['tab' => 'provider']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-colors {{ $tab === 'provider' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
            <span>🔑 Cuentas Proveedor</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'provider' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $providerCount }}</span>
        </a>
    </div>

    @if($tab !== 'provider')
        <!-- Subscriptions Table -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-4">Cliente / Contacto</th>
                            <th class="py-3 px-4">Servicio & Cuenta</th>
                            <th class="py-3 px-4">Perfil & PIN</th>
                            <th class="py-3 px-4">Vencimiento</th>
                            <th class="py-3 px-4 text-center">Cobrar WhatsApp</th>
                            <th class="py-3 px-4 text-right">Renovación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($subscriptions as $sub)
                            @php
                                $diff = now()->startOfDay()->diffInDays($sub->expires_at->copy()->startOfDay(), false);
                            @endphp
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <!-- Cliente -->
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-white">{{ $sub->client->name }}</div>
                                    @if($sub->client->phone)
                                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $sub->client->phone) }}" target="_blank" class="text-[11px] text-amber-400/80 hover:text-amber-400 flex items-center gap-1 mt-0.5">
                                            <span>📱 {{ $sub->client->phone }}</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-rose-400 italic">Sin celular registrado</span>
                                    @endif
                                </td>

                                <!-- Servicio -->
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-800 text-slate-200 border border-slate-700">
                                        {{ $sub->profile->providerAccount->service->name ?? 'Servicio' }}
                                    </span>
                                    <div class="text-[11px] text-slate-500 mt-0.5 truncate max-w-[180px]">
                                        {{ $sub->profile->providerAccount->email ?? '' }}
                                    </div>
                                </td>

                                <!-- Perfil -->
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-200">{{ $sub->profile->name ?? 'Perfil' }}</div>
                                    @if($sub->profile->pin)
                                        <div class="text-[11px] text-amber-400 font-mono">PIN: {{ $sub->profile->pin }}</div>
                                    @else
                                        <div class="text-[11px] text-slate-500">Sin PIN</div>
                                    @endif
                                </td>

                                <!-- Vencimiento & Días -->
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-200">{{ $sub->expires_at->format('d/m/Y') }}</div>
                                    <div class="mt-1">
                                        @if($diff == 0)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                                🚨 Vence HOY
                                            </span>
                                        @elseif($diff > 0)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                en {{ $diff }} día(s)
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-950/40 text-rose-400 border border-rose-800">
                                                Vencido hace {{ abs($diff) }} d
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Botones WhatsApp -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($sub->wa_charge_url)
                                            <a href="{{ $sub->wa_charge_url }}" target="_blank" title="💰 Recordatorio de Cobro (${{ number_format($sub->price, 2) }})" class="p-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm transition-transform hover:scale-105">
                                                💰
                                            </a>
                                        @endif

                                        @if($sub->wa_update_url)
                                            <a href="{{ $sub->wa_update_url }}" target="_blank" title="🔐 Enviar nueva contraseña" class="p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-blue-400 text-sm transition-transform hover:scale-105">
                                                🔐
                                            </a>
                                        @endif

                                        @if($sub->wa_profile_url)
                                            <a href="{{ $sub->wa_profile_url }}" target="_blank" title="💻 Datos de bienvenida / credenciales" class="p-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 text-sm transition-transform hover:scale-105">
                                                💻
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Renovación Rápida -->
                                <td class="py-3.5 px-4 text-right">
                                    <form action="{{ route('expirations.renew', $sub) }}" method="POST" class="inline-flex items-center gap-1.5">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $sub->price }}">
                                        <input type="hidden" name="months" value="1">
                                        <select name="payment_method" class="bg-slate-950 border border-slate-700 text-[11px] rounded-lg px-2 py-1.5 text-slate-300 focus:outline-none focus:border-amber-500">
                                            <option value="transfer_pichincha">Pichincha</option>
                                            <option value="transfer_guayaquil">Guayaquil</option>
                                            <option value="deuna">Deuna</option>
                                            <option value="cash">Efectivo</option>
                                            <option value="other">Otro</option>
                                        </select>
                                        <button type="submit" title="Renovar 1 mes por ${{ number_format($sub->price, 2) }}" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-[11px] shadow transition-colors cursor-pointer">
                                            +1 Mes
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500">
                                    No hay suscripciones que coincidan con este filtro. ✨
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
    @else
        <!-- Provider Accounts Table -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-4">Plataforma & Identificador</th>
                            <th class="py-3 px-4">Correo Proveedor</th>
                            <th class="py-3 px-4">Costo Mayorista</th>
                            <th class="py-3 px-4">Corte con Proveedor</th>
                            <th class="py-3 px-4">Perfiles</th>
                            <th class="py-3 px-4 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($providerAccounts as $account)
                            @php
                                $diff = now()->startOfDay()->diffInDays($account->billing_due_date->copy()->startOfDay(), false);
                            @endphp
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-white">
                                    {{ $account->service->name }}
                                    <span class="text-xs font-normal text-slate-400 block">{{ $account->account_identifier ?? 'Cuenta' }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-300">
                                    {{ $account->email }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-rose-400">
                                    ${{ number_format($account->cost, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-slate-200">{{ $account->billing_due_date->format('d/m/Y') }}</span>
                                    <div class="mt-0.5">
                                        @if($diff <= 3)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                                Corte en {{ $diff }} días
                                            </span>
                                        @else
                                            <span class="text-[11px] text-slate-400">en {{ $diff }} días</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-300">
                                    {{ $account->max_profiles }} máx.
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500">
                                    No hay cuentas proveedor registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($providerAccounts instanceof \Illuminate\Pagination\LengthAwarePaginator && $providerAccounts->hasPages())
                <div class="p-4 border-t border-slate-800">
                    {{ $providerAccounts->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
