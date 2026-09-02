<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased text-slate-800 bg-slate-50 selection:bg-blue-600 selection:text-white pb-20 sm:pb-0 relative">

    <!-- Top Contact & Location Announcement Strip -->
    @if(isset($company))
        <div class="bg-slate-900 text-slate-300 text-[11px] sm:text-xs py-1.5 sm:py-2 px-3 sm:px-4 border-b border-slate-800">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-2 overflow-x-auto no-scrollbar whitespace-nowrap">
                <div class="flex items-center gap-3 sm:gap-4 flex-shrink-0">
                    @if($company->address || $company->city_province)
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $company->address ?? $company->city_province }}</span>
                        </div>
                    @endif

                    @if($company->phone)
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $company->phone }}</span>
                        </div>
                    @endif
                </div>

                @if($company->whatsapp)
                    <div class="flex items-center gap-2 flex-shrink-0 pl-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 font-bold transition">
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
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3">
                <!-- Logo & Business Name -->
                <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 sm:gap-3 flex-shrink-0 group">
                    @if(isset($company) && $company->logo && file_exists(public_path('storage/' . $company->logo)))
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-9 sm:h-12 max-w-[130px] sm:max-w-[180px] object-contain group-hover:scale-105 transition transform">
                    @else
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg sm:text-2xl shadow-md shadow-blue-500/25 flex-shrink-0">
                            {{ strtoupper(substr($company->name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight block leading-tight truncate max-w-[140px] sm:max-w-none">
                                {{ $company->name ?? 'TECHSTORE' }}
                            </span>
                            <span class="text-[10px] sm:text-[11px] font-semibold text-slate-400 uppercase tracking-widest block">
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

                <!-- Action Buttons: Cart, PDF, Admin -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Cart Button (Header) -->
                    <button type="button" onclick="openCart()" class="relative p-2 sm:px-3.5 sm:py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl font-bold text-xs sm:text-sm flex items-center gap-2 transition">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="hidden sm:inline">Carrito</span>
                        <span id="header-cart-count" class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">0</span>
                    </button>

                    <a href="{{ route('catalog.pdf') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2.5 bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>PDF</span>
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

                    <!-- Mobile Menu Button -->
                    <button type="button" onclick="toggleMobileMenu()" class="p-2 md:hidden text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition" aria-label="Menú">
                        <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer / Dropdown -->
        <div id="mobile-nav" class="hidden md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md px-4 py-4 space-y-2 shadow-lg">
            <a href="{{ route('catalog.index') }}" onclick="toggleMobileMenu()" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-800 hover:bg-blue-50 hover:text-blue-600 transition">
                🏠 Inicio
            </a>
            <a href="{{ route('catalog.index') }}#categorias" onclick="toggleMobileMenu()" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-800 hover:bg-blue-50 hover:text-blue-600 transition">
                📂 Categorías
            </a>
            <a href="{{ route('catalog.index') }}#productos" onclick="toggleMobileMenu()" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-800 hover:bg-blue-50 hover:text-blue-600 transition">
                💻 Todos los Productos
            </a>
            <button type="button" onclick="toggleMobileMenu(); openCart();" class="w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 transition flex items-center justify-between">
                <span>🛒 Ver Carrito de Cotización</span>
                <span id="mobile-nav-cart-count" class="px-2 py-0.5 rounded-full bg-blue-600 text-white text-xs font-bold">0</span>
            </button>
            <a href="{{ route('catalog.pdf') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition">
                📄 Descargar Catálogo PDF
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                    ⚙️ Panel de Administración
                </a>
            @else
                <a href="{{ route('login') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                    🔐 Iniciar Sesión (Admin)
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Floating Buttons (Mobile / Desktop) -->
    <div class="fixed bottom-5 right-5 z-40 flex flex-col gap-3">
        <!-- Floating Cart Button -->
        <button type="button" onclick="openCart()"
                class="relative flex items-center justify-center w-13 h-13 p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-2xl shadow-blue-600/50 hover:scale-105 transition transform active:scale-95"
                title="Ver carrito de cotización">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span id="floating-cart-count" class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-rose-500 text-white text-[11px] font-black flex items-center justify-center shadow">0</span>
        </button>

        <!-- Floating WhatsApp Direct Button -->
        @if(isset($company) && $company->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank"
               class="flex items-center justify-center w-13 h-13 p-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-2xl shadow-emerald-500/50 hover:scale-105 transition transform active:scale-95"
               title="WhatsApp Directo">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                </svg>
            </a>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- SLIDE-OVER CART DRAWER & CHECKOUT MODAL -->
    <!-- ========================================================================= -->
    <div id="cart-backdrop" onclick="closeCart()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden transition-opacity"></div>

    <div id="cart-drawer" class="fixed inset-y-0 right-0 z-50 w-full sm:w-[460px] bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Drawer Header -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Carrito de Cotización</h3>
                    <p class="text-xs text-slate-500" id="drawer-items-count">0 productos seleccionados</p>
                </div>
            </div>

            <button type="button" onclick="closeCart()" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-200/50 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Drawer Content: Items List & Form -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">
            <!-- Empty State -->
            <div id="cart-empty-state" class="py-16 text-center space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Tu carrito está vacío</h4>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Agrega productos del catálogo para armar tu cotización o pedido rápidamente.</p>
                <button type="button" onclick="closeCart()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-sm">
                    Explorar Productos
                </button>
            </div>

            <!-- Items List -->
            <div id="cart-items-container" class="space-y-3 divide-y divide-slate-100">
                <!-- Dynamically injected by JS -->
            </div>

            <!-- Customer Details & Payment Method Form (Visible when cart has items) -->
            <div id="cart-checkout-section" class="hidden space-y-4 pt-4 border-t border-slate-200">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Datos para la Cotización</h4>

                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Nombre Completo <span class="text-rose-500">*</span></label>
                        <input type="text" id="order-name" placeholder="Ej. Carlos Mendoza" class="w-full px-3.5 py-2 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Dirección o Ciudad de Entrega</label>
                        <input type="text" id="order-address" placeholder="Ej. Lima, Av. Arequipa 1234 o Envío a Provincia" class="w-full px-3.5 py-2 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Payment Method Selector -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-2">Método de Pago Preferido <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Yape -->
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:border-purple-400 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50/50 transition">
                                <input type="radio" name="payment_method" value="Yape 📱" checked class="text-purple-600 focus:ring-purple-500">
                                <span class="text-xs font-bold text-slate-800">Yape</span>
                            </label>

                            <!-- Plin -->
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:border-cyan-400 has-[:checked]:border-cyan-600 has-[:checked]:bg-cyan-50/50 transition">
                                <input type="radio" name="payment_method" value="Plin 🟣" class="text-cyan-600 focus:ring-cyan-500">
                                <span class="text-xs font-bold text-slate-800">Plin</span>
                            </label>

                            <!-- Transferencia Bancaria -->
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 transition">
                                <input type="radio" name="payment_method" value="Transferencia Bancaria (BCP / BBVA / Interbank) 🏦" class="text-blue-600 focus:ring-blue-500">
                                <span class="text-xs font-bold text-slate-800">Transferencia</span>
                            </label>

                            <!-- Efectivo / Contraentrega -->
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-400 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                                <input type="radio" name="payment_method" value="Efectivo / Pago contra entrega 💵" class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-xs font-bold text-slate-800">Contraentrega</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Notas adicionales o consulta</label>
                        <textarea id="order-notes" rows="2" placeholder="¿Deseas boleta/factura o alguna especificación?" class="w-full px-3.5 py-2 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Drawer Footer: Totals and WhatsApp Action -->
        <div id="cart-drawer-footer" class="hidden p-4 sm:p-6 border-t border-slate-200 bg-slate-50 space-y-3">
            <div class="flex items-center justify-between text-slate-600 text-xs">
                <span>Subtotal estimado:</span>
                <span id="cart-subtotal" class="font-bold text-slate-800">S/ 0.00</span>
            </div>
            <div class="flex items-center justify-between text-slate-900 font-extrabold text-base sm:text-lg pt-1 border-t border-slate-200/80">
                <span>Total a Pagar:</span>
                <span id="cart-total" class="text-blue-600 font-black">S/ 0.00</span>
            </div>

            <!-- WhatsApp Checkout Button -->
            <button type="button" onclick="submitWhatsAppOrder()" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-600/25 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                </svg>
                <span>Pedir / Cotizar por WhatsApp</span>
            </button>

            <div class="flex items-center justify-center gap-4 pt-1">
                <button type="button" onclick="clearCart()" class="text-[11px] text-slate-400 hover:text-rose-500 transition font-medium">
                    Vaciar Carrito
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification for Added Products -->
    <div id="cart-toast" class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-50 hidden bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 transition-all duration-300">
        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <span id="toast-message" class="text-xs font-semibold">Producto agregado al carrito</span>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-16 sm:mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand -->
                <div class="space-y-4 sm:col-span-2 md:col-span-1">
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

                <!-- Col 3: Contacto & Métodos de Pago -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Contacto & Pagos</h3>
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
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                                    </svg>
                                    <span>WhatsApp: {{ $company->whatsapp }}</span>
                                </a>
                            </p>
                        @endif

                        <div class="pt-2 flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded bg-purple-900/60 text-purple-300 text-[10px] font-bold border border-purple-700/50">Yape</span>
                            <span class="px-2 py-0.5 rounded bg-cyan-900/60 text-cyan-300 text-[10px] font-bold border border-cyan-700/50">Plin</span>
                            <span class="px-2 py-0.5 rounded bg-blue-900/60 text-blue-300 text-[10px] font-bold border border-blue-700/50">BCP / BBVA</span>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Enlaces Rápidos -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Accesos Rápidos</h3>
                    <div class="flex flex-col space-y-2 text-xs">
                        <a href="{{ route('catalog.index') }}" class="hover:text-white transition">Inicio del Catálogo</a>
                        <a href="{{ route('catalog.index') }}#categorias" class="hover:text-white transition">Lista de Categorías</a>
                        <a href="javascript:void(0)" onclick="openCart()" class="hover:text-white transition">Ver Mi Carrito</a>
                        <a href="{{ route('catalog.pdf') }}" class="hover:text-white transition">Descargar Catálogo (PDF)</a>
                        <a href="{{ route('login') }}" class="hover:text-white transition">Panel de Administración</a>
                    </div>
                </div>
            </div>

            <div class="mt-10 sm:mt-12 pt-6 sm:pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $company->name ?? 'TechStore' }}. Todos los derechos reservados.</p>
                <p class="mt-1">Precios expresados en Soles Peruanos (S/) sujetos a cambios sin previo aviso.</p>
            </div>
        </div>
    </footer>

    <!-- ========================================================================= -->
    <!-- JAVASCRIPT CART & WHATSAPP ENGINE -->
    <!-- ========================================================================= -->
    <script>
        const COMPANY_WHATSAPP = '{{ isset($company) && $company->whatsapp ? preg_replace('/[^0-9]/', '', $company->whatsapp) : '51987654321' }}';
        const COMPANY_NAME = '{{ $company->name ?? 'TechStore' }}';

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
            const mobileBadge = document.getElementById('mobile-nav-cart-count');
            const drawerCount = document.getElementById('drawer-items-count');

            if (headerBadge) headerBadge.textContent = totalCount;
            if (floatingBadge) floatingBadge.textContent = totalCount;
            if (mobileBadge) mobileBadge.textContent = totalCount;
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
                        <div class="pt-3 first:pt-0 flex items-center justify-between gap-3 bg-slate-50/50 p-2.5 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-14 h-14 rounded-xl bg-white p-1 flex-shrink-0 flex items-center justify-center border border-slate-200 shadow-xs overflow-hidden">
                                    ${item.image ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-contain">` : `
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>`}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-800 text-xs truncate">${item.name}</h4>
                                    <p class="text-xs text-blue-600 font-extrabold">S/ ${item.price.toFixed(2)} c/u</p>
                                    ${item.image ? `<a href="${item.image}" target="_blank" class="text-[10px] text-slate-400 hover:text-blue-600 underline flex items-center gap-1 mt-0.5"><span>Ver foto grande</span></a>` : ''}
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="flex items-center border border-slate-200 rounded-lg bg-white shadow-xs">
                                    <button type="button" onclick="updateItemQuantity(${item.id}, -1)" class="px-2 py-1 text-slate-600 hover:text-rose-600 font-bold text-xs">-</button>
                                    <span class="px-2 text-xs font-bold text-slate-800">${item.quantity}</span>
                                    <button type="button" onclick="updateItemQuantity(${item.id}, 1)" class="px-2 py-1 text-slate-600 hover:text-blue-600 font-bold text-xs">+</button>
                                </div>
                                <button type="button" onclick="removeFromCart(${item.id})" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Eliminar">
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
                }, 2500);
            }
        }

        function submitWhatsAppOrder() {
            if (cart.length === 0) {
                alert('El carrito está vacío.');
                return;
            }

            const name = document.getElementById('order-name').value.trim();
            if (!name) {
                alert('Por favor, ingresa tu Nombre Completo para generar el pedido.');
                document.getElementById('order-name').focus();
                return;
            }

            const address = document.getElementById('order-address').value.trim();
            const notes = document.getElementById('order-notes').value.trim();
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value || 'Yape / Transferencia';
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Construct formatted WhatsApp message with image links
            let msg = `🛒 *NUEVO PEDIDO / COTIZACIÓN - ${COMPANY_NAME}*\n`;
            msg += `━━━━━━━━━━━━━━━━━━━━\n`;
            msg += `👤 *Cliente:* ${name}\n`;
            if (address) msg += `📍 *Dirección/Destino:* ${address}\n`;
            msg += `💳 *Método de Pago:* ${selectedPayment}\n`;
            if (notes) msg += `📝 *Nota:* ${notes}\n`;
            msg += `━━━━━━━━━━━━━━━━━━━━\n`;
            msg += `📦 *PRODUCTOS SOLICITADOS:*\n\n`;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                msg += `${index + 1}. *${item.name}*\n`;
                msg += `   ▪ Cantidad: ${item.quantity} und.\n`;
                msg += `   ▪ Precio Unitario: S/ ${item.price.toFixed(2)}\n`;
                msg += `   ▪ Subtotal: S/ ${subtotal.toFixed(2)}\n`;
                if (item.image) {
                    msg += `   ▪ 🖼️ Foto: ${item.image}\n`;
                } else if (item.url) {
                    msg += `   ▪ 🔗 Enlace: ${item.url}\n`;
                }
                msg += `\n`;
            });

            msg += `━━━━━━━━━━━━━━━━━━━━\n`;
            msg += `💰 *TOTAL ESTIMADO: S/ ${totalPrice.toFixed(2)}*\n\n`;
            msg += `Quedo atento a la confirmación de stock y los datos para realizar el pago. ¡Muchas gracias!`;

            const whatsappUrl = `https://wa.me/${COMPANY_WHATSAPP}?text=${encodeURIComponent(msg)}`;
            window.open(whatsappUrl, '_blank');
        }

        // Run on load
        document.addEventListener('DOMContentLoaded', updateCartUI);
    </script>
</body>
</html>
