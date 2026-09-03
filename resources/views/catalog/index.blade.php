@extends('layouts.app')

@section('title', 'Catálogo de Productos de Cómputo y Accesorios')
@section('meta_description', 'Los mejores productos con la mejor calidad y al mejor precio en laptops, componentes, accesorios e impresoras.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-10 sm:space-y-14">

    <!-- 1. HERO SLIDER BANNER -->
    @php
        $hasBanners = isset($banners) && $banners->isNotEmpty();
    @endphp
    <section id="hero-banner-section" class="relative bg-white rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden p-6 sm:p-10 lg:p-14">
        <!-- Slides Wrapper -->
        <div id="hero-slider-track" class="relative min-h-[300px] sm:min-h-[330px] flex items-center">
            
            @if($hasBanners)
                <!-- Admin Configured Banners -->
                @foreach($banners as $index => $banner)
                    <div class="hero-slide {{ $index === 0 ? '' : 'hidden' }} w-full grid grid-cols-1 md:grid-cols-2 gap-8 items-center transition-all duration-500">
                        <!-- Left Info Column -->
                        <div class="space-y-4 sm:space-y-6 text-left">
                            @if(!empty($banner->badge))
                                <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-xs font-black rounded-full border border-blue-100 uppercase tracking-wider">
                                    {{ $banner->badge }}
                                </span>
                            @endif

                            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                                {{ $banner->title ?: ($company->name ?? 'Catálogo Virtual') }}
                            </h2>
                            
                            @if(!empty($banner->subtitle))
                                <p class="text-slate-500 text-sm sm:text-base max-w-md font-medium leading-relaxed">
                                    {{ $banner->subtitle }}
                                </p>
                            @endif

                            <div class="pt-2">
                                <a href="{{ $banner->button_link ?: '#productos' }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 bg-[#0052cc] hover:bg-blue-600 text-white font-bold text-sm sm:text-base rounded-xl shadow-md transition transform active:scale-95">
                                    <span>{{ $banner->button_text ?: 'Ver Productos' }}</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Right Banner Image -->
                        <div class="relative flex items-center justify-center p-4">
                            @php
                                $bannerImg = !empty($banner->image) ? (str_starts_with($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image)) : null;
                            @endphp
                            @if($bannerImg)
                                <img src="{{ $bannerImg }}" alt="{{ $banner->title }}"
                                     class="max-h-72 sm:max-h-84 w-auto max-w-full object-contain drop-shadow-xl hover:scale-105 transition-transform duration-500 rounded-2xl">
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default Hero Slide (No extra product slides) -->
                <div class="hero-slide w-full grid grid-cols-1 md:grid-cols-2 gap-8 items-center transition-all duration-500">
                    <!-- Left Info Column -->
                    <div class="space-y-4 sm:space-y-6 text-left">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                            @if(!empty($company?->hero_title))
                                {{ $company->hero_title }}
                            @else
                                Catálogo Virtual<br>
                                <span class="text-blue-600">de Cómputo y Accesorios</span>
                            @endif
                        </h1>
                        
                        <p class="text-slate-500 text-sm sm:text-base max-w-md font-medium leading-relaxed">
                            {{ $company?->hero_subtitle ?: 'Los mejores productos con la mejor calidad y al mejor precio.' }}
                        </p>

                        <div class="pt-2">
                            <a href="#productos" class="inline-flex items-center gap-2.5 px-6 py-3.5 bg-[#0052cc] hover:bg-blue-600 text-white font-bold text-sm sm:text-base rounded-xl shadow-md transition transform active:scale-95">
                                <span>Ver Productos</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right Hero Image -->
                    <div class="relative flex items-center justify-center p-4">
                        @if(!empty($company?->hero_image))
                            <img src="{{ str_starts_with($company->hero_image, 'http') ? $company->hero_image : asset('storage/' . $company->hero_image) }}" alt="{{ $company->hero_title ?? 'Catálogo' }}"
                                 class="max-h-72 sm:max-h-84 w-auto max-w-full object-contain drop-shadow-xl hover:scale-105 transition-transform duration-500 rounded-2xl">
                        @else
                            <!-- High Quality Laptop SVG Mockup -->
                            <div class="w-full max-w-md aspect-4/3 bg-gradient-to-tr from-slate-900 to-blue-950 rounded-2xl p-4 shadow-2xl flex flex-col justify-between border-4 border-slate-700">
                                <div class="w-full flex-1 bg-gradient-to-br from-blue-600 to-indigo-900 rounded-xl flex items-center justify-center text-white relative overflow-hidden">
                                    <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] opacity-30 [background-size:12px_12px]"></div>
                                    <div class="text-center z-10 space-y-1">
                                        <span class="text-3xl">💻</span>
                                        <p class="text-xs font-black tracking-widest text-blue-200">{{ $company?->hero_badge ?: 'TECLADOS • LAPTOPS • HARDWARE' }}</p>
                                    </div>
                                </div>
                                <div class="w-32 h-2 bg-slate-600 rounded-full mx-auto mt-3"></div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Slider Arrow Navigation Buttons (Left & Right) -->
        <button id="slider-btn-prev" type="button" class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/95 border border-slate-200 shadow-md items-center justify-center text-slate-600 hover:text-blue-600 hover:border-blue-300 transition z-10" title="Anterior">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button id="slider-btn-next" type="button" class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/95 border border-slate-200 shadow-md items-center justify-center text-slate-600 hover:text-blue-600 hover:border-blue-300 transition z-10" title="Siguiente">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Slider Pagination Dots -->
        <div id="slider-dots-container" class="flex items-center justify-center gap-2 pt-6 sm:pt-4"></div>
    </section>

    <!-- 2. CATEGORÍAS (Exact TecnoStore Mockup Style) -->
    <section id="categorias" class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Categorías</h2>
            @if(request('categoria') || request('search'))
                <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                    Ver todas las categorías
                </a>
            @endif
        </div>

        <!-- Horizontal Scroll on Mobile / Multi-column Grid on Desktop -->
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-9 gap-3 sm:gap-4">
            
            <!-- Category Item Definition Helper -->
            @php
                $categoryIcons = [
                    'computacion' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    'accesorios' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v4m0 0a4 4 0 00-4 4v4a4 4 0 008 0v-4a4 4 0 00-4-4z"/></svg>',
                    'componentes' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>',
                    'impresoras' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>',
                    'redes' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>',
                    'gaming' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/></svg>',
                    'almacenamiento' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>',
                    'cables' => '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                ];
            @endphp

            @foreach($categories as $cat)
                @php
                    $isCatActive = (isset($selectedCategory) && ($selectedCategory->id == $cat->id || $selectedCategory->parent_id == $cat->id)) || request('categoria') == $cat->slug;
                    $slug = Str::slug($cat->name);
                    $iconSvg = $categoryIcons[$slug] ?? null;
                    if (!$iconSvg) {
                        foreach($categoryIcons as $key => $icon) {
                            if (str_contains($slug, $key)) {
                                $iconSvg = $icon;
                                break;
                            }
                        }
                    }
                    if (!$iconSvg) {
                        $iconSvg = '<svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>';
                    }
                @endphp

                <a href="{{ route('catalog.index', array_filter(['categoria' => $cat->slug, 'search' => request('search')])) }}"
                   class="group flex flex-col items-center justify-center p-3 sm:p-4 rounded-2xl bg-white border transition-all text-center hover:shadow-md hover:-translate-y-1 {{ $isCatActive ? 'border-blue-600 ring-2 ring-blue-500/20 shadow-sm' : 'border-slate-200/90 hover:border-blue-300' }}">
                    
                    <!-- Icon Box -->
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center transition {{ $isCatActive ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-blue-50 group-hover:text-blue-600' }}">
                        {!! $iconSvg !!}
                    </div>

                    <span class="mt-2.5 text-[11px] sm:text-xs font-bold text-slate-800 group-hover:text-blue-600 transition truncate max-w-[90px] block">
                        {{ $cat->name }}
                    </span>
                    <span class="text-[10px] text-slate-400">
                        {{ $cat->total_active_products_count }} prod.
                    </span>
                </a>
            @endforeach
        </div>

        <!-- Subcategories Filter Bar (Inside parent category) -->
        @if(isset($selectedCategory) && ($selectedCategory->subcategories->isNotEmpty() || $selectedCategory->parent))
            @php
                $mainCategory = $selectedCategory->parent ?: $selectedCategory;
            @endphp
            <div class="bg-white rounded-2xl border border-blue-200/80 shadow-xs p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">📁</span>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800">
                            Subcategorías de <span class="text-blue-600 font-extrabold">{{ $mainCategory->name }}</span>
                        </h3>
                    </div>
                    <span class="text-[11px] text-slate-400 hidden sm:inline">Selecciona una subcategoría para filtrar</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('catalog.index', array_filter(['categoria' => $mainCategory->slug, 'search' => request('search')])) }}"
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('categoria') == $mainCategory->slug ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        <span>Ver Todo en {{ $mainCategory->name }}</span>
                        <span class="text-[10px] {{ request('categoria') == $mainCategory->slug ? 'text-blue-200' : 'text-slate-400' }}">({{ $mainCategory->total_active_products_count }})</span>
                    </a>
                    @foreach($mainCategory->subcategories as $sub)
                        <a href="{{ route('catalog.index', array_filter(['categoria' => $sub->slug, 'search' => request('search')])) }}"
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('categoria') == $sub->slug ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-700' }}">
                            <span>↳ {{ $sub->name }}</span>
                            <span class="text-[10px] {{ request('categoria') == $sub->slug ? 'text-blue-200' : 'text-slate-400' }}">({{ $sub->products_count }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- 3. PRODUCTOS DESTACADOS (Exact TecnoStore Card Grid) -->
    <section id="productos" class="space-y-4 sm:space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    @if(isset($selectedCategory))
                        {{ $selectedCategory->name }}
                        @if($selectedCategory->parent)
                            <span class="text-sm font-normal text-slate-400 block sm:inline">en {{ $selectedCategory->parent->name }}</span>
                        @endif
                    @else
                        Productos Destacados
                    @endif
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Mostrando {{ $products->total() }} producto(s) en catálogo
                </p>
            </div>

            @if(request('categoria') || request('search'))
                <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                    Ver todos
                </a>
            @endif
        </div>

        @if($products->count() > 0)
            <!-- Products Grid: 2 cols on mobile, 3-6 cols on desktop -->
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
                                    {{ $prod->category->name ?? 'Accesorios' }}
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

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="pt-6 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center max-w-md mx-auto space-y-3">
                <p class="text-sm font-bold text-slate-700">No se encontraron productos en esta categoría.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold">
                    Ver todos los productos
                </a>
            </div>
        @endif
    </section>

    <!-- 4. CUATRO BENEFICIOS INFERIORES (Exact TecnoStore Mockup Bar) -->
    <section class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-left">
            
            <!-- 1: Productos de calidad -->
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Productos de calidad</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Las mejores marcas</p>
                </div>
            </div>

            <!-- 2: Precios competitivos -->
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Precios competitivos</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Ofertas y promociones</p>
                </div>
            </div>

            <!-- 3: Atención personalizada -->
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Atención personalizada</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Estamos para ayudarte</p>
                </div>
            </div>

            <!-- 4: Catálogo actualizado -->
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Catálogo actualizado</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Información siempre al día</p>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slide');
        const dotsContainer = document.getElementById('slider-dots-container');
        const prevBtn = document.getElementById('slider-btn-prev');
        const nextBtn = document.getElementById('slider-btn-next');
        let currentSlide = 0;
        let slideInterval = null;

        if (slides.length <= 1) {
            if (prevBtn) prevBtn.classList.add('hidden');
            if (nextBtn) nextBtn.classList.add('hidden');
            if (dotsContainer) dotsContainer.classList.add('hidden');
            return;
        }

        // Generate dots
        dotsContainer.innerHTML = '';
        slides.forEach((_, idx) => {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = `w-2.5 h-2.5 rounded-full transition-all duration-300 ${idx === 0 ? 'bg-blue-600 w-6' : 'bg-slate-300 hover:bg-slate-400'}`;
            dot.setAttribute('aria-label', `Ir a diapositiva ${idx + 1}`);
            dot.addEventListener('click', () => {
                goToSlide(idx);
                resetAutoPlay();
            });
            dotsContainer.appendChild(dot);
        });

        function updateDots() {
            const dots = dotsContainer.querySelectorAll('button');
            dots.forEach((dot, idx) => {
                if (idx === currentSlide) {
                    dot.className = 'w-6 h-2.5 rounded-full bg-blue-600 transition-all duration-300';
                } else {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300';
                }
            });
        }

        function goToSlide(index) {
            slides[currentSlide].classList.add('hidden');
            slides[currentSlide].classList.remove('opacity-100');
            
            currentSlide = (index + slides.length) % slides.length;
            
            slides[currentSlide].classList.remove('hidden');
            slides[currentSlide].classList.add('opacity-100');
            updateDots();
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                resetAutoPlay();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                resetAutoPlay();
            });
        }

        function startAutoPlay() {
            slideInterval = setInterval(nextSlide, 6000);
        }

        function resetAutoPlay() {
            clearInterval(slideInterval);
            startAutoPlay();
        }

        const bannerSection = document.getElementById('hero-banner-section');
        if (bannerSection) {
            bannerSection.addEventListener('mouseenter', () => clearInterval(slideInterval));
            bannerSection.addEventListener('mouseleave', () => startAutoPlay());
        }

        startAutoPlay();
    });
</script>
@endpush
