@extends('layouts.app')

@section('title', 'Plantillas de Mensajes WhatsApp')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
            <span>💬 Plantillas de WhatsApp</span>
        </h1>
        <p class="text-xs text-slate-400 mt-1">
            Personaliza los mensajes dinámicos que se abren automáticamente en WhatsApp para cobros, credenciales y avisos.
        </p>
    </div>

    <!-- Tags Helper Box -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg">
        <h3 class="text-xs font-semibold text-amber-400 uppercase tracking-wider mb-2">Variables dinámicas disponibles:</h3>
        <div class="flex flex-wrap gap-2 text-xs">
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{client_name}}</span>
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{service_name}}</span>
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{profile_name}}</span>
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{pin}}</span>
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{price}}</span>
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{expires_at}}</span>
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{account_email}}</span>
            <span class="px-2 py-1 rounded bg-slate-950 border border-slate-800 font-mono text-slate-300">@{{account_password}}</span>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($templates as $template)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-mono font-bold text-amber-400 uppercase">
                            {{ $template->code }}
                        </span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $template->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }}">
                            {{ $template->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    <form action="{{ route('message-templates.update', $template) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Título</label>
                            <input 
                                type="text" 
                                name="title" 
                                value="{{ $template->title }}" 
                                required 
                                class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Cuerpo del Mensaje</label>
                            <textarea 
                                name="body" 
                                rows="9" 
                                required 
                                class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs font-mono text-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                            >{{ $template->body }}</textarea>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                                <input type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }} class="rounded bg-slate-950 border-slate-800 text-amber-500">
                                <span>Habilitada</span>
                            </label>

                            <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-semibold text-xs transition-colors cursor-pointer">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
