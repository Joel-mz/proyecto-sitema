<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Catálogo Virtual de Productos') - {{ $company->name ?? 'Catálogo' }}</title>
    <meta name="description" content="@yield('meta_description', $company->description ?? 'Descubre nuestro catálogo virtual con los mejores productos y precios actualizados.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased text-slate-800 bg-slate-50 selection:bg-blue-600 selection:text-white">

    <!-- Top Contact & Location Announcement Strip -->
    @if(isset($company))
        <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
            <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-4 flex-wrap">
                    @if($company->address || $company->city_province || $company->region)
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $company->address }}{{ $company->city_province ? ', ' . $company->city_province : '' }}{{ $company->region ? ' - ' . $company->region : '' }}</span>
                        </div>
                    @endif

                    @if($company->phone)
                        <div class="hidden sm:flex items-center gap-1.5 text-slate-400">
                            <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $company->phone }}</span>
                        </div>
                    @endif
                </div>

                @if($company->whatsapp)
                    <div class="flex items-center gap-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 font-semibold transition">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                            </svg>
                            <span>WhatsApp Directo</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Top Navigation -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                <!-- Logo & Business Name -->
                <a href="{{ route('catalog.index') }}" class="flex items-center gap-3 flex-shrink-0 group">
                    @if(isset($company) && $company->logo && file_exists(public_path('storage/' . $company->logo)))
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-12 max-w-[180px] object-contain group-hover:scale-105 transition transform">
                    @else
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-blue-500/25 group-hover:scale-105 transition transform flex-shrink-0">
                            {{ strtoupper(substr($company->name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-xl font-extrabold text-slate-900 tracking-tight block leading-tight">
                                {{ $company->name ?? 'TECHSTORE' }}
                            </span>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest block">
                                Catálogo Virtual
                            </span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('catalog.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('catalog.index') && !request('categoria') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Inicio
                    </a>
                    <a href="{{ route('catalog.index') }}#categorias" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                        Categorías
                    </a>
                    <a href="{{ route('catalog.index') }}#productos" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                        Productos
                    </a>
                </nav>

                <!-- Action Button: Download PDF & Admin Link -->
                <div class="flex items-center gap-3">
                    @if(isset($company) && $company->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank"
                           class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200 transition">
                            <svg class="w-4 h-4 fill-current text-emerald-600" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                            </svg>
                            <span>Consultar</span>
                        </a>
                    @endif

                    <a href="{{ route('catalog.pdf') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-md shadow-rose-600/20 transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Descargar PDF</span>
                    </a>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                            <span>Panel Admin</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="p-2 text-slate-400 hover:text-slate-600 transition" title="Acceso Administrador">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        @if(isset($company) && $company->logo && file_exists(public_path('storage/' . $company->logo)))
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-10 max-w-[150px] object-contain bg-white/10 p-1.5 rounded-xl">
                        @else
                            <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-extrabold text-lg">
                                {{ strtoupper(substr($company->name ?? 'C', 0, 1)) }}
                            </div>
                            <span class="text-lg font-bold text-white tracking-tight">{{ $company->name ?? 'TECHSTORE' }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ $company->description ?? 'Catálogo virtual especializado en productos de cómputo y accesorios con los mejores precios.' }}
                    </p>
                </div>

                <!-- Col 2: Ubicación Física -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Ubicación y Local</h3>
                    <div class="space-y-2 text-xs">
                        @if($company->address)
                            <p class="text-slate-300 flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $company->address }}</span>
                            </p>
                        @endif

                        @if($company->city_province || $company->region)
                            <p class="text-slate-400 pl-6">
                                <strong>Provincia:</strong> {{ $company->city_province ?? '-' }}<br>
                                <strong>Región:</strong> {{ $company->region ?? '-' }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Col 3: Contacto -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Contacto Directo</h3>
                    <div class="space-y-2 text-xs">
                        @if($company->phone)
                            <p class="text-slate-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>{{ $company->phone }}</span>
                            </p>
                        @endif

                        @if($company->whatsapp)
                            <p class="flex items-center gap-2">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1.5 transition">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                                    </svg>
                                    <span>WhatsApp: {{ $company->whatsapp }}</span>
                                </a>
                            </p>
                        @endif

                        @if($company->email)
                            <p class="text-slate-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $company->email }}</span>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Col 4: Enlaces Rápidos -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Accesos Rápidos</h3>
                    <div class="flex flex-col space-y-2 text-xs">
                        <a href="{{ route('catalog.index') }}" class="hover:text-white transition">Inicio del Catálogo</a>
                        <a href="{{ route('catalog.index') }}#categorias" class="hover:text-white transition">Lista de Categorías</a>
                        <a href="{{ route('catalog.pdf') }}" class="hover:text-white transition">Descargar Catálogo (PDF)</a>
                        <a href="{{ route('login') }}" class="hover:text-white transition">Panel de Administración</a>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $company->name ?? 'TechStore' }}. Todos los derechos reservados.</p>
                <p class="mt-1">Precios expresados en Soles Peruanos (S/) sujetos a cambios sin previo aviso.</p>
            </div>
        </div>
    </footer>

</body>
</html>
