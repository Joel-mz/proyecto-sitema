@extends('layouts.app')

@section('title', $product->name . ' - Catálogo Virtual')
@section('meta_description', Str::limit(strip_tags($product->description), 150) ?: 'Detalle de producto: ' . $product->name . ' en TechStore.')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-8 sm:space-y-12">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-bold text-slate-500 overflow-x-auto no-scrollbar whitespace-nowrap">
        <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Inicio</a>
        <span>/</span>
        <a href="{{ route('catalog.category', $product->category->slug) }}" class="hover:text-blue-600 transition">
            {{ $product->category->name }}
        </a>
        <span>/</span>
        <span class="text-slate-800 truncate max-w-[160px] sm:max-w-none">{{ $product->name }}</span>
    </div>

    <!-- Main Product Card -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-md p-4 sm:p-10 lg:p-12 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-10 lg:gap-14 items-center">
            <!-- Left Column: Large Image Box with zoom effect -->
            <div class="w-full aspect-square bg-slate-50 rounded-2xl border border-slate-200/80 overflow-hidden flex items-center justify-center p-4 sm:p-10 shadow-inner group">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-contain group-hover:scale-108 transition-transform duration-500">
                @else
                    <div class="flex flex-col items-center justify-center text-slate-400 gap-2 sm:gap-3">
                        <svg class="w-14 h-14 sm:w-20 sm:h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs sm:text-sm font-bold">Imagen no disponible</span>
                    </div>
                @endif
            </div>

            <!-- Right Column: Product Info & Price -->
            <div class="space-y-4 sm:space-y-6">
                <div>
                    <a href="{{ route('catalog.category', $product->category->slug) }}"
                       class="inline-block px-3 py-1 rounded-full text-[10px] sm:text-xs font-black uppercase tracking-wider bg-blue-50 text-blue-700 hover:bg-blue-100 transition mb-2 sm:mb-3 border border-blue-200">
                        {{ $product->category->name }}
                    </a>
                    <h1 class="text-xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price Box with Delivery Info -->
                <div class="p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-slate-50 to-blue-50/40 border border-slate-200/80 flex items-center justify-between gap-3 shadow-xs">
                    <div>
                        <span class="text-[10px] sm:text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Precio de Catálogo</span>
                        <span class="text-2xl sm:text-4xl font-black text-blue-600">
                            S/ {{ number_format($product->price, 2) }}
                        </span>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] sm:text-xs font-extrabold border border-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Stock Disponible
                    </div>
                </div>

                <!-- Delivery Highlights -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center gap-2">
                        <span class="text-base">🏪</span>
                        <div>
                            <span class="block font-bold text-slate-800 text-[11px]">Recojo en Moyobamba</span>
                            <span class="block text-[10px] text-emerald-600 font-bold">Entrega Inmediata</span>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center gap-2">
                        <span class="text-base">🚚</span>
                        <div>
                            <span class="block font-bold text-slate-800 text-[11px]">Envíos San Martín</span>
                            <span class="block text-[10px] text-slate-500">Tarapoto, Rioja y más</span>
                        </div>
                    </div>
                </div>

                <!-- Quantity & Cart Actions -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border-2 border-slate-200 rounded-xl bg-slate-50 p-1">
                            <button type="button" onclick="adjustProductQty(-1)" class="w-9 h-9 rounded-lg bg-white shadow-xs text-slate-700 hover:bg-slate-100 font-black text-lg flex items-center justify-center transition cursor-pointer">-</button>
                            <input type="number" id="detail-product-qty" value="1" min="1" max="99" class="w-12 text-center bg-transparent font-black text-slate-900 text-sm focus:outline-none" readonly>
                            <button type="button" onclick="adjustProductQty(1)" class="w-9 h-9 rounded-lg bg-white shadow-xs text-slate-700 hover:bg-slate-100 font-black text-lg flex items-center justify-center transition cursor-pointer">+</button>
                        </div>

                        <!-- Add to Cart Button -->
                        <button type="button"
                                onclick="addProductToCartWithQty()"
                                class="flex-1 py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-98 text-white rounded-xl font-extrabold text-xs sm:text-sm shadow-lg shadow-blue-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>+ Agregar a Mi Pedido</span>
                        </button>
                    </div>

                    <!-- Direct WhatsApp Single Order Button -->
                    <button type="button"
                            onclick="orderSingleWhatsApp()"
                            class="w-full py-3.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-emerald-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                        </svg>
                        <span>Comprar / Consultar por WhatsApp</span>
                    </button>
                </div>

                <!-- Description -->
                <div class="space-y-2 sm:space-y-3 pt-2">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Descripción & Especificaciones</h3>
                    <div class="text-slate-600 text-xs sm:text-base leading-relaxed whitespace-pre-line bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                        {{ $product->description ?: 'No se especificó una descripción detallada para este producto.' }}
                    </div>
                </div>

                <!-- Actions Back / PDF -->
                <div class="pt-4 sm:pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-2.5 sm:gap-4">
                    <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Volver al Catálogo</span>
                    </a>

                    <a href="{{ route('catalog.pdf') }}" class="w-full sm:w-auto px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 border border-rose-200 cursor-pointer">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Descargar PDF</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <section class="space-y-4 sm:space-y-6 pt-2 sm:pt-6">
            <h2 class="text-base sm:text-xl font-black text-slate-900 tracking-tight px-1">
                Otros productos en {{ $product->category->name }}
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl hover:border-blue-300 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="relative w-full aspect-square bg-slate-50 overflow-hidden flex items-center justify-center p-3 sm:p-5">
                                <a href="{{ route('catalog.show', $rel->slug) }}" class="w-full h-full flex items-center justify-center">
                                    @if($rel->image)
                                        <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-contain group-hover:scale-108 transition duration-300">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </a>
                            </div>
                            <div class="p-3 sm:p-4 space-y-1">
                                <h4 class="font-extrabold text-slate-800 text-xs sm:text-sm line-clamp-2 group-hover:text-blue-600 transition">
                                    <a href="{{ route('catalog.show', $rel->slug) }}">{{ $rel->name }}</a>
                                </h4>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 pt-0">
                            <div class="pt-2 border-t border-slate-100 flex flex-col gap-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-black text-blue-600 text-xs sm:text-sm">S/ {{ number_format($rel->price, 2) }}</span>
                                    <a href="{{ route('catalog.show', $rel->slug) }}" class="text-[11px] text-slate-400 hover:text-blue-600 font-bold">&rarr;</a>
                                </div>
                                <button type="button"
                                        onclick="addToCart({{ $rel->id }}, '{{ addslashes($rel->name) }}', {{ $rel->price }}, '{{ $rel->image ? asset('storage/' . $rel->image) : '' }}', '{{ route('catalog.show', $rel->slug) }}', 1)"
                                        class="w-full py-2 px-2.5 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-[10px] sm:text-xs font-black transition flex items-center justify-center gap-1 cursor-pointer">
                                    <span>+ Carrito</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>

<script>
    function adjustProductQty(delta) {
        const input = document.getElementById('detail-product-qty');
        let val = parseInt(input.value) || 1;
        val = Math.max(1, Math.min(99, val + delta));
        input.value = val;
    }

    function addProductToCartWithQty() {
        const qty = parseInt(document.getElementById('detail-product-qty').value) || 1;
        addToCart(
            {{ $product->id }},
            '{{ addslashes($product->name) }}',
            {{ $product->price }},
            '{{ $product->image ? asset('storage/' . $product->image) : '' }}',
            '{{ route('catalog.show', $product->slug) }}',
            qty
        );
    }

    function orderSingleWhatsApp() {
        const qty = parseInt(document.getElementById('detail-product-qty').value) || 1;
        const total = ({{ $product->price }} * qty).toFixed(2);
        let msg = `👋 *CONSULTA DE PRODUCTO - ${COMPANY_NAME}*\n`;
        msg += `━━━━━━━━━━━━━━━━━━━━\n`;
        msg += `💻 *Producto:* {{ $product->name }}\n`;
        msg += `▪ Cantidad: ${qty} und.\n`;
        msg += `▪ Precio Unitario: S/ {{ number_format($product->price, 2) }}\n`;
        msg += `▪ Subtotal Estimado: S/ ${total}\n`;
        @if($product->image)
            msg += `▪ 🖼️ Foto: {{ asset('storage/' . $product->image) }}\n`;
        @else
            msg += `▪ 🔗 Ver Producto: {{ route('catalog.show', $product->slug) }}\n`;
        @endif
        msg += `━━━━━━━━━━━━━━━━━━━━\n`;
        msg += `📍 *Ubicación / Modalidad:* Recojo en Moyobamba o Envío San Martín\n`;
        msg += `Hola, deseo consultar la disponibilidad de este producto y los datos para el pago (Yape / Plin / Transferencia). ¡Muchas gracias!`;

        const whatsappUrl = `https://api.whatsapp.com/send?phone=${COMPANY_WHATSAPP}&text=${encodeURIComponent(msg)}`;
        window.location.href = whatsappUrl;
    }
</script>
@endsection
