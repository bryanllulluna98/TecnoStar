@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white">Editar Cliente: {{ $client->name }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">Actualiza la información personal o de contacto.</p>
        </div>
        <a href="{{ route('clients.show', $client) }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Volver al detalle
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                    Nombre Completo <span class="text-rose-400">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $client->name) }}" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Celular / WhatsApp
                    </label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone', $client->phone) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 font-mono"
                    >
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Correo Personal
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $client->email) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    >
                </div>
            </div>

            <div class="flex items-center gap-2 py-2">
                <input 
                    type="checkbox" 
                    id="needs_contact_review" 
                    name="needs_contact_review" 
                    value="1" 
                    {{ old('needs_contact_review', $client->needs_contact_review) ? 'checked' : '' }}
                    class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-amber-500 focus:ring-amber-500/40"
                >
                <label for="needs_contact_review" class="text-xs text-slate-300 cursor-pointer">
                    Marcar para revisión de contacto (teléfono incompleto o no verificado)
                </label>
            </div>

            <div>
                <label for="notes" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                    Notas u Observaciones
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="3" 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                >{{ old('notes', $client->notes) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                <a href="{{ route('clients.show', $client) }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
                    Actualizar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
