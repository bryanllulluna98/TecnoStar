<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — TecnoStar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-slate-900 border border-slate-800 p-2 shadow-xl shadow-amber-500/5 mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo de TecnoStar" class="w-full h-full object-contain" onerror="this.onerror=null; this.parentElement.innerHTML='⭐';">
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">TecnoStar</h1>
            <p class="text-sm text-slate-400 mt-1">Sistema de Gestión de Cuentas y Streaming</p>
        </div>

        <!-- Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl backdrop-blur-sm">
            <h2 class="text-xl font-semibold text-white mb-6">Iniciar Sesión</h2>
            @if (session('info'))
                <div
                    class="mb-5 p-3.5 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div
                    class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email"
                        class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email" id="email" name="email" value="admin@tecnostar.com" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-colors text-sm"
                        placeholder="ejemplo@tecnostar.com">
                </div>

                <div>
                    <label for="password"
                        class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-2">
                        Contraseña
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-colors text-sm"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-amber-500 focus:ring-amber-500/40">
                        <span class="text-slate-300">Recordar sesión</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-semibold text-sm shadow-lg shadow-amber-500/20 focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all cursor-pointer">
                    Ingresar al Panel
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-500 mt-8">
            TecnoStar &copy; {{ date('Y') }} — Todos los derechos reservados
        </p>
    </div>
</body>

</html>
