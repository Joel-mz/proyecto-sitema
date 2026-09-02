@extends('layouts.app')

@section('title', $product->name . ' - Catálogo Virtual')
@section('meta_description', Str::limit(strip_tags($product->description), 150) ?: 'Detalle de producto: ' . $product->name . ' en TechStore.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Inicio</a>
        <span>/</span>
        <a href="{{ route('catalog.category', $product->category->slug) }}" class="hover:text-blue-600 transition">
            {{ $product->category->name }}
        </a>
        <span>/</span>
        <span class="text-slate-800">{{ $product->name }}</span>
    </div>

    <!-- Main Product Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10 lg:p-12 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
            <!-- Left Column: Large Image Box -->
            <div class="w-full aspect-square bg-slate-50 rounded-2xl border border-slate-200/80 overflow-hidden flex items-center justify-center p-6 sm:p-10 shadow-inner">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-contain hover:scale-105 transition-transform duration-500">
                @else
                    <div class="flex flex-col items-center justify-center text-slate-400 gap-3">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-medium">Imagen no disponible</span>
                    </div>
                @endif
            </div>

            <!-- Right Column: Product Info & Price -->
            <div class="space-y-6">
                <div>
                    <a href="{{ route('catalog.category', $product->category->slug) }}"
                       class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 hover:bg-blue-100 transition mb-3">
                        {{ $product->category->name }}
                    </a>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price Box -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Precio de Catálogo</span>
                        <span class="text-3xl sm:text-4xl font-black text-blue-600">
                            S/ {{ number_format($product->price, 2) }}
                        </span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold self-start sm:self-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Disponible
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Descripción del Producto</h3>
                    <div class="text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                        {{ $product->description ?: 'No se especificó una descripción detallada para este producto.' }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Volver al Catálogo</span>
                    </a>

                    <a href="{{ route('catalog.pdf') }}" class="px-6 py-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs sm:text-sm font-bold transition flex items-center gap-2 border border-rose-200">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Descargar Catálogo Completo (PDF)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <section class="space-y-6 pt-6">
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Otros productos en {{ $product->category->name }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col">
                        <div class="relative w-full aspect-square bg-slate-100 overflow-hidden flex items-center justify-center p-4">
                            @if($rel->image)
                                <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                            @else
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-2">
                            <h4 class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-blue-600 transition">
                                <a href="{{ route('catalog.show', $rel->slug) }}">{{ $rel->name }}</a>
                            </h4>
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-blue-600 text-sm">S/ {{ number_format($rel->price, 2) }}</span>
                                <a href="{{ route('catalog.show', $rel->slug) }}" class="text-xs text-slate-400 hover:text-blue-600 font-semibold">&rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
