<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Catálogo Virtual de Productos') - {{ $company->name ?? 'Catálogo' }}</title>
    <meta name="description" content="@yield('meta_description', $company->description ?? 'Descubre nuestro catálogo virtual con los mejores productos y precios actualizados.')">
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
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        }
                    },
                    animation: {
                        'bounce-subtle': 'bounceSubtle 2s infinite ease-in-out',
                    },
                    keyframes: {
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-4px)' },
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

    <!-- Top Announcement Strip: Moyobamba, San Martín & Envíos -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 text-slate-300 text-[11px] sm:text-xs py-1.5 sm:py-2 px-3 sm:px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2 overflow-x-auto no-scrollbar whitespace-nowrap">
            <div class="flex items-center gap-3 sm:gap-5 flex-shrink-0">
                <div class="flex items-center gap-1.5 text-slate-300">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-emerald-950 text-emerald-400 border border-emerald-800/60 font-bold text-[10px]">
                        📍 Moyobamba - San Martín
                    </span>
                    <span class="text-slate-400 hidden sm:inline">{{ $company->address ?? 'Atención en Tienda y Envíos a todo el Perú' }}</span>
                </div>

                <div class="hidden md:flex items-center gap-2 text-slate-400">
                    <span class="text-blue-400">🛵</span>
                    <span>Envíos locales en Moyobamba y agencias a nivel regional y nacional</span>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0 pl-2">
                <a href="https://api.whatsapp.com/send?phone={{ $company->whatsapp_number ?? '51987654321' }}&text={{ urlencode('Hola, deseo realizar una consulta o pedido.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-bold transition">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>WhatsApp: {{ $company->whatsapp ?? '+51 987 654 321' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3">
                <!-- Logo & Business Name -->
                <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 sm:gap-3 flex-shrink-0 group">
                    @if(isset($company) && $company->logo && file_exists(public_path('storage/' . $company->logo)))
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-9 sm:h-12 max-w-[130px] sm:max-w-[180px] object-contain group-hover:scale-105 transition transform">
                    @else
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-cyan-500 flex items-center justify-center text-white font-black text-lg sm:text-2xl shadow-lg shadow-blue-500/25 flex-shrink-0 group-hover:rotate-3 transition transform">
                            {{ strtoupper(substr($company->name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-base sm:text-xl font-black text-slate-900 tracking-tight block leading-tight truncate max-w-[140px] sm:max-w-none">
                                {{ $company->name ?? 'TECHSTORE' }}
                            </span>
                            <span class="text-[10px] sm:text-[11px] font-bold text-blue-600 uppercase tracking-widest block">
                                Catálogo Virtual
                            </span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-1.5">
                    <a href="{{ route('catalog.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('catalog.index') && !request('categoria') ? 'bg-blue-50 text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Inicio
                    </a>
                    <a href="{{ route('catalog.index') }}#categorias" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                        Categorías
                    </a>
                    <a href="{{ route('catalog.index') }}#productos" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                        Productos
                    </a>
                </nav>

                <!-- Action Buttons: Cart, PDF, Admin -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Cart Button (Header) with Glow Effect -->
                    <button type="button" onclick="openCart()" class="relative p-2 sm:px-4 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-xs sm:text-sm flex items-center gap-2 shadow-md shadow-blue-600/25 transition transform active:scale-95 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="hidden sm:inline">Mi Pedido / Carrito</span>
                        <span id="header-cart-count" class="w-5 h-5 rounded-full bg-white text-blue-700 text-[11px] font-black flex items-center justify-center shadow-xs">0</span>
                    </button>

                    <a href="{{ route('catalog.pdf') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs sm:text-sm font-bold rounded-xl border border-rose-200 shadow-xs transition">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>PDF</span>
                    </a>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
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

        <!-- Mobile Dropdown -->
        <div id="mobile-nav" class="hidden md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md px-4 py-4 space-y-2 shadow-lg">
            <a href="{{ route('catalog.index') }}" onclick="toggleMobileMenu()" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-600 transition">
                🏠 Inicio
            </a>
            <a href="{{ route('catalog.index') }}#categorias" onclick="toggleMobileMenu()" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-600 transition">
                📂 Categorías
            </a>
            <a href="{{ route('catalog.index') }}#productos" onclick="toggleMobileMenu()" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-600 transition">
                💻 Todos los Productos
            </a>
            <button type="button" onclick="toggleMobileMenu(); openCart();" class="w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 transition flex items-center justify-between">
                <span>🛒 Ver Mi Pedido / Cotización</span>
                <span id="mobile-nav-cart-count" class="px-2 py-0.5 rounded-full bg-blue-600 text-white text-xs font-black">0</span>
            </button>
            <a href="{{ route('catalog.pdf') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition">
                📄 Descargar Catálogo PDF
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                    ⚙️ Panel de Administración
                </a>
            @else
                <a href="{{ route('login') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition">
                    🔐 Iniciar Sesión (Admin)
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-5 right-5 z-40 flex flex-col gap-3">
        <!-- Floating Cart Button with Counter -->
        <button type="button" onclick="openCart()"
                class="relative flex items-center justify-center w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-full shadow-2xl shadow-blue-600/50 hover:scale-110 transition transform active:scale-95 cursor-pointer"
                title="Ver carrito de cotización y pedidos">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span id="floating-cart-count" class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-rose-500 text-white text-xs font-black flex items-center justify-center shadow-md border-2 border-white">0</span>
        </button>

        <!-- Floating WhatsApp Direct Button -->
        <a href="https://api.whatsapp.com/send?phone={{ $company->whatsapp_number ?? '51987654321' }}&text={{ urlencode('Hola, deseo consultar sobre los productos de su catálogo virtual.') }}" target="_blank" rel="noopener noreferrer"
           class="flex items-center justify-center w-14 h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-2xl shadow-emerald-500/50 hover:scale-110 transition transform active:scale-95 cursor-pointer animate-bounce-subtle"
           title="WhatsApp Directo">
            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
            </svg>
        </a>
    </div>

    <!-- ========================================================================= -->
    <!-- SLIDE-OVER CART DRAWER & CHECKOUT MODAL (SMART ORDER VS QUOTE) -->
    <!-- ========================================================================= -->
    <div id="cart-backdrop" onclick="closeCart()" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 hidden transition-opacity"></div>

    <div id="cart-drawer" class="fixed inset-y-0 right-0 z-50 w-full sm:w-[500px] bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Drawer Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-900 to-indigo-950 text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black shadow-md shadow-blue-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-white text-base">Carrito & Cotizador</h3>
                    <p class="text-xs text-blue-200" id="drawer-items-count">0 productos seleccionados</p>
                </div>
            </div>

            <button type="button" onclick="closeCart()" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Drawer Body -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-5">
            <!-- Empty State -->
            <div id="cart-empty-state" class="py-16 text-center space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h4 class="font-extrabold text-slate-800 text-base">Tu carrito está vacío</h4>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Explora nuestro catálogo y agrega productos para cotizar o pedir al instante.</p>
                <button type="button" onclick="closeCart()" class="mt-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/25 transition cursor-pointer">
                    Ver Catálogo
                </button>
            </div>

            <!-- Items List -->
            <div id="cart-items-container" class="space-y-2.5">
                <!-- Dynamically injected by JS -->
            </div>

            <!-- Checkout Configuration Section -->
            <div id="cart-checkout-section" class="hidden space-y-4 pt-4 border-t border-slate-200">
                
                <!-- 1. INTENCIÓN: ¿Comprar Ahora o Solicitar Cotización? -->
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        Selecciona el tipo de solicitud <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1 rounded-xl">
                        <button type="button" id="action-btn-order" onclick="setOrderAction('order')" class="py-2.5 px-3 rounded-lg text-xs font-black transition bg-white text-emerald-700 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>🛍️ Pedido / Compra</span>
                        </button>
                        <button type="button" id="action-btn-quote" onclick="setOrderAction('quote')" class="py-2.5 px-3 rounded-lg text-xs font-bold transition text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>📄 Solo Cotización</span>
                        </button>
                    </div>
                </div>

                <!-- Info message when in Quotation Mode -->
                <div id="quote-info-box" class="hidden p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <span>ℹ️ Modo Cotización Activado</span>
                    </div>
                    <p class="text-[11px] text-blue-700 leading-relaxed">
                        En modo cotización <strong>no se requiere método de pago ni modalidad de entrega</strong>. Recibirás la proforma formal de precios y disponibilidad por WhatsApp.
                    </p>
                </div>

                <!-- 2. TIPO DE ENTREGA / ENVÍO (Visible SOLO en modo Compra / Pedido) -->
                <div id="delivery-mode-container">
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        Modalidad de Entrega <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <!-- Recojo en Tienda Moyobamba -->
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/60 transition">
                            <input type="radio" name="delivery_mode" value="Recojo en Tienda Física (Moyobamba) 🏪" checked class="mt-0.5 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="block text-xs font-extrabold text-slate-800">Recojo en Tienda</span>
                                <span class="block text-[10px] text-emerald-600 font-bold">Local en Moyobamba (Gratis)</span>
                            </div>
                        </label>

                        <!-- Delivery Local Moyobamba -->
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/60 transition">
                            <input type="radio" name="delivery_mode" value="Delivery Local a Domicilio (Moyobamba) 🛵" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="block text-xs font-extrabold text-slate-800">Delivery Moyobamba</span>
                                <span class="block text-[10px] text-slate-500">Envío directo a tu puerta</span>
                            </div>
                        </label>

                        <!-- Envío Regional San Martín -->
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/60 transition">
                            <input type="radio" name="delivery_mode" value="Envío Regional San Martín (Tarapoto, Rioja, etc.) 🚚" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="block text-xs font-extrabold text-slate-800">Región San Martín</span>
                                <span class="block text-[10px] text-slate-500">Tarapoto, Rioja, Juanjuí, etc.</span>
                            </div>
                        </label>

                        <!-- Envío Nacional -->
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/60 transition">
                            <input type="radio" name="delivery_mode" value="Envío Nacional a todo el Perú (Olva / Agencia) 📦" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="block text-xs font-extrabold text-slate-800">Todo el Perú</span>
                                <span class="block text-[10px] text-slate-500">Olva Courier / Shalom / Marvisur</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. TIPO DE COMPROBANTE / CLIENTE -->
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">
                            Datos del Solicitante / Cliente
                        </label>
                        <div class="flex gap-1 bg-slate-100 p-0.5 rounded-lg text-[11px]">
                            <button type="button" id="type-btn-personal" onclick="setCustomerType('personal')" class="px-2.5 py-1 rounded-md font-bold transition bg-white text-blue-700 shadow-xs cursor-pointer">
                                Persona (DNI)
                            </button>
                            <button type="button" id="type-btn-business" onclick="setCustomerType('business')" class="px-2.5 py-1 rounded-md font-bold transition text-slate-500 hover:text-slate-800 cursor-pointer">
                                Empresa (RUC)
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="sm:col-span-2">
                            <label id="lbl-name" class="block text-[11px] font-bold text-slate-600 mb-1">
                                Nombre Completo <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="order-name" placeholder="Ej. Carlos Mendoza" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label id="lbl-doc" class="block text-[11px] font-bold text-slate-600 mb-1">
                                DNI (8 dígitos) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="order-doc" maxlength="8" placeholder="Ej. 74859612" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">
                                Teléfono / Celular <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" id="order-phone" placeholder="Ej. 987654321" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        </div>

                        <div class="sm:col-span-2">
                            <label id="order-address-label" class="block text-[11px] font-bold text-slate-600 mb-1">Dirección / Referencia de Entrega</label>
                            <input type="text" id="order-address" placeholder="Ej. Jr. Alonso de Alvarado 450, Moyobamba" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        </div>
                    </div>
                </div>

                <!-- 4. MÉTODO DE PAGO PREFERIDO (Visible SOLO en modo Compra / Pedido) -->
                <div id="payment-method-container" class="space-y-2 pt-2 border-t border-slate-100">
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">
                        Método de Pago Preferido <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Yape -->
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-purple-400 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50/60 transition">
                            <input type="radio" name="payment_method" value="Yape 📱" checked class="text-purple-600 focus:ring-purple-500">
                            <span class="text-xs font-bold text-slate-800">Yape</span>
                        </label>

                        <!-- Plin -->
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-cyan-400 has-[:checked]:border-cyan-600 has-[:checked]:bg-cyan-50/60 transition">
                            <input type="radio" name="payment_method" value="Plin 🟣" class="text-cyan-600 focus:ring-cyan-500">
                            <span class="text-xs font-bold text-slate-800">Plin</span>
                        </label>

                        <!-- Transferencia -->
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/60 transition">
                            <input type="radio" name="payment_method" value="Transferencia Bancaria (BCP / BBVA / Interbank / BN) 🏦" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-800">Transferencia</span>
                        </label>

                        <!-- Contraentrega -->
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-emerald-400 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/60 transition">
                            <input type="radio" name="payment_method" value="Pago Contra Entrega / En Tienda 💵" class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-800">Contraentrega</span>
                        </label>
                    </div>
                </div>

                <!-- 5. NOTAS ADICIONALES -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Notas o Consulta adicional (Opcional)</label>
                    <textarea id="order-notes" rows="2" placeholder="Ej. ¿Tienen descuento por volumen o alguna especificación?" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white"></textarea>
                </div>
            </div>
        </div>

        <!-- Drawer Footer: Totals and WhatsApp Action -->
        <div id="cart-drawer-footer" class="hidden p-4 sm:p-5 border-t border-slate-200 bg-slate-50 space-y-2.5">
            <div class="flex items-center justify-between text-slate-600 text-xs">
                <span id="cart-subtotal-label">Subtotal productos:</span>
                <span id="cart-subtotal" class="font-bold text-slate-800">S/ 0.00</span>
            </div>
            <div class="flex items-center justify-between text-slate-900 font-extrabold text-base sm:text-lg pt-1 border-t border-slate-200/80">
                <span id="cart-total-label">Total a Pagar:</span>
                <span id="cart-total" class="text-blue-600 font-black">S/ 0.00</span>
            </div>

            <!-- WhatsApp Checkout Button -->
            <button type="button" id="cart-submit-btn" onclick="submitWhatsAppOrder()" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white rounded-xl font-black text-sm shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                </svg>
                <span id="btn-submit-text">Enviar Pedido de Compra por WhatsApp</span>
            </button>

            <div class="flex items-center justify-center gap-4 pt-1">
                <button type="button" onclick="clearCart()" class="text-[11px] text-slate-400 hover:text-rose-500 transition font-medium cursor-pointer">
                    Vaciar Carrito
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification for Added Products -->
    <div id="cart-toast" class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-50 hidden bg-slate-900/95 backdrop-blur-md text-white px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 transition-all duration-300 border border-slate-700">
        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <span id="toast-message" class="text-xs font-bold">Producto agregado al carrito</span>
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
                            <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg">
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
                        <p class="text-slate-300 flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $company->address ?? 'Moyobamba, San Martín, Perú' }}</span>
                        </p>
                        <p class="text-slate-400 pl-6">
                            <strong>Distrito:</strong> {{ $company->city_province ?? 'Moyobamba' }}<br>
                            <strong>Región:</strong> {{ $company->region ?? 'San Martín' }}
                        </p>
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

                        <p class="flex items-center gap-2">
                            <a href="https://api.whatsapp.com/send?phone={{ $company->whatsapp_number ?? '51987654321' }}&text={{ urlencode('Hola, deseo realizar una consulta sobre el catálogo de productos.') }}" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1.5 transition">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                                </svg>
                                <span>WhatsApp: {{ $company->whatsapp ?? '+51 987 654 321' }}</span>
                            </a>
                        </p>

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
                        <a href="javascript:void(0)" onclick="openCart()" class="hover:text-white transition">Ver Mi Pedido</a>
                        <a href="{{ route('catalog.pdf') }}" class="hover:text-white transition">Descargar Catálogo (PDF)</a>
                        <a href="{{ route('login') }}" class="hover:text-white transition">Panel de Administración</a>
                    </div>
                </div>
            </div>

            <div class="mt-10 sm:mt-12 pt-6 sm:pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $company->name ?? 'TechStore' }}. Moyobamba, San Martín, Perú.</p>
                <p class="mt-1">Precios en Soles (S/) sujetos a stock y confirmación.</p>
            </div>
        </div>
    </footer>

    <!-- ========================================================================= -->
    <!-- JAVASCRIPT CART & ADVANCED WHATSAPP ORDER ENGINE -->
    <!-- ========================================================================= -->
    <script>
        const COMPANY_WHATSAPP = '{{ $company->whatsapp_number ?? '51987654321' }}';
        const COMPANY_NAME = '{{ $company->name ?? 'TechStore' }}';
        let customerType = 'personal';
        let orderAction = 'order'; // 'order' or 'quote'

        function setOrderAction(action) {
            orderAction = action;
            const btnOrder = document.getElementById('action-btn-order');
            const btnQuote = document.getElementById('action-btn-quote');
            const btnSubmit = document.getElementById('cart-submit-btn');
            const btnSubmitText = document.getElementById('btn-submit-text');
            const totalLabel = document.getElementById('cart-total-label');
            const subtotalLabel = document.getElementById('cart-subtotal-label');
            const deliveryContainer = document.getElementById('delivery-mode-container');
            const paymentContainer = document.getElementById('payment-method-container');
            const quoteInfoBox = document.getElementById('quote-info-box');
            const addressLabel = document.getElementById('order-address-label');
            const addressInput = document.getElementById('order-address');

            if (action === 'order') {
                // Compra / Pedido Mode
                btnOrder.className = 'py-2.5 px-3 rounded-lg text-xs font-black transition bg-white text-emerald-700 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer';
                btnQuote.className = 'py-2.5 px-3 rounded-lg text-xs font-bold transition text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1.5 cursor-pointer';
                
                deliveryContainer.classList.remove('hidden');
                paymentContainer.classList.remove('hidden');
                quoteInfoBox.classList.add('hidden');

                addressLabel.textContent = 'Dirección / Referencia de Entrega';
                addressInput.placeholder = 'Ej. Jr. Alonso de Alvarado 450, Moyobamba';

                totalLabel.textContent = 'Total a Pagar:';
                subtotalLabel.textContent = 'Subtotal productos:';

                btnSubmit.className = 'w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white rounded-xl font-black text-sm shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2 cursor-pointer';
                btnSubmitText.textContent = 'Enviar Pedido de Compra por WhatsApp';
            } else {
                // Cotización Mode
                btnQuote.className = 'py-2.5 px-3 rounded-lg text-xs font-black transition bg-white text-blue-700 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer';
                btnOrder.className = 'py-2.5 px-3 rounded-lg text-xs font-bold transition text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1.5 cursor-pointer';
                
                // HIDE delivery and payment method
                deliveryContainer.classList.add('hidden');
                paymentContainer.classList.add('hidden');
                quoteInfoBox.classList.remove('hidden');

                addressLabel.textContent = 'Ciudad / Ubicación de referencia (Opcional)';
                addressInput.placeholder = 'Ej. Moyobamba, Tarapoto, Rioja, etc.';

                totalLabel.textContent = 'Total Estimado / Proforma:';
                subtotalLabel.textContent = 'Subtotal estimado:';

                btnSubmit.className = 'w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white rounded-xl font-black text-sm shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2 cursor-pointer';
                btnSubmitText.textContent = 'Solicitar Cotización por WhatsApp';
            }
        }

        function setCustomerType(type) {
            customerType = type;
            const btnPersonal = document.getElementById('type-btn-personal');
            const btnBusiness = document.getElementById('type-btn-business');
            const lblName = document.getElementById('lbl-name');
            const lblDoc = document.getElementById('lbl-doc');
            const inputName = document.getElementById('order-name');
            const inputDoc = document.getElementById('order-doc');

            if (type === 'personal') {
                btnPersonal.className = 'px-2.5 py-1 rounded-md font-bold transition bg-white text-blue-700 shadow-xs cursor-pointer';
                btnBusiness.className = 'px-2.5 py-1 rounded-md font-bold transition text-slate-500 hover:text-slate-800 cursor-pointer';
                lblName.innerHTML = 'Nombre Completo <span class="text-rose-500">*</span>';
                lblDoc.innerHTML = 'DNI (8 dígitos) <span class="text-rose-500">*</span>';
                inputName.placeholder = 'Ej. Carlos Mendoza';
                inputDoc.placeholder = 'Ej. 74859612';
                inputDoc.maxLength = 8;
            } else {
                btnBusiness.className = 'px-2.5 py-1 rounded-md font-bold transition bg-white text-blue-700 shadow-xs cursor-pointer';
                btnPersonal.className = 'px-2.5 py-1 rounded-md font-bold transition text-slate-500 hover:text-slate-800 cursor-pointer';
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
                        <div class="pt-3 first:pt-0 flex items-center justify-between gap-3 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-200 transition">
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
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="flex items-center border border-slate-200 rounded-lg bg-white shadow-xs">
                                    <button type="button" onclick="updateItemQuantity(${item.id}, -1)" class="px-2 py-1 text-slate-600 hover:text-rose-600 font-black text-xs cursor-pointer">-</button>
                                    <span class="px-2 text-xs font-black text-slate-800">${item.quantity}</span>
                                    <button type="button" onclick="updateItemQuantity(${item.id}, 1)" class="px-2 py-1 text-slate-600 hover:text-blue-600 font-black text-xs cursor-pointer">+</button>
                                </div>
                                <button type="button" onclick="removeFromCart(${item.id})" class="p-1.5 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Eliminar">
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

            let msg = '';

            if (orderAction === 'quote') {
                // STRICT QUOTATION FORMAT (NO DELIVERY, NO PAYMENT METHOD)
                msg += `📄 *SOLICITUD DE COTIZACIÓN - ${COMPANY_NAME}*\n`;
                msg += `━━━━━━━━━━━━━━━━━━━━\n`;
                msg += `👤 *Solicitante:* ${name}\n`;
                msg += `📄 *${docType}:* ${doc}\n`;
                msg += `📱 *Teléfono:* ${phone}\n`;
                if (address) msg += `📍 *Ciudad/Ubicación:* ${address}\n`;
                if (notes) msg += `📝 *Consulta:* ${notes}\n`;
                msg += `━━━━━━━━━━━━━━━━━━━━\n`;
                msg += `📦 *PRODUCTOS A COTIZAR:*\n\n`;

                cart.forEach((item, index) => {
                    const subtotal = item.price * item.quantity;
                    msg += `${index + 1}. *${item.name}*\n`;
                    msg += `   ▪ Cantidad: ${item.quantity} und.\n`;
                    msg += `   ▪ Precio Ref.: S/ ${item.price.toFixed(2)}\n`;
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
                msg += `Hola, solicito una cotización formal y disponibilidad de stock para los productos listados. ¡Muchas gracias!`;

                const whatsappUrl = `https://api.whatsapp.com/send?phone=${COMPANY_WHATSAPP}&text=${encodeURIComponent(msg)}`;
                window.location.href = whatsappUrl;

            } else {
                // ORDER / PURCHASE FORMAT (INCLUDES DELIVERY, PAYMENT & PDF TICKET LINK)
                const deliveryMode = document.querySelector('input[name="delivery_mode"]:checked')?.value || 'Recojo en Tienda Moyobamba 🏪';
                const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value || 'Yape 📱';

                submitBtn.disabled = true;
                submitText.textContent = 'Generando Ticket y Pedido...';

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
                    console.warn('No se pudo generar ticket en BD, continuando directo a WhatsApp:', e);
                }

                submitBtn.disabled = false;
                submitText.textContent = originalText;

                msg += `🛒 *NUEVO PEDIDO DE COMPRA - ${COMPANY_NAME}*\n`;
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
                    } else if (item.url) {
                        msg += `   ▪ 🔗 Enlace: ${item.url}\n`;
                    }
                    msg += `\n`;
                });

                msg += `━━━━━━━━━━━━━━━━━━━━\n`;
                msg += `💰 *TOTAL A PAGAR: S/ ${totalPrice.toFixed(2)}*\n\n`;
                msg += `Quedo atento a la confirmación de stock y los datos para realizar el pago por *${selectedPayment}*. ¡Muchas gracias!`;

                const whatsappUrl = `https://api.whatsapp.com/send?phone=${COMPANY_WHATSAPP}&text=${encodeURIComponent(msg)}`;
                window.location.href = whatsappUrl;
            }
        }

        // Run on load
        document.addEventListener('DOMContentLoaded', () => {
            updateCartUI();
        });
    </script>
</body>
</html>
