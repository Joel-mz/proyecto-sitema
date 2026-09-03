<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración') - {{ $company->name ?? 'TecnoStore' }}</title>

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
                            500: '#0052cc',
                            600: '#1d4ed8',
                            700: '#1e40af',
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
<body class="h-full bg-slate-100 flex overflow-hidden">

    <!-- Admin Sidebar (Exact TecnoStore Dark Navy Style: #0a1931) -->
    <aside class="w-64 bg-[#0a1931] text-slate-300 flex flex-col flex-shrink-0 z-30 transition-all duration-300 hidden md:flex border-r border-slate-800">
        
        <!-- Sidebar Brand / Logo -->
        <div class="p-5 border-b border-slate-800/80 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-md flex-shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/>
                </svg>
            </div>
            <div>
                <span class="font-black text-white text-base block leading-tight">
                    {{ $company->name ?? 'TecnoStore' }}
                </span>
                <span class="text-[11px] text-blue-400 font-medium block">
                    Administrador
                </span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-xs font-semibold">
            
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Productos Group -->
            <div class="pt-2 space-y-1">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Productos
                </div>
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition pl-6 {{ request()->routeIs('products.index') ? 'text-white font-bold bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                    <span>• Todos los productos</span>
                </a>
                <a href="{{ route('products.create') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition pl-6 {{ request()->routeIs('products.create') ? 'text-white font-bold bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                    <span>• Agregar producto</span>
                </a>
            </div>

            <!-- Categorías Group -->
            <div class="pt-2 space-y-1">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Categorías
                </div>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition pl-6 {{ request()->routeIs('categories.index') ? 'text-white font-bold bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                    <span>• Todas las categorías</span>
                </a>
                <a href="{{ route('categories.create') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition pl-6 {{ request()->routeIs('categories.create') ? 'text-white font-bold bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                    <span>• Agregar categoría</span>
                </a>
            </div>

            <!-- Banners & Slider Group -->
            <div class="pt-2 space-y-1">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Diseño & Portada
                </div>
                <a href="{{ route('banners.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('banners.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Banners del Slider</span>
                </a>
            </div>

            <!-- Cotizaciones Group -->
            <div class="pt-2 space-y-1">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Ventas & Cotizaciones
                </div>
                <a href="{{ route('quotes.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('quotes.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Cotizaciones</span>
                </a>
            </div>

            <!-- Catálogo PDF -->
            <div class="pt-2 space-y-1">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Catálogo PDF
                </div>
                <a href="{{ route('catalog.pdf') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Descargar PDF</span>
                </a>
            </div>

            @if(auth()->check() && auth()->user()->isAdmin())
                <!-- Configuración -->
                <div class="pt-2 space-y-1">
                    <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        Configuración
                    </div>
                    <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Datos de Empresa</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('users.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Usuarios y Roles</span>
                    </a>
                </div>
            @endif

        </nav>

        <!-- Cerrar Sesión Footer Button -->
        <div class="p-4 border-t border-slate-800/80">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-slate-800/60 text-xs font-semibold transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#f4f6fa]">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between z-10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <a href="{{ route('catalog.index') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Ver Catálogo Público</span>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <span class="text-xs font-bold text-slate-800 block">{{ auth()->user()->name ?? 'Administrador' }}</span>
                    <span class="text-[10px] text-slate-400 capitalize">{{ auth()->user()->role ?? 'Admin' }}</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
