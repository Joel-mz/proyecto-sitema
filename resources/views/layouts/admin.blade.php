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
        
        /* Completely hide scrollbars everywhere in sidebar/nav while allowing mouse wheel scrolling */
        .sidebar-scroll,
        aside,
        nav,
        .no-scrollbar {
            -ms-overflow-style: none !important;  /* IE and Edge */
            scrollbar-width: none !important;  /* Firefox */
        }
        .sidebar-scroll::-webkit-scrollbar,
        aside::-webkit-scrollbar,
        nav::-webkit-scrollbar,
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0px !important;
            height: 0px !important;
            background: transparent !important;
        }

        /* Collapsed Sidebar Styles */
        .sidebar-collapsed {
            width: 4.75rem !important; /* 76px */
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-group-title,
        .sidebar-collapsed .sidebar-sublinks {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-nav-item {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .sidebar-collapsed .sidebar-logo-container {
            justify-content: center !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .sidebar-collapsed .sidebar-logo-text {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-tooltip {
            display: none;
        }
        .sidebar-collapsed .group-nav:hover .sidebar-tooltip {
            display: block !important;
        }
    </style>
</head>
<body class="h-full bg-slate-100 flex overflow-hidden">

    <!-- Mobile Drawer Backdrop -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden transition-opacity duration-300 opacity-0 md:hidden"></div>

    <!-- Mobile Slide-In Sidebar Drawer -->
    <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-[#0a1931] text-slate-300 flex flex-col z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden shadow-2xl border-r border-slate-800">
        
        <!-- Mobile Sidebar Header -->
        <div class="p-4 border-b border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-md flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-black text-white text-sm block leading-tight">
                        {{ $company->name ?? 'TecnoStore' }}
                    </span>
                    <span class="text-[10px] text-blue-400 font-medium block">
                        Menú de Administración
                    </span>
                </div>
            </div>
            <button id="mobile-sidebar-close" type="button" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition" aria-label="Cerrar menú">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation Links -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto no-scrollbar text-xs font-semibold">
            
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
                    <span class="text-blue-400">+ Agregar producto</span>
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
                    <span class="text-blue-400">+ Agregar categoría</span>
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

            <!-- Enlace a Catálogo Público -->
            <div class="pt-3 border-t border-slate-800/80">
                <a href="{{ route('catalog.index') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-blue-600/20 text-blue-300 hover:bg-blue-600/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Ver Catálogo Público ↗</span>
                </a>
            </div>

        </nav>

        <!-- Cerrar Sesión Mobile Button -->
        <div class="p-4 border-t border-slate-800/80">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-rose-400 hover:bg-rose-950/40 text-xs font-semibold transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Desktop Sidebar (Collapsible & Expandable, #0a1931) -->
    <aside id="desktop-sidebar" class="w-64 bg-[#0a1931] text-slate-300 flex flex-col flex-shrink-0 z-30 transition-all duration-300 ease-in-out hidden md:flex border-r border-slate-800 relative">
        
        <!-- Sidebar Brand / Logo -->
        <div class="p-4 border-b border-slate-800/80 flex items-center justify-between sidebar-logo-container transition-all">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-md flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/>
                    </svg>
                </div>
                <div class="sidebar-logo-text truncate">
                    <span class="font-black text-white text-sm block leading-tight truncate">
                        {{ $company->name ?? 'TecnoStore' }}
                    </span>
                    <span class="text-[10px] text-blue-400 font-medium block">
                        Administrador
                    </span>
                </div>
            </div>

            <!-- Toggle Mini/Full Button inside sidebar header -->
            <button id="desktop-sidebar-toggle-btn" type="button" class="w-7 h-7 rounded-lg bg-slate-800/90 text-slate-400 hover:text-white hover:bg-blue-600 flex items-center justify-center transition flex-shrink-0 cursor-pointer" title="Minimizar / Agrandar menú">
                <svg id="sidebar-toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-3 space-y-1.5 overflow-y-auto no-scrollbar text-xs font-semibold overflow-x-hidden">
            
            <!-- Dashboard Link -->
            <div class="group-nav relative">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="sidebar-text truncate">Dashboard</span>
                </a>
                <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                    Dashboard
                </div>
            </div>

            <!-- Productos Group -->
            <div class="pt-2 space-y-1">
                <div class="sidebar-group-title px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Productos
                </div>
                <div class="group-nav relative">
                    <a href="{{ route('products.index') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('products.*') ? 'text-white font-bold bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                        <svg class="w-5 h-5 flex-shrink-0 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="sidebar-text truncate">Todos los productos</span>
                    </a>
                    <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                        Productos
                    </div>
                </div>
                <div class="sidebar-sublinks">
                    <a href="{{ route('products.create') }}" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-blue-400 hover:text-blue-300 hover:bg-slate-800/40 transition pl-8 text-[11px]">
                        <span>+ Agregar producto</span>
                    </a>
                </div>
            </div>

            <!-- Categorías Group -->
            <div class="pt-2 space-y-1">
                <div class="sidebar-group-title px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Categorías
                </div>
                <div class="group-nav relative">
                    <a href="{{ route('categories.index') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('categories.*') ? 'text-white font-bold bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                        <svg class="w-5 h-5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span class="sidebar-text truncate">Todas las categorías</span>
                    </a>
                    <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                        Categorías & Subcategorías
                    </div>
                </div>
                <div class="sidebar-sublinks">
                    <a href="{{ route('categories.create') }}" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-blue-400 hover:text-blue-300 hover:bg-slate-800/40 transition pl-8 text-[11px]">
                        <span>+ Agregar categoría / sub</span>
                    </a>
                </div>
            </div>

            <!-- Banners & Slider Group -->
            <div class="pt-2 space-y-1">
                <div class="sidebar-group-title px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Diseño & Portada
                </div>
                <div class="group-nav relative">
                    <a href="{{ route('banners.index') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('banners.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-5 h-5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="sidebar-text truncate">Banners del Slider</span>
                    </a>
                    <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                        Banners del Slider
                    </div>
                </div>
            </div>

            <!-- Cotizaciones Group -->
            <div class="pt-2 space-y-1">
                <div class="sidebar-group-title px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Ventas & Cotizaciones
                </div>
                <div class="group-nav relative">
                    <a href="{{ route('quotes.index') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('quotes.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sidebar-text truncate">Cotizaciones</span>
                    </a>
                    <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                        Cotizaciones
                    </div>
                </div>
            </div>

            <!-- Catálogo PDF -->
            <div class="pt-2 space-y-1">
                <div class="sidebar-group-title px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Catálogo PDF
                </div>
                <div class="group-nav relative">
                    <a href="{{ route('catalog.pdf') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition">
                        <svg class="w-5 h-5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sidebar-text truncate">Descargar PDF</span>
                    </a>
                    <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                        Catálogo PDF
                    </div>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->isAdmin())
                <!-- Configuración -->
                <div class="pt-2 space-y-1">
                    <div class="sidebar-group-title px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        Configuración
                    </div>
                    <div class="group-nav relative">
                        <a href="{{ route('admin.settings.edit') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <svg class="w-5 h-5 flex-shrink-0 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="sidebar-text truncate">Datos de Empresa</span>
                        </a>
                        <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                            Datos de Empresa
                        </div>
                    </div>
                    <div class="group-nav relative">
                        <a href="{{ route('users.index') }}" class="sidebar-nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('users.*') ? 'bg-[#0052cc] text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <svg class="w-5 h-5 flex-shrink-0 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="sidebar-text truncate">Usuarios y Roles</span>
                        </a>
                        <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                            Usuarios y Roles
                        </div>
                    </div>
                </div>
            @endif

            <!-- Enlace a Catálogo Público -->
            <div class="pt-3 border-t border-slate-800/80">
                <div class="group-nav relative">
                    <a href="{{ route('catalog.index') }}" target="_blank" class="sidebar-nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl bg-blue-600/20 text-blue-300 hover:bg-blue-600/30 transition">
                        <svg class="w-5 h-5 flex-shrink-0 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span class="sidebar-text truncate">Ver Catálogo Público ↗</span>
                    </a>
                    <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-slate-700">
                        Ver Catálogo Público ↗
                    </div>
                </div>
            </div>

        </nav>

        <!-- Cerrar Sesión Footer Button -->
        <div class="p-3 border-t border-slate-800/80">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <div class="group-nav relative">
                    <button type="submit" class="sidebar-nav-item w-full flex items-center gap-3 px-3 py-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-slate-800/60 text-xs font-semibold transition cursor-pointer">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="sidebar-text truncate">Cerrar sesión</span>
                    </button>
                    <div class="sidebar-tooltip hidden absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-rose-900 text-rose-100 text-xs font-semibold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none border border-rose-700">
                        Cerrar sesión
                    </div>
                </div>
            </form>
        </div>
    </aside>

    <!-- Main Admin Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#f4f6fa]">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200/90 px-3 sm:px-8 flex items-center justify-between z-10 flex-shrink-0 shadow-2xs">
            <div class="flex items-center gap-2 sm:gap-3">
                
                <!-- Desktop Sidebar Toggle Button in Header -->
                <button id="desktop-header-toggle-btn" type="button" class="hidden md:flex w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 items-center justify-center transition cursor-pointer active:scale-95" title="Minimizar / Agrandar barra lateral">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </button>

                <!-- Mobile Hamburger Button -->
                <button id="mobile-menu-toggle" type="button" class="md:hidden w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center transition cursor-pointer active:scale-95" aria-label="Abrir menú">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Mobile Brand Header -->
                <div class="flex items-center gap-2 md:hidden">
                    <span class="font-extrabold text-slate-900 text-sm truncate max-w-[130px] xs:max-w-[180px]">
                        {{ $company->name ?? 'TecnoStore' }}
                    </span>
                </div>

                <!-- Desktop / Tablet Public Link -->
                <a href="{{ route('catalog.index') }}" target="_blank" class="hidden xs:inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Ver Catálogo</span>
                </a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('catalog.index') }}" target="_blank" class="xs:hidden p-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition" title="Ver catálogo">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
                <div class="text-right hidden sm:block">
                    <span class="text-xs font-bold text-slate-800 block">{{ auth()->user()->name ?? 'Administrador' }}</span>
                    <span class="text-[10px] text-slate-400 capitalize">{{ auth()->user()->role ?? 'Admin' }}</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Main Content Area (With extra bottom padding on mobile for the bottom bar) -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8 pb-24 md:pb-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Mobile Bottom Navigation Bar -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-[#0a1931] border-t border-slate-800 flex items-center justify-around z-30 px-1 py-1 shadow-2xl">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->routeIs('admin.dashboard') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="text-[10px]">Dashboard</span>
            </a>

            <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->routeIs('products.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-[10px]">Productos</span>
            </a>

            <a href="{{ route('banners.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->routeIs('banners.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-[10px]">Slider</span>
            </a>

            <a href="{{ route('quotes.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->routeIs('quotes.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-[10px]">Cotizar</span>
            </a>

            <button id="mobile-bottom-menu-btn" type="button" class="flex flex-col items-center justify-center flex-1 py-1 text-center text-slate-400 hover:text-slate-200 transition cursor-pointer">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span class="text-[10px]">Menú</span>
            </button>
        </nav>

    </div>

    <!-- Collapsible Sidebar & Mobile Drawer JavaScript Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Drawer Controls
            const mobileToggleBtn = document.getElementById('mobile-menu-toggle');
            const mobileBottomBtn = document.getElementById('mobile-bottom-menu-btn');
            const mobileCloseBtn = document.getElementById('mobile-sidebar-close');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileBackdrop = document.getElementById('mobile-sidebar-backdrop');

            function openMobileSidebar() {
                if (!mobileSidebar || !mobileBackdrop) return;
                mobileBackdrop.classList.remove('hidden');
                setTimeout(() => {
                    mobileBackdrop.classList.remove('opacity-0');
                    mobileBackdrop.classList.add('opacity-100');
                    mobileSidebar.classList.remove('-translate-x-full');
                }, 10);
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileSidebar() {
                if (!mobileSidebar || !mobileBackdrop) return;
                mobileSidebar.classList.add('-translate-x-full');
                mobileBackdrop.classList.remove('opacity-100');
                mobileBackdrop.classList.add('opacity-0');
                setTimeout(() => {
                    mobileBackdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 300);
            }

            if (mobileToggleBtn) mobileToggleBtn.addEventListener('click', openMobileSidebar);
            if (mobileBottomBtn) mobileBottomBtn.addEventListener('click', openMobileSidebar);
            if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', closeMobileSidebar);
            if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeMobileSidebar);

            // Desktop Collapsible Sidebar
            const desktopSidebar = document.getElementById('desktop-sidebar');
            const desktopToggleBtn = document.getElementById('desktop-sidebar-toggle-btn');
            const headerToggleBtn = document.getElementById('desktop-header-toggle-btn');
            const toggleIcon = document.getElementById('sidebar-toggle-icon');

            function applySidebarState(isCollapsed) {
                if (!desktopSidebar) return;
                if (isCollapsed) {
                    desktopSidebar.classList.add('sidebar-collapsed');
                    if (toggleIcon) toggleIcon.classList.add('rotate-180');
                } else {
                    desktopSidebar.classList.remove('sidebar-collapsed');
                    if (toggleIcon) toggleIcon.classList.remove('rotate-180');
                }
            }

            // Restore from localStorage
            const savedState = localStorage.getItem('tecnostore_sidebar_collapsed') === 'true';
            applySidebarState(savedState);

            function toggleSidebar() {
                const willCollapse = !desktopSidebar.classList.contains('sidebar-collapsed');
                applySidebarState(willCollapse);
                localStorage.setItem('tecnostore_sidebar_collapsed', willCollapse ? 'true' : 'false');
            }

            if (desktopToggleBtn) desktopToggleBtn.addEventListener('click', toggleSidebar);
            if (headerToggleBtn) headerToggleBtn.addEventListener('click', toggleSidebar);
        });
    </script>

    @stack('scripts')
</body>
</html>
