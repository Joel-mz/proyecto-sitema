@extends('layouts.app')

@section('title', 'Catálogo de Productos de Cómputo y Accesorios')
@section('meta_description', 'Explora nuestro catálogo virtual completo con laptops, componentes, periféricos y accesorios en Moyobamba y San Martín.')

@section('content')
<div class="space-y-6 sm:space-y-12">

    <!-- Modern Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white py-10 sm:py-16 lg:py-20 border-b border-slate-800">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:20px_20px]"></div>
        
        <!-- Glow circles background -->
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 sm:space-y-6">
            <!-- Pill Highlights -->
            <div class="inline-flex flex-wrap items-center justify-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/30 text-blue-300 text-[11px] sm:text-xs font-bold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Moyobamba & San Martín
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 text-[11px] sm:text-xs font-bold tracking-wide">
                    💳 Yape / Plin / Transferencia
                </span>
            </div>

            <h1 class="text-2xl sm:text-5xl lg:text-6xl font-black tracking-tight max-w-4xl mx-auto leading-tight">
                Catálogo Virtual de <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-indigo-300">Cómputo & Tecnología</span>
            </h1>
            
            <p class="text-slate-300 text-xs sm:text-base lg:text-lg max-w-2xl mx-auto px-2 font-medium leading-relaxed">
                Arma tu pedido o solicita tu cotización formal al instante con atención directa por WhatsApp y envíos rápidos.
            </p>

            <!-- Search Bar in Hero -->
            <div class="max-w-2xl mx-auto pt-2 sm:pt-4">
                <form action="{{ route('catalog.index') }}" method="GET" class="relative flex items-center">
                    @if(request('categoria'))
                        <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar laptop, periférico, monitor, componente..."
                        class="w-full pl-10 sm:pl-12 pr-24 sm:pr-32 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-slate-900/90 transition text-xs sm:text-sm font-medium shadow-lg">
                    <button type="submit"
                        class="absolute right-1.5 sm:right-2 px-4 py-2 sm:px-6 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl shadow-md transition transform active:scale-95 cursor-pointer">
                        Buscar
                    </button>
                </form>
            </div>

            <!-- Quick Features Bar -->
            <div class="pt-4 sm:pt-6 grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 max-w-4xl mx-auto text-left">
                <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs flex items-center gap-2.5">
                    <span class="text-xl">🏪</span>
                    <div>
                        <p class="text-xs font-bold text-white leading-tight">Tienda Física</p>
                        <p class="text-[10px] text-slate-400">Recojo en Moyobamba</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs flex items-center gap-2.5">
                    <span class="text-xl">🛵</span>
                    <div>
                        <p class="text-xs font-bold text-white leading-tight">Delivery Local</p>
                        <p class="text-[10px] text-slate-400">Todo Moyobamba</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs flex items-center gap-2.5">
                    <span class="text-xl">🚚</span>
                    <div>
                        <p class="text-xs font-bold text-white leading-tight">Envíos San Martín</p>
                        <p class="text-[10px] text-slate-400">Tarapoto, Rioja y más</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs flex items-center gap-2.5">
                    <span class="text-xl">📱</span>
                    <div>
                        <p class="text-xs font-bold text-white leading-tight">Yape & Plin</p>
                        <p class="text-[10px] text-slate-400">Transferencias seguras</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6 sm:space-y-10">

        <!-- Categorías Horizontal Touch Scroll Navigation -->
        <section id="categorias" class="space-y-2 sm:space-y-4">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-base sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-1.5 sm:gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    <span>Categorías del Catálogo</span>
                </h2>
                @if(request('categoria') || request('search'))
                    <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Ver Todos</span>
                    </a>
                @endif
            </div>

            <!-- Categories Horizontal Scroll Pills -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1 -mx-3 px-3 sm:mx-0 sm:px-0 sm:flex-wrap">
                <a href="{{ route('catalog.index', array_filter(['search' => request('search')])) }}"
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition border whitespace-nowrap flex-shrink-0 cursor-pointer {{ !request('categoria') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-transparent shadow-md shadow-blue-500/25' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
                    Todos los Productos
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', array_filter(['categoria' => $cat->slug, 'search' => request('search')])) }}"
                       class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition border flex items-center gap-2 whitespace-nowrap flex-shrink-0 cursor-pointer {{ request('categoria') == $cat->slug ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-transparent shadow-md shadow-blue-500/25' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="text-[10px] sm:text-[11px] px-2 py-0.5 rounded-full font-black {{ request('categoria') == $cat->slug ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                            {{ $cat->products_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Product Grid Section -->
        <section id="productos" class="space-y-4 sm:space-y-6 pt-1">
            <!-- Section Title & Results Count -->
            <div class="flex items-center justify-between gap-2 border-b border-slate-200/80 pb-3 sm:pb-4 px-1">
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight">
                        @if(request('categoria'))
                            {{ $categories->firstWhere('slug', request('categoria'))->name ?? 'Productos' }}
                        @else
                            Lista de Productos Disponibles
                        @endif
                    </h2>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5 font-medium">
                        Mostrando {{ $products->total() }} producto(s) en catálogo
                    </p>
                </div>
            </div>

            <!-- Products Grid: 2 columns on mobile, 3-4 columns on larger screens -->
            @if($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($products as $prod)
                        <div class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl hover:border-blue-300 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <!-- Image Box -->
                                <div class="relative w-full aspect-square bg-slate-50 overflow-hidden flex items-center justify-center p-3 sm:p-5">
                                    <a href="{{ route('catalog.show', $prod->slug) }}" class="w-full h-full flex items-center justify-center">
                                        @if($prod->image)
                                            <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}"
                                                 class="w-full h-full object-contain group-hover:scale-108 transition-transform duration-500">
                                        @else
                                            <div class="flex flex-col items-center justify-center text-slate-400 gap-1">
                                                <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-[10px] sm:text-xs font-medium">Sin imagen</span>
                                            </div>
                                        @endif
                                    </a>

                                    <!-- Category Badge Overlay -->
                                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 pointer-events-none">
                                        <span class="px-2 py-0.5 rounded-lg text-[9px] sm:text-[10px] font-extrabold bg-white/95 backdrop-blur-md text-slate-700 shadow-xs border border-white/60 truncate max-w-[110px] block">
                                            {{ $prod->category->name ?? 'General' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content Info -->
                                <div class="p-3 sm:p-5 space-y-1.5">
                                    <h3 class="font-extrabold text-slate-900 text-xs sm:text-base leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                        <a href="{{ route('catalog.show', $prod->slug) }}">
                                            {{ $prod->name }}
                                        </a>
                                    </h3>
                                    <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed hidden sm:block">
                                        {{ $prod->description ?: 'Producto de cómputo y accesorios de alta calidad.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Footer Price & Action -->
                            <div class="p-3 sm:p-5 pt-0 sm:pt-0">
                                <div class="pt-2.5 sm:pt-3 border-t border-slate-100 flex flex-col gap-2">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] sm:text-[10px] uppercase tracking-wider font-bold text-slate-400 block leading-none">Precio</span>
                                            <span class="text-sm sm:text-lg font-black text-blue-600 block">
                                                S/ {{ number_format($prod->price, 2) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('catalog.show', $prod->slug) }}" class="text-[11px] font-bold text-slate-500 hover:text-blue-600 flex items-center gap-0.5">
                                            <span>Detalles</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>

                                    <button type="button"
                                            onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->price }}, '{{ $prod->image ? asset('storage/' . $prod->image) : '' }}', '{{ route('catalog.show', $prod->slug) }}', 1)"
                                            class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-95 text-white text-[11px] sm:text-xs font-extrabold transition flex items-center justify-center gap-1.5 shadow-md shadow-blue-600/20 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>+ Agregar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pt-6 sm:pt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-16 text-center max-w-xl mx-auto space-y-4 shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No encontramos productos</h3>
                    <p class="text-xs text-slate-500">
                        Intenta con otra palabra clave o selecciona otra categoría del catálogo.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="inline-block px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition cursor-pointer">
                        Ver Todos los Productos
                    </a>
                </div>
            @endif
        </section>

    </div>
</div>
@endsection
