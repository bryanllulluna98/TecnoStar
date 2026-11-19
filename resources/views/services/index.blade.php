@extends('layouts.app')

@section('title', 'Catálogo de Servicios')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span>🎬 Catálogo de Servicios</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Plataformas de streaming soportadas y precios sugeridos de venta.</p>
        </div>
    </div>

    <!-- 2 Column: Add service form + list -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- List of Services (2 cols) -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="p-4 border-b border-slate-800">
                <h2 class="text-sm font-semibold text-white">Plataformas Registradas</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-4">Plataforma</th>
                            <th class="py-3 px-4">Modalidad</th>
                            <th class="py-3 px-4">Precio Sugerido</th>
                            <th class="py-3 px-4">Perfiles</th>
                            <th class="py-3 px-4">Cuentas Madre</th>
                            <th class="py-3 px-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($services as $service)
                            <tr class="hover:bg-slate-800/40">
                                <td class="py-3.5 px-4 font-bold text-white">
                                    {{ $service->name }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $service->type === 'profile' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                        {{ $service->type === 'profile' ? 'Por Perfiles' : 'Cuenta Completa' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-emerald-400">
                                    ${{ number_format($service->suggested_price, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-300">
                                    {{ $service->profiles_count }} total
                                </td>
                                <td class="py-3.5 px-4 text-slate-300">
                                    {{ $service->provider_accounts_count }} activa(s)
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $service->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }}">
                                        {{ $service->is_active ? 'Habilitado' : 'Desactivado' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Form (1 col) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
            <h2 class="text-sm font-semibold text-white mb-4">+ Agregar Plataforma</h2>

            <form action="{{ route('services.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Nombre</label>
                    <input type="text" name="name" required placeholder="Ej. Crunchyroll" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Modalidad</label>
                    <select name="type" required class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white">
                        <option value="profile">Por Perfiles (Pantallas)</option>
                        <option value="full_account">Cuenta Completa / Familiar</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">Precio Sugerido ($)</label>
                        <input type="number" step="0.01" name="suggested_price" value="5.00" required class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">Perfiles Base</label>
                        <input type="number" name="default_profiles" value="5" min="1" max="20" required class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white font-mono">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-semibold text-xs shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
                    Guardar Plataforma
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
