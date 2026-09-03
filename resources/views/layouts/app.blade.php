<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Catálogo Virtual de Cómputo y Accesorios') - {{ $company->name ?? 'TecnoStore' }}</title>
    <meta name="description" content="@yield('meta_description', $company->description ?? 'Los mejores productos con la mejor calidad y al mejor precio en cómputo y accesorios.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
                        navy: {
                            800: '#0c2b74',
                            900: '#0a235c',
                            950: '#07173e',
                        },
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased text-slate-800 bg-[#f4f6fa] selection:bg-blue-600 selection:text-white pb-16 sm:pb-0 relative">

    <!-- Top Main Header: Exact TecnoStore Royal Navy Blue Style -->
    <header class="sticky top-0 z-40 bg-[#0c2b74] text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 sm:gap-6">
                
                <!-- Brand Logo (TecnoStore icon + text + subtitle) -->
                <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 sm:gap-3 flex-shrink-0 group">
                    @if(isset($company) && $company->logo && file_exists(public_path('storage/' . $company->logo)))
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-9 sm:h-11 max-w-[140px] object-contain">
                    @else
                        <!-- TecnoStore Bag/Store Icon -->
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-md flex-shrink-0 group-hover:bg-blue-500 transition">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12zm-7-8c-1.66 0-3-1.34-3-3H7c0 2.76 2.24 5 5 5s5-2.24 5-5h-2c0 1.66-1.34 3-3 3z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-base sm:text-xl font-black tracking-tight block leading-tight text-white">
                                {{ $company->name ?? 'TecnoStore' }}
                            </span>
                            <span class="text-[10px] sm:text-[11px] text-blue-200 font-medium block leading-none">
                                Catálogo de Cómputo y Accesorios
                            </span>
                        </div>
                    @endif
                </a>

                <!-- Search Bar in Header (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-md mx-2">
                    <form action="{{ route('catalog.index') }}" method="GET" class="w-full relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar productos..."
                               class="w-full pl-4 pr-10 py-2 sm:py-2.5 rounded-full bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-inner">
                        <button type="submit" class="absolute right-3 text-slate-400 hover:text-blue-600 transition" title="Buscar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Navigation Links & Action Buttons -->
                <div class="flex items-center gap-2 sm:gap-5">
                    <nav class="hidden lg:flex items-center gap-4 text-xs sm:text-sm font-semibold text-slate-200">
                        <a href="{{ route('catalog.index') }}" class="hover:text-white transition {{ request()->routeIs('catalog.index') && !request('categoria') ? 'text-white font-bold' : '' }}">
                            Inicio
                        </a>
                        <a href="{{ route('catalog.index') }}#categorias" class="hover:text-white transition">
                            Categorías
                        </a>
                        <a href="{{ route('catalog.index') }}#productos" class="hover:text-white transition">
                            Productos
                        </a>
                    </nav>

                    <!-- Descargar Catálogo PDF Button -->
                    <a href="{{ route('catalog.pdf') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 bg-[#0052cc] hover:bg-blue-600 text-white text-xs sm:text-sm font-bold rounded-lg shadow-sm transition">
                        <span>Descargar Catálogo PDF</span>
                    </a>

                    <!-- Cart Button (Trigger Drawer) -->
                    <button type="button" onclick="openCart()" class="relative p-2 bg-blue-800/80 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-1.5 cursor-pointer" title="Ver Carrito">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span id="header-cart-count" class="w-5 h-5 rounded-full bg-rose-500 text-white text-[11px] font-black flex items-center justify-center shadow-xs">0</span>
                    </button>

                    <!-- Admin Link / Login -->
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-bold text-white transition">
                            Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="p-2 text-blue-200 hover:text-white transition" title="Acceso Administrador">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button" onclick="toggleMobileMenu()" class="p-2 lg:hidden text-white hover:bg-white/10 rounded-lg transition" aria-label="Menú">
                        <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Search Bar (under navbar on small screens) -->
            <div class="md:hidden pb-3 pt-1">
                <form action="{{ route('catalog.index') }}" method="GET" class="w-full relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar productos..."
                           class="w-full pl-4 pr-10 py-2 rounded-full bg-white text-slate-800 placeholder-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
                    <button type="submit" class="absolute right-3 text-slate-400 hover:text-blue-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-nav" class="hidden lg:hidden border-t border-blue-900 bg-[#0a235c] px-4 py-4 space-y-2">
            <a href="{{ route('catalog.index') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-sm font-semibold text-white hover:bg-blue-800 transition">
                Inicio
            </a>
            <a href="{{ route('catalog.index') }}#categorias" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-sm font-semibold text-white hover:bg-blue-800 transition">
                Categorías
            </a>
            <a href="{{ route('catalog.index') }}#productos" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-sm font-semibold text-white hover:bg-blue-800 transition">
                Productos
            </a>
            <a href="{{ route('catalog.pdf') }}" class="block px-3 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-500 transition text-center">
                Descargar Catálogo PDF
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-blue-200 hover:bg-blue-800 transition">
                    ⚙️ Panel de Administración
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-5 right-5 z-40 flex flex-col gap-3">
        <!-- Floating Cart Button -->
        <button type="button" onclick="openCart()"
                class="relative flex items-center justify-center w-13 h-13 sm:w-14 sm:h-14 bg-[#0052cc] hover:bg-blue-600 text-white rounded-full shadow-xl hover:scale-105 transition transform active:scale-95 cursor-pointer"
                title="Ver carrito de compras">
            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span id="floating-cart-count" class="absolute -top-1 -right-1 w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-rose-500 text-white text-xs font-black flex items-center justify-center shadow-md border-2 border-white">0</span>
        </button>

        <!-- Floating WhatsApp Button -->
        <a href="https://api.whatsapp.com/send?phone={{ $company->whatsapp_number ?? '51987654321' }}&text={{ urlencode('Hola, deseo consultar sobre los productos del catálogo.') }}" target="_blank" rel="noopener noreferrer"
           class="flex items-center justify-center w-13 h-13 sm:w-14 sm:h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-xl hover:scale-105 transition transform active:scale-95 cursor-pointer animate-bounce-subtle"
           title="WhatsApp Directo">
            <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
            </svg>
        </a>
    </div>

    <!-- ========================================================================= -->
    <!-- SLIDE-OVER CART DRAWER & ORDER CHECKOUT -->
    <!-- ========================================================================= -->
    <div id="cart-backdrop" onclick="closeCart()" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 hidden transition-opacity"></div>

    <div id="cart-drawer" class="fixed inset-y-0 right-0 z-50 w-full sm:w-[480px] bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Drawer Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-[#0c2b74] text-white">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Carrito de Compras</h3>
                    <p class="text-xs text-blue-200" id="drawer-items-count">0 productos seleccionados</p>
                </div>
            </div>

            <button type="button" onclick="closeCart()" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Drawer Body -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4">
            <!-- Empty State -->
            <div id="cart-empty-state" class="py-16 text-center space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 text-base">Tu carrito está vacío</h4>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Explora el catálogo y agrega productos para realizar tu pedido al instante.</p>
                <button type="button" onclick="closeCart()" class="mt-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/25 transition cursor-pointer">
                    Ver Productos
                </button>
            </div>

            <!-- Items List -->
            <div id="cart-items-container" class="space-y-2">
                <!-- Dynamically injected by JS -->
            </div>

            <!-- Checkout Form Section -->
            <div id="cart-checkout-section" class="hidden space-y-4 pt-3 border-t border-slate-200">
                
                <!-- 1. Modalidad de Entrega -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Modalidad de Entrega <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <label class="flex items-start gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 transition">
                            <input type="radio" name="delivery_mode" value="Recojo en Tienda Física (Moyobamba) 🏪" checked class="mt-0.5 text-blue-600">
                            <div>
                                <span class="block font-bold text-slate-800">Recojo en Tienda</span>
                                <span class="block text-[10px] text-emerald-600 font-bold">Moyobamba (Gratis)</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 transition">
                            <input type="radio" name="delivery_mode" value="Delivery Local Moyobamba 🛵" class="mt-0.5 text-blue-600">
                            <div>
                                <span class="block font-bold text-slate-800">Delivery Moyobamba</span>
                                <span class="block text-[10px] text-slate-500">Envío directo</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 transition">
                            <input type="radio" name="delivery_mode" value="Envío San Martín (Tarapoto, Rioja) 🚚" class="mt-0.5 text-blue-600">
                            <div>
                                <span class="block font-bold text-slate-800">Región San Martín</span>
                                <span class="block text-[10px] text-slate-500">Tarapoto, Rioja, etc.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 transition">
                            <input type="radio" name="delivery_mode" value="Envío Nacional a todo el Perú 📦" class="mt-0.5 text-blue-600">
                            <div>
                                <span class="block font-bold text-slate-800">Todo el Perú</span>
                                <span class="block text-[10px] text-slate-500">Olva / Shalom</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 2. Datos del Cliente -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                            Datos del Comprador
                        </label>
                        <div class="flex gap-1 bg-slate-100 p-0.5 rounded-lg text-[11px]">
                            <button type="button" id="type-btn-personal" onclick="setCustomerType('personal')" class="px-2 py-0.5 rounded font-bold transition bg-white text-blue-700 shadow-xs cursor-pointer">
                                Boleta (DNI)
                            </button>
                            <button type="button" id="type-btn-business" onclick="setCustomerType('business')" class="px-2 py-0.5 rounded font-bold transition text-slate-500 hover:text-slate-800 cursor-pointer">
                                Factura (RUC)
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <div class="sm:col-span-2">
                            <label id="lbl-name" class="block text-[11px] font-bold text-slate-600 mb-0.5">Nombre Completo <span class="text-rose-500">*</span></label>
                            <input type="text" id="order-name" placeholder="Ej. Carlos Mendoza" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label id="lbl-doc" class="block text-[11px] font-bold text-slate-600 mb-0.5">DNI (8 dígitos) <span class="text-rose-500">*</span></label>
                            <input type="text" id="order-doc" maxlength="8" placeholder="Ej. 74859612" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Celular / WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" id="order-phone" placeholder="Ej. 987654321" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Dirección / Referencia</label>
                            <input type="text" id="order-address" placeholder="Ej. Jr. Alonso de Alvarado 450, Moyobamba" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- 3. Método de Pago -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                        Método de Pago <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-white cursor-pointer hover:border-purple-400 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50/50 transition">
                            <input type="radio" name="payment_method" value="Yape 📱" checked class="text-purple-600">
                            <span class="font-bold text-slate-800">Yape</span>
                        </label>

                        <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-white cursor-pointer hover:border-cyan-400 has-[:checked]:border-cyan-600 has-[:checked]:bg-cyan-50/50 transition">
                            <input type="radio" name="payment_method" value="Plin 🟣" class="text-cyan-600">
                            <span class="font-bold text-slate-800">Plin</span>
                        </label>

                        <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-white cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 transition">
                            <input type="radio" name="payment_method" value="Transferencia Bancaria (BCP / BBVA) 🏦" class="text-blue-600">
                            <span class="font-bold text-slate-800">Transferencia</span>
                        </label>

                        <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-white cursor-pointer hover:border-emerald-400 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                            <input type="radio" name="payment_method" value="Pago Contra Entrega / En Tienda 💵" class="text-emerald-600">
                            <span class="font-bold text-slate-800">Contraentrega</span>
                        </label>
                    </div>
                </div>

                <!-- 4. Notas -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Indicaciones adicionales (Opcional)</label>
                    <textarea id="order-notes" rows="2" placeholder="Ej. Entregar en la tarde / Consultar color" class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>
            </div>
        </div>

        <!-- Drawer Footer: Totals and WhatsApp Action -->
        <div id="cart-drawer-footer" class="hidden p-4 sm:p-5 border-t border-slate-200 bg-slate-50 space-y-2.5">
            <div class="flex items-center justify-between text-slate-600 text-xs">
                <span>Subtotal:</span>
                <span id="cart-subtotal" class="font-bold text-slate-800">S/ 0.00</span>
            </div>
            <div class="flex items-center justify-between text-slate-900 font-bold text-base pt-1 border-t border-slate-200">
                <span>Total a Pagar:</span>
                <span id="cart-total" class="text-blue-600 font-black text-lg">S/ 0.00</span>
            </div>

            <!-- WhatsApp Checkout Button -->
            <button type="button" id="cart-submit-btn" onclick="submitWhatsAppOrder()" class="w-full py-3 px-4 bg-[#0052cc] hover:bg-blue-700 active:scale-98 text-white rounded-lg font-bold text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                </svg>
                <span id="btn-submit-text">Enviar Pedido por WhatsApp</span>
            </button>

            <div class="text-center pt-1">
                <button type="button" onclick="clearCart()" class="text-[11px] text-slate-400 hover:text-rose-500 transition font-medium cursor-pointer">
                    Vaciar Carrito
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="cart-toast" class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-50 hidden bg-slate-900 text-white px-4 py-2.5 rounded-full shadow-xl flex items-center gap-2 transition-all">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span id="toast-message" class="text-xs font-bold">Producto agregado al carrito</span>
    </div>

    <!-- TecnoStore Official Navy Footer -->
    <footer class="bg-[#081736] text-slate-400 border-t border-blue-950 mt-16 sm:mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                
                <!-- Col 1: TecnoStore Brand -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/>
                            </svg>
                        </div>
                        <span class="text-base font-black text-white tracking-tight">{{ $company->name ?? 'TecnoStore' }}</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Catálogo de Cómputo y Accesorios. Calidad y garantía en tecnología.
                    </p>
                </div>

                <!-- Col 2: Enlaces -->
                <div class="space-y-2.5">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Enlaces</h4>
                    <div class="flex flex-col space-y-1.5 text-xs">
                        <a href="{{ route('catalog.index') }}" class="hover:text-white transition">Inicio</a>
                        <a href="{{ route('catalog.index') }}#categorias" class="hover:text-white transition">Categorías</a>
                        <a href="{{ route('catalog.index') }}#productos" class="hover:text-white transition">Productos</a>
                        <a href="{{ route('catalog.pdf') }}" class="hover:text-white transition">Descargar Catálogo (PDF)</a>
                    </div>
                </div>

                <!-- Col 3: Contáctanos -->
                <div class="space-y-2.5">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Contáctanos</h4>
                    <div class="space-y-1.5 text-xs text-slate-300">
                        <p>WhatsApp: {{ $company->whatsapp ?? '+51 987 654 321' }}</p>
                        <p>Email: {{ $company->email ?? 'contacto@tecnostore.com' }}</p>
                        @if($company->address)
                            <p>Ubicación: {{ $company->address }}</p>
                        @endif
                    </div>
                </div>

                <!-- Col 4: Síguenos -->
                <div class="space-y-2.5">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Síguenos</h4>
                    <div class="flex items-center gap-3 text-slate-300 text-lg">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="hover:text-blue-400 transition" title="Facebook">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hover:text-pink-400 transition" title="Instagram">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://api.whatsapp.com/send?phone={{ $company->whatsapp_number ?? '51987654321' }}" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-400 transition" title="WhatsApp">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/></svg>
                        </a>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-blue-950 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $company->name ?? 'TecnoStore' }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- ========================================================================= -->
    <!-- JAVASCRIPT CART & WHATSAPP ENGINE -->
    <!-- ========================================================================= -->
    <script>
        const COMPANY_WHATSAPP = '{{ $company->whatsapp_number ?? '51987654321' }}';
        const COMPANY_NAME = '{{ $company->name ?? 'TecnoStore' }}';
        let customerType = 'personal';

        function setCustomerType(type) {
            customerType = type;
            const btnPersonal = document.getElementById('type-btn-personal');
            const btnBusiness = document.getElementById('type-btn-business');
            const lblName = document.getElementById('lbl-name');
            const lblDoc = document.getElementById('lbl-doc');
            const inputName = document.getElementById('order-name');
            const inputDoc = document.getElementById('order-doc');

            if (type === 'personal') {
                btnPersonal.className = 'px-2 py-0.5 rounded font-bold transition bg-white text-blue-700 shadow-xs cursor-pointer';
                btnBusiness.className = 'px-2 py-0.5 rounded font-bold transition text-slate-500 hover:text-slate-800 cursor-pointer';
                lblName.innerHTML = 'Nombre Completo <span class="text-rose-500">*</span>';
                lblDoc.innerHTML = 'DNI (8 dígitos) <span class="text-rose-500">*</span>';
                inputName.placeholder = 'Ej. Carlos Mendoza';
                inputDoc.placeholder = 'Ej. 74859612';
                inputDoc.maxLength = 8;
            } else {
                btnBusiness.className = 'px-2 py-0.5 rounded font-bold transition bg-white text-blue-700 shadow-xs cursor-pointer';
                btnPersonal.className = 'px-2 py-0.5 rounded font-bold transition text-slate-500 hover:text-slate-800 cursor-pointer';
                lblName.innerHTML = 'Razón Social / Empresa <span class="text-rose-500">*</span>';
                lblDoc.innerHTML = 'RUC (11 dígitos) <span class="text-rose-500">*</span>';
                inputName.placeholder = 'Ej. Corporación Perú S.A.C.';
                inputDoc.placeholder = 'Ej. 20601234567';
                inputDoc.maxLength = 11;
            }
        }

        // Initialize cart from localStorage
        let cart = JSON.parse(localStorage.getItem('catalog_cart') || '[]');

        function saveCart() {
            localStorage.setItem('catalog_cart', JSON.stringify(cart));
            updateCartUI();
        }

        function addToCart(id, name, price, image = null, url = null, quantity = 1) {
            quantity = parseInt(quantity) || 1;
            const existingIndex = cart.findIndex(item => item.id === id);

            if (existingIndex > -1) {
                cart[existingIndex].quantity += quantity;
                if (image) cart[existingIndex].image = image;
                if (url) cart[existingIndex].url = url;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: parseFloat(price),
                    image: image,
                    url: url,
                    quantity: quantity
                });
            }

            saveCart();
            showToast(`¡"${name}" agregado al carrito!`);
        }

        function updateItemQuantity(id, delta) {
            const index = cart.findIndex(item => item.id === id);
            if (index > -1) {
                cart[index].quantity += delta;
                if (cart[index].quantity <= 0) {
                    cart.splice(index, 1);
                }
                saveCart();
            }
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            saveCart();
        }

        function clearCart() {
            if (confirm('¿Deseas vaciar todos los productos del carrito?')) {
                cart = [];
                saveCart();
            }
        }

        function updateCartUI() {
            const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Update badges
            const headerBadge = document.getElementById('header-cart-count');
            const floatingBadge = document.getElementById('floating-cart-count');
            const drawerCount = document.getElementById('drawer-items-count');

            if (headerBadge) headerBadge.textContent = totalCount;
            if (floatingBadge) floatingBadge.textContent = totalCount;
            if (drawerCount) drawerCount.textContent = `${totalCount} ${totalCount === 1 ? 'producto seleccionado' : 'productos seleccionados'}`;

            // Update Drawer container
            const container = document.getElementById('cart-items-container');
            const emptyState = document.getElementById('cart-empty-state');
            const checkoutSection = document.getElementById('cart-checkout-section');
            const footerSection = document.getElementById('cart-drawer-footer');
            const subtotalEl = document.getElementById('cart-subtotal');
            const totalEl = document.getElementById('cart-total');

            if (cart.length === 0) {
                if (container) container.innerHTML = '';
                if (emptyState) emptyState.classList.remove('hidden');
                if (checkoutSection) checkoutSection.classList.add('hidden');
                if (footerSection) footerSection.classList.add('hidden');
            } else {
                if (emptyState) emptyState.classList.add('hidden');
                if (checkoutSection) checkoutSection.classList.remove('hidden');
                if (footerSection) footerSection.classList.remove('hidden');

                if (container) {
                    container.innerHTML = cart.map(item => `
                        <div class="flex items-center justify-between gap-3 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-12 h-12 rounded-lg bg-white p-1 flex-shrink-0 flex items-center justify-center border border-slate-200 overflow-hidden">
                                    ${item.image ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-contain">` : `
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>`}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-800 text-xs truncate">${item.name}</h4>
                                    <p class="text-xs text-blue-600 font-extrabold">S/ ${item.price.toFixed(2)}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="flex items-center border border-slate-200 rounded-md bg-white">
                                    <button type="button" onclick="updateItemQuantity(${item.id}, -1)" class="px-2 py-0.5 text-slate-600 hover:text-rose-600 font-bold text-xs cursor-pointer">-</button>
                                    <span class="px-1.5 text-xs font-bold text-slate-800">${item.quantity}</span>
                                    <button type="button" onclick="updateItemQuantity(${item.id}, 1)" class="px-2 py-0.5 text-slate-600 hover:text-blue-600 font-bold text-xs cursor-pointer">+</button>
                                </div>
                                <button type="button" onclick="removeFromCart(${item.id})" class="p-1 text-slate-400 hover:text-rose-600 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `).join('');
                }

                if (subtotalEl) subtotalEl.textContent = `S/ ${totalPrice.toFixed(2)}`;
                if (totalEl) totalEl.textContent = `S/ ${totalPrice.toFixed(2)}`;
            }
        }

        function openCart() {
            document.getElementById('cart-backdrop').classList.remove('hidden');
            document.getElementById('cart-drawer').classList.remove('translate-x-full');
            updateCartUI();
        }

        function closeCart() {
            document.getElementById('cart-backdrop').classList.add('hidden');
            document.getElementById('cart-drawer').classList.add('translate-x-full');
        }

        function toggleMobileMenu() {
            const nav = document.getElementById('mobile-nav');
            const openIcon = document.getElementById('menu-icon-open');
            const closeIcon = document.getElementById('menu-icon-close');
            
            nav.classList.toggle('hidden');
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }

        function showToast(msg) {
            const toast = document.getElementById('cart-toast');
            const toastMsg = document.getElementById('toast-message');
            if (toast && toastMsg) {
                toastMsg.textContent = msg;
                toast.classList.remove('hidden');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 2200);
            }
        }

        async function submitWhatsAppOrder() {
            if (cart.length === 0) {
                alert('El carrito está vacío.');
                return;
            }

            const name = document.getElementById('order-name').value.trim();
            if (!name) {
                alert(customerType === 'personal' ? 'Por favor, ingresa tu Nombre Completo.' : 'Por favor, ingresa la Razón Social / Empresa.');
                document.getElementById('order-name').focus();
                return;
            }

            const doc = document.getElementById('order-doc').value.trim();
            if (!doc) {
                alert(customerType === 'personal' ? 'Por favor, ingresa tu DNI.' : 'Por favor, ingresa el número de RUC.');
                document.getElementById('order-doc').focus();
                return;
            }

            const phone = document.getElementById('order-phone').value.trim();
            if (!phone) {
                alert('Por favor, ingresa un número de Teléfono / Celular de contacto.');
                document.getElementById('order-phone').focus();
                return;
            }

            const address = document.getElementById('order-address').value.trim();
            const notes = document.getElementById('order-notes').value.trim();
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const docType = customerType === 'personal' ? 'DNI' : 'RUC';

            const submitBtn = document.getElementById('cart-submit-btn');
            const submitText = document.getElementById('btn-submit-text');
            const originalText = submitText.textContent;

            const deliveryMode = document.querySelector('input[name="delivery_mode"]:checked')?.value || 'Recojo en Tienda Moyobamba 🏪';
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value || 'Yape 📱';

            submitBtn.disabled = true;
            submitText.textContent = 'Generando Pedido...';

            let ticketUrl = '';
            let orderNumber = '';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const payload = {
                    customer_name: name,
                    customer_document: doc,
                    customer_document_type: docType,
                    customer_phone: phone,
                    delivery_mode: deliveryMode,
                    delivery_address: address,
                    payment_method: selectedPayment,
                    notes: notes,
                    items: cart.map(item => ({
                        product_id: item.id,
                        product_name: item.name,
                        quantity: item.quantity,
                        unit_price: item.price
                    }))
                };

                const res = await fetch('{{ route("orders.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    const data = await res.json();
                    ticketUrl = data.ticket_url;
                    orderNumber = data.order_number;
                }
            } catch (e) {
                console.warn('No se pudo guardar pedido en BD, continuando directo a WhatsApp:', e);
            }

            submitBtn.disabled = false;
            submitText.textContent = originalText;

            let msg = `🛒 *NUEVO PEDIDO DE COMPRA - ${COMPANY_NAME}*\n`;
            if (orderNumber) {
                msg += `🔖 *N° Pedido:* ${orderNumber}\n`;
            }
            msg += `━━━━━━━━━━━━━━━━━━━━\n`;
            msg += `👤 *Cliente:* ${name}\n`;
            msg += `📄 *${docType} (${customerType === 'personal' ? 'Boleta' : 'Factura'}):* ${doc}\n`;
            msg += `📱 *Teléfono:* ${phone}\n`;
            msg += `📍 *Modalidad de Entrega:* ${deliveryMode}\n`;
            if (address) msg += `🏠 *Dirección/Referencia:* ${address}\n`;
            msg += `💳 *Método de Pago:* ${selectedPayment}\n`;
            if (ticketUrl) {
                msg += `📄 *Descargar Ticket PDF:* ${ticketUrl}\n`;
            }
            if (notes) msg += `📝 *Nota:* ${notes}\n`;
            msg += `━━━━━━━━━━━━━━━━━━━━\n`;
            msg += `📦 *PRODUCTOS SOLICITADOS:*\n\n`;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                msg += `${index + 1}. *${item.name}*\n`;
                msg += `   ▪ Cantidad: ${item.quantity} und.\n`;
                msg += `   ▪ P. Unitario: S/ ${item.price.toFixed(2)}\n`;
                msg += `   ▪ Subtotal: S/ ${subtotal.toFixed(2)}\n`;
                if (item.image) {
                    msg += `   ▪ 🖼️ Foto: ${item.image}\n`;
                }
                msg += `\n`;
            });

            msg += `━━━━━━━━━━━━━━━━━━━━\n`;
            msg += `💰 *TOTAL A PAGAR: S/ ${totalPrice.toFixed(2)}*\n\n`;
            msg += `Quedo atento a la confirmación de stock y los datos para realizar el pago por *${selectedPayment}*. ¡Muchas gracias!`;

            const whatsappUrl = `https://api.whatsapp.com/send?phone=${COMPANY_WHATSAPP}&text=${encodeURIComponent(msg)}`;
            window.location.href = whatsappUrl;
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateCartUI();
        });
    </script>
    @stack('scripts')
</body>
</html>
