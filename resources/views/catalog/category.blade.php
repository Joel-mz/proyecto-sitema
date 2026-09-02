@extends('layouts.app')

@section('title', $category->name . ' - Catálogo Virtual')
@section('meta_description', 'Consulta todos los productos disponibles en la categoría ' . $category->name . ' con precios actualizados.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Category Header Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Inicio</a>
        <span>/</span>
        <span class="text-slate-800">{{ $category->name }}</span>
    </div>

    <!-- Category Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-8 sm:p-12 text-white flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl">
        <div class="space-y-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-500/20 text-blue-400 border border-blue-400/30 inline-block">
                Categoría
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $category->name }}</h1>
            <p class="text-slate-300 text-sm max-w-xl leading-relaxed">
                {{ $category->description ?: 'Explora todos los modelos y productos disponibles en esta categoría.' }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <span class="px-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-md text-white font-bold text-sm border border-white/15">
                {{ $products->total() }} productos encontrados
            </span>
        </div>
    </div>

    <!-- Products Grid -->
    <section class="space-y-6">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $prod)
                    <div class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 flex flex-col">
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
                        </div>

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

            @if($products->hasPages())
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-xl mx-auto space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Sin productos en esta categoría</h3>
                <p class="text-xs text-slate-500">
                    Aún no se han agregado productos para la categoría {{ $category->name }}.
                </p>
                <a href="{{ route('catalog.index') }}" class="inline-block px-5 py-2.5 bg-blue-600 text-white font-semibold text-xs rounded-xl shadow-sm hover:bg-blue-700 transition">
                    Ver todas las categorías
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
