@extends('layouts.app')

@section('title', 'Catálogo de Productos de Cómputo y Accesorios')
@section('meta_description', 'Explora nuestro catálogo virtual completo con laptops, componentes, periféricos y accesorios con precios actualizados.')

@section('content')
<div class="space-y-12">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-slate-900 text-white py-16 lg:py-24 border-b border-slate-800">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-400 text-xs font-semibold tracking-wide uppercase">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                Precios e Inventario Actualizados
            </div>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight">
                Catálogo Virtual de <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">Cómputo & Accesorios</span>
            </h1>
            <p class="text-slate-400 text-base sm:text-lg max-w-2xl mx-auto">
                Explora nuestra selección de componentes, laptops, periféricos y equipos de última generación. Consulta precios y especificaciones al instante.
            </p>

            <!-- Search Bar in Hero -->
            <div class="max-w-2xl mx-auto pt-4">
                <form action="{{ route('catalog.index') }}" method="GET" class="relative flex items-center">
                    @if(request('categoria'))
                        <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por producto, marca, modelo o especificación..."
                        class="w-full pl-12 pr-32 py-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-slate-900 transition text-sm">
                    <button type="submit"
                        class="absolute right-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md transition">
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Categorías Pills Navigation -->
        <section id="categorias" class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    <span>Categorías</span>
                </h2>
                @if(request('categoria') || request('search'))
                    <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Limpiar filtros</span>
                    </a>
                @endif
            </div>

            <!-- Categories Horizontal Scroll / Flex Wrap -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('catalog.index', array_filter(['search' => request('search')])) }}"
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition border {{ !request('categoria') ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-600/20' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                    Todos los productos
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', array_filter(['categoria' => $cat->slug, 'search' => request('search')])) }}"
                       class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition border flex items-center gap-1.5 {{ request('categoria') == $cat->slug ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-600/20' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="text-[11px] px-1.5 py-0.5 rounded-full {{ request('categoria') == $cat->slug ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500' }}">
                            {{ $cat->products_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Product Grid Section -->
        <section id="productos" class="space-y-6 pt-2">
            <!-- Section Title & Results Count -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/80 pb-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                        @if(request('categoria'))
                            {{ $categories->firstWhere('slug', request('categoria'))->name ?? 'Productos' }}
                        @else
                            Catálogo de Productos
                        @endif
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Mostrando {{ $products->count() }} de {{ $products->total() }} productos disponibles
                    </p>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $prod)
                        <div class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 flex flex-col">
                            <!-- Image Box -->
                            <div class="relative w-full aspect-square bg-slate-100 overflow-hidden flex items-center justify-center p-4">
                                @if($prod->image)
                                    <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}"
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex flex-col items-center justify-center text-slate-400 gap-2">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium">Sin imagen</span>
                                    </div>
                                @endif

                                <!-- Category Badge Overlay -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white/90 backdrop-blur-md text-slate-700 shadow-xs border border-white/60">
                                        {{ $prod->category->name ?? 'General' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content Info -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-1.5">
                                    <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-blue-600 transition">
                                        <a href="{{ route('catalog.show', $prod->slug) }}">
                                            {{ $prod->name }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $prod->description ?: 'Producto de cómputo y accesorios de alta calidad.' }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <div>
                                        <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 block">Precio</span>
                                        <span class="text-lg font-extrabold text-blue-600">
                                            S/ {{ number_format($prod->price, 2) }}
                                        </span>
                                    </div>

                                    <a href="{{ route('catalog.show', $prod->slug) }}"
                                       class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 text-xs font-bold transition flex items-center gap-1">
                                        <span>Detalles</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pt-6">
                        {{ $products->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-xl mx-auto space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No encontramos productos</h3>
                    <p class="text-xs text-slate-500">
                        No hay productos que coincidan con los criterios de búsqueda o la categoría seleccionada.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="inline-block px-5 py-2.5 bg-blue-600 text-white font-semibold text-xs rounded-xl shadow-sm hover:bg-blue-700 transition">
                        Ver todos los productos
                    </a>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
