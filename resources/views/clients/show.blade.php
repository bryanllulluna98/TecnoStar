@extends('layouts.app')

@section('title', $client->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-white">{{ $client->name }}</h1>
                @if($client->needs_contact_review)
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        ⚠️ Requiere revisión
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-400 mt-1">Registrado el {{ $client->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('clients.edit', $client) }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium border border-slate-700 transition-colors">
                ✏️ Editar Datos
            </a>
            <a href="{{ route('subscriptions.create', ['client_id' => $client->id]) }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all">
                + Asignar Suscripción
            </a>
        </div>
    </div>

    <!-- Contact Info Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Celular / WhatsApp</span>
            <div class="mt-2 text-sm font-semibold text-white">
                @if($client->phone)
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $client->phone) }}" target="_blank" class="text-amber-400 hover:text-amber-300 flex items-center gap-2">
                        <span>📱 {{ $client->phone }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Abrir chat</span>
                    </a>
                @else
                    <span class="text-rose-400 text-xs">Sin teléfono registrado</span>
                @endif
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Correo Electrónico</span>
            <div class="mt-2 text-sm text-slate-200">
                {{ $client->email ?? 'No registrado' }}
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Notas Internas</span>
            <div class="mt-2 text-xs text-slate-400">
                {{ $client->notes ?: 'Sin observaciones adicionales.' }}
            </div>
        </div>
    </div>

    <!-- Subscriptions Section -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-semibold text-white flex items-center gap-2">
                <span>📋 Suscripciones y Perfiles Asignados</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-800 text-slate-400">{{ $client->subscriptions->count() }}</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3 font-medium">Servicio</th>
                        <th class="pb-3 font-medium">Perfil Asignado</th>
                        <th class="pb-3 font-medium">PIN</th>
                        <th class="pb-3 font-medium">Precio</th>
                        <th class="pb-3 font-medium">Vigencia</th>
                        <th class="pb-3 font-medium">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($client->subscriptions as $sub)
                        <tr class="hover:bg-slate-800/30">
                            <td class="py-3 font-semibold text-white">
                                {{ $sub->profile->providerAccount->service->name ?? 'Servicio' }}
                                <span class="text-[11px] text-slate-500 block font-normal">{{ $sub->profile->providerAccount->email ?? '' }}</span>
                            </td>
                            <td class="py-3 text-slate-300 font-medium">
                                {{ $sub->profile->name ?? 'Perfil' }}
                            </td>
                            <td class="py-3 font-mono text-amber-400">
                                {{ $sub->profile->pin ?? '—' }}
                            </td>
                            <td class="py-3 font-semibold text-emerald-400">
                                ${{ number_format($sub->price, 2) }}
                            </td>
                            <td class="py-3 text-slate-300">
                                {{ $sub->starts_at->format('d/m/Y') }} &rarr; <strong>{{ $sub->expires_at->format('d/m/Y') }}</strong>
                            </td>
                            <td class="py-3">
                                @if($sub->status === 'active')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Activa</span>
                                @elseif($sub->status === 'expired')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">Vencida</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-800 text-slate-400">{{ ucfirst($sub->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                Este cliente aún no tiene perfiles ni suscripciones asignadas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
