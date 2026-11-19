@extends('layouts.app')

@section('title', 'Directorio de Clientes')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span>👥 Clientes</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-400 font-normal">
                    {{ $clients->total() }} registrados
                </span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Directorio consolidado de clientes y números de contacto de TecnoStar.</p>
        </div>

        <div>
            <a href="{{ route('clients.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2 cursor-pointer">
                <span>+ Nuevo Cliente</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('clients.index') }}" class="flex-1 w-full flex items-center gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Buscar por nombre, celular o correo..." 
                class="flex-1 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
            >
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-medium border border-slate-700 transition-colors">
                Buscar
            </button>
            @if($search)
                <a href="{{ route('clients.index') }}" class="text-xs text-slate-400 hover:text-white px-2">Limpiar</a>
            @endif
        </form>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <a href="{{ route('clients.index', ['review' => 1]) }}" class="px-3 py-2 rounded-xl text-xs font-medium border transition-colors flex items-center gap-2 {{ $onlyReview ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' : 'bg-slate-950 text-slate-400 border-slate-800 hover:text-white' }}">
                <span>⚠️ Requieren Contacto ({{ $reviewCount }})</span>
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Cliente</th>
                        <th class="py-3 px-4">Celular</th>
                        <th class="py-3 px-4">Correo</th>
                        <th class="py-3 px-4 text-center">Suscripciones</th>
                        <th class="py-3 px-4">Estado Contacto</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4 font-semibold text-white">
                                <a href="{{ route('clients.show', $client) }}" class="hover:text-amber-400 transition-colors">
                                    {{ $client->name }}
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                @if($client->phone)
                                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $client->phone) }}" target="_blank" class="text-amber-400/90 hover:text-amber-400 font-mono flex items-center gap-1">
                                        <span>💬 {{ $client->phone }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-500 italic">No registrado</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-400">
                                {{ $client->email ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($client->active_subscriptions_count > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {{ $client->active_subscriptions_count }} activa(s)
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] text-slate-500 bg-slate-800">
                                        Sin activas
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($client->needs_contact_review)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        ⚠️ Revisar celular
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-800 text-slate-300">
                                        OK
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('clients.show', $client) }}" title="Ver historial" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                                        👁️
                                    </a>
                                    <a href="{{ route('clients.edit', $client) }}" title="Editar" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                                        ✏️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                No se encontraron clientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
