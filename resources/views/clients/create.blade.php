@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white">Registrar Nuevo Cliente</h1>
            <p class="text-xs text-slate-400 mt-0.5">Ingresa los datos para registrar un cliente en la base de datos.</p>
        </div>
        <a href="{{ route('clients.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Volver al listado
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <form action="{{ route('clients.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                    Nombre Completo <span class="text-rose-400">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                    placeholder="Ej. Carlos Pérez"
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
                        value="{{ old('phone') }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 font-mono"
                        placeholder="Ej. +593 99 876 5432 o 0998765432"
                    >
                    <p class="text-[10px] text-slate-500 mt-1">Se normalizará automáticamente a formato internacional.</p>
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">
                        Correo Personal (Opcional)
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                        placeholder="cliente@gmail.com"
                    >
                </div>
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
                    placeholder="Preferencias del cliente, método de pago habitual, etc."
                >{{ old('notes') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                <a href="{{ route('clients.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
                    Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
