@extends('layouts.app')

@section('title', $category->name . ' - Catálogo Virtual')
@section('meta_description', 'Consulta todos los productos disponibles en la categoría ' . $category->name . ' con precios actualizados.')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6 sm:space-y-10">

    <!-- Category Header Breadcrumb -->
    <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-semibold text-slate-500 overflow-x-auto no-scrollbar whitespace-nowrap">
        <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Inicio</a>
        <span>/</span>
        <span class="text-slate-800">{{ $category->name }}</span>
    </div>

    <!-- Category Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl sm:rounded-3xl p-6 sm:p-12 text-white flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6 shadow-xl">
        <div class="space-y-2 sm:space-y-3">
            <span class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-blue-500/20 text-blue-400 border border-blue-400/30 inline-block">
                Categoría
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">{{ $category->name }}</h1>
            <p class="text-slate-300 text-xs sm:text-sm max-w-xl leading-relaxed">
                {{ $category->description ?: 'Explora todos los modelos y productos disponibles en esta categoría.' }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <span class="inline-block px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl bg-white/10 backdrop-blur-md text-white font-bold text-xs sm:text-sm border border-white/15">
                {{ $products->total() }} productos encontrados
            </span>
        </div>
    </div>

    <!-- Products Grid -->
    <section class="space-y-4 sm:space-y-6">
        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($products as $prod)
                    <div class="group bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl hover:border-blue-200 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="relative w-full aspect-square bg-slate-100 overflow-hidden flex items-center justify-center p-2.5 sm:p-4">
                                <a href="{{ route('catalog.show', $prod->slug) }}" class="w-full h-full flex items-center justify-center">
                                    @if($prod->image)
                                        <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}"
                                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="flex flex-col items-center justify-center text-slate-400 gap-1">
                                            <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-[10px] sm:text-xs font-medium">Sin imagen</span>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <div class="p-3 sm:p-5 space-y-1 sm:space-y-1.5">
                                <h3 class="font-bold text-slate-900 text-xs sm:text-base leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                    <a href="{{ route('catalog.show', $prod->slug) }}">
                                        {{ $prod->name }}
                                    </a>
                                </h3>
                                <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed hidden sm:block">
                                    {{ $prod->description ?: 'Producto de alta calidad.' }}
                                </p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-5 pt-0 sm:pt-0">
                            <div class="pt-2 sm:pt-3 border-t border-slate-100 flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider font-bold text-slate-400 block leading-none">Precio</span>
                                        <span class="text-sm sm:text-lg font-extrabold text-blue-600 block">
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
                                        onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->price }}, '{{ $prod->image ? asset('storage/' . $prod->image) : '' }}', 1)"
                                        class="w-full py-1.5 sm:py-2 px-3 rounded-lg sm:rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-[11px] sm:text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Agregar al Carrito</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="pt-4 sm:pt-6">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-8 sm:p-12 text-center max-w-xl mx-auto space-y-3 sm:space-y-4">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-800">Sin productos en esta categoría</h3>
                <p class="text-xs text-slate-500">
                    Aún no se han agregado productos para la categoría {{ $category->name }}.
                </p>
                <a href="{{ route('catalog.index') }}" class="inline-block px-4 py-2 sm:px-5 sm:py-2.5 bg-blue-600 text-white font-semibold text-xs rounded-xl shadow-sm hover:bg-blue-700 transition">
                    Ver todas las categorías
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
