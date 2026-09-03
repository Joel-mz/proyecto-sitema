@extends('layouts.app')

@section('title', $category->name . ' - Catálogo Virtual')
@section('meta_description', 'Consulta todos los productos disponibles en la categoría ' . $category->name . ' con precios actualizados.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">

    <!-- Category Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Inicio</a>
        <span>/</span>
        <span class="text-slate-800">{{ $category->name }}</span>
    </div>

    <!-- Category Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $category->name }}</h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ $category->description ?: 'Explora todos los modelos y accesorios disponibles en esta categoría.' }}
            </p>
        </div>
        <div>
            <span class="inline-block px-3.5 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200">
                {{ $products->total() }} productos
            </span>
        </div>
    </div>

    <!-- Products Grid (Exact TecnoStore Card Grid) -->
    <section class="space-y-4">
        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($products as $prod)
                    <div class="group bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-lg hover:border-blue-300 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <!-- Product Image (White background box) -->
                            <div class="w-full aspect-square bg-white flex items-center justify-center p-3 sm:p-4 overflow-hidden border-b border-slate-100">
                                <a href="{{ route('catalog.show', $prod->slug) }}" class="w-full h-full flex items-center justify-center">
                                    @if($prod->image_url)
                                        <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}"
                                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </a>
                            </div>

                            <!-- Product Information -->
                            <div class="p-3 sm:p-3.5 space-y-1">
                                <h3 class="font-bold text-slate-900 text-xs sm:text-sm leading-tight group-hover:text-blue-600 transition line-clamp-2">
                                    <a href="{{ route('catalog.show', $prod->slug) }}">
                                        {{ $prod->name }}
                                    </a>
                                </h3>
                                <p class="text-[11px] text-slate-400 truncate">
                                    {{ $category->name }}
                                </p>
                            </div>
                        </div>

                        <!-- Price & Action Button -->
                        <div class="p-3 sm:p-3.5 pt-0 space-y-2">
                            <div class="font-black text-blue-600 text-sm sm:text-base">
                                S/ {{ number_format($prod->price, 2) }}
                            </div>

                            <button type="button"
                                    onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->price }}, '{{ $prod->image_url ?? '' }}', '{{ route('catalog.show', $prod->slug) }}', 1)"
                                    class="w-full py-1.5 px-2 bg-blue-50 hover:bg-[#0052cc] text-blue-700 hover:text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Agregar</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="pt-6 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center max-w-md mx-auto space-y-3">
                <p class="text-sm font-bold text-slate-700">No hay productos en esta categoría.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold">
                    Ver todos los productos
                </a>
            </div>
        @endif
    </section>

</div>
@endsection
