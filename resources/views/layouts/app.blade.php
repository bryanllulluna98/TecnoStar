<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') — TecnoStar ⭐</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full text-slate-100 flex flex-col md:flex-row antialiased">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-slate-900 border-r border-slate-800 flex flex-col flex-shrink-0">
        <!-- Logo -->
        <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-800">
            <div class="w-9 h-9 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-center p-1 overflow-hidden flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.onerror=null; this.parentElement.innerHTML='⭐';">
            </div>
            <div>
                <span class="text-base font-bold tracking-tight text-white block leading-tight">TecnoStar</span>
                <span class="text-[10px] uppercase tracking-wider text-amber-400 font-semibold">Streaming Pro</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-amber-500 text-slate-950 font-semibold shadow-lg shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-base">📊</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('expirations.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('expirations.*') ? 'bg-amber-500 text-slate-950 font-semibold shadow-lg shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <span class="text-base">⏰</span>
                    <span>Vencimientos</span>
                </div>
                <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full {{ request()->routeIs('expirations.*') ? 'bg-slate-950/20 text-slate-950' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                    Cobros
                </span>
            </a>

            <div class="pt-4 pb-1">
                <span class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Operación</span>
            </div>

            <a href="{{ route('provider-accounts.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('provider-accounts.*') ? 'bg-amber-500 text-slate-950 font-semibold shadow-lg shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-base">🔑</span>
                <span>Cuentas Madre</span>
            </a>

            <a href="{{ route('clients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('clients.*') ? 'bg-amber-500 text-slate-950 font-semibold shadow-lg shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-base">👥</span>
                <span>Clientes</span>
            </a>

            <a href="{{ route('subscriptions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('subscriptions.*') ? 'bg-amber-500 text-slate-950 font-semibold shadow-lg shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-base">📋</span>
                <span>Suscripciones</span>
            </a>

            <div class="pt-4 pb-1">
                <span class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Ajustes</span>
            </div>

            <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('services.*') ? 'bg-amber-500 text-slate-950 font-semibold shadow-lg shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-base">🎬</span>
                <span>Servicios</span>
            </a>

            <a href="{{ route('message-templates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('message-templates.*') ? 'bg-amber-500 text-slate-950 font-semibold shadow-lg shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-base">💬</span>
                <span>Plantillas WhatsApp</span>
            </a>
        </nav>

        <!-- User / Logout -->
        <div class="p-4 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-xs font-semibold text-amber-400">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="truncate">
                    <span class="text-xs font-medium text-white block truncate">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <span class="text-[11px] text-slate-500 block truncate">{{ Auth::user()->email ?? '' }}</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Cerrar sesión" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Navbar -->
        <header class="h-16 bg-slate-900/60 backdrop-blur-md border-b border-slate-800 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Ecuador (UTC-5): <strong class="text-slate-200">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</strong></span>
            </div>

            <div>
                <a href="{{ route('expirations.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-400 text-xs font-medium transition-colors">
                    <span>⚡ Cobros rápidos WhatsApp</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-400">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-400/60 hover:text-rose-400">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
                    <p class="font-semibold mb-1">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
