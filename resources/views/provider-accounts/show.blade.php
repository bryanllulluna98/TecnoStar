@extends('layouts.app')

@section('title', 'Cuenta Madre: ' . $providerAccount->email)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    {{ $providerAccount->service->name }}
                </span>
                <h1 class="text-xl font-bold text-white font-mono">{{ $providerAccount->email }}</h1>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $providerAccount->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400' }}">
                    {{ ucfirst($providerAccount->status) }}
                </span>
            </div>
            @if($providerAccount->account_identifier)
                <p class="text-xs text-slate-400 mt-1">Etiqueta: {{ $providerAccount->account_identifier }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('provider-accounts.edit', $providerAccount) }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium border border-slate-700 transition-colors">
                ✏️ Editar Cuenta
            </a>
            <a href="{{ route('provider-accounts.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-medium border border-slate-800 transition-colors">
                &larr; Volver
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Contraseña Maestra (Cifrada con botón revelar) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Contraseña Proveedor</span>
            <div class="mt-2 flex items-center justify-between">
                <span id="passText" class="font-mono text-xs text-slate-200 select-all tracking-wider">••••••••••••</span>
                <button 
                    type="button" 
                    onclick="togglePassword()" 
                    class="text-xs text-amber-400 hover:text-amber-300 font-medium px-2 py-1 rounded bg-slate-950 border border-slate-800 cursor-pointer"
                >
                    👁️ Ver
                </button>
            </div>
            <p class="text-[10px] text-emerald-400/80 mt-1">🔒 Protegida con AES-256</p>
        </div>

        <!-- Costo -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Costo Mayorista</span>
            <div class="mt-2 text-lg font-bold text-rose-400">
                ${{ number_format($providerAccount->cost, 2) }}
            </div>
            <p class="text-[10px] text-slate-500 mt-1">Gasto mensual del negocio</p>
        </div>

        <!-- Corte Proveedor -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Corte con Proveedor</span>
            <div class="mt-2 text-sm font-semibold text-white">
                {{ $providerAccount->billing_due_date->format('d/m/Y') }}
            </div>
            @php
                $diff = now()->startOfDay()->diffInDays($providerAccount->billing_due_date->copy()->startOfDay(), false);
            @endphp
            <p class="text-[10px] mt-1 {{ $diff <= 3 ? 'text-rose-400 font-bold' : 'text-slate-400' }}">
                {{ $diff >= 0 ? "Vence en {$diff} días" : "Vencida hace " . abs($diff) . " días" }}
            </p>
        </div>

        <!-- Capacidad -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Capacidad de Perfiles</span>
            <div class="mt-2 text-lg font-bold text-white">
                {{ $providerAccount->profiles->where('status', 'occupied')->count() }} / {{ $providerAccount->max_profiles }}
            </div>
            <p class="text-[10px] text-emerald-400 mt-1 font-medium">
                {{ $providerAccount->profiles->where('status', 'free')->count() }} perfil(es) libre(s)
            </p>
        </div>
    </div>

    <!-- Profiles List -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-semibold text-white flex items-center gap-2">
                <span>💻 Perfiles de la Cuenta</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-800 text-slate-400">{{ $providerAccount->profiles->count() }}</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($providerAccount->profiles as $profile)
                @php
                    $activeSub = $profile->activeSubscription;
                    $isOccupied = $profile->status === 'occupied';
                @endphp
                <div class="bg-slate-950/70 border border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                    <div>
                        <!-- Profile Header -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-bold text-white text-sm">{{ $profile->name }}</span>
                            @if($isOccupied)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                    Ocupado
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    Libre (Disponible)
                                </span>
                            @endif
                        </div>

                        <!-- Current Client (if occupied) -->
                        @if($isOccupied && $activeSub)
                            <div class="p-2.5 rounded-lg bg-slate-900/80 border border-slate-800 mb-3 text-xs">
                                <div class="text-slate-400 text-[10px] uppercase tracking-wider font-semibold">Cliente Asignado:</div>
                                <div class="font-semibold text-white mt-0.5">
                                    <a href="{{ route('clients.show', $activeSub->client) }}" class="hover:text-amber-400">
                                        {{ $activeSub->client->name }}
                                    </a>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1 flex items-center justify-between">
                                    <span>Vence: <strong class="text-slate-200">{{ $activeSub->expires_at->format('d/m/Y') }}</strong></span>
                                    <span class="font-bold text-emerald-400">${{ number_format($activeSub->price, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="p-2.5 rounded-lg bg-slate-900/40 border border-dashed border-slate-800 mb-3 text-xs text-center text-slate-500">
                                Perfil disponible para asignar a un cliente.
                            </div>
                        @endif

                        <!-- Edit PIN / Name form -->
                        <form action="{{ route('profiles.update', $profile) }}" method="POST" class="flex items-center gap-2 pt-2 border-t border-slate-800/80">
                            @csrf
                            @method('PATCH')
                            <div class="flex-1">
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ $profile->name }}" 
                                    title="Nombre de perfil"
                                    class="w-full px-2 py-1 bg-slate-900 border border-slate-800 rounded text-xs text-white"
                                >
                            </div>
                            <div class="w-24">
                                <input 
                                    type="text" 
                                    name="pin" 
                                    value="{{ $profile->pin }}" 
                                    placeholder="PIN" 
                                    title="Código PIN"
                                    class="w-full px-2 py-1 bg-slate-900 border border-slate-800 rounded text-xs font-mono text-amber-400 text-center"
                                >
                            </div>
                            <button type="submit" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-xs">
                                💾
                            </button>
                        </form>
                    </div>

                    <!-- Bottom Action: Sell if Free -->
                    @if(!$isOccupied)
                        <div class="mt-4 pt-3 border-t border-slate-800">
                            <a href="{{ route('subscriptions.create', ['profile_id' => $profile->id]) }}" class="w-full py-2 px-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-semibold text-xs flex items-center justify-center gap-1.5 transition-colors">
                                <span>+ Vender este Perfil</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    let visible = false;
    const realPassword = @json($providerAccount->password);

    function togglePassword() {
        const passText = document.getElementById('passText');
        const btn = event.target;
        if (!visible) {
            passText.textContent = realPassword;
            btn.textContent = '🙈 Ocultar';
            visible = true;
        } else {
            passText.textContent = '••••••••••••';
            btn.textContent = '👁️ Ver';
            visible = false;
        }
    }
</script>
@endpush
@endsection
