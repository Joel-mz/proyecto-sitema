@extends('layouts.app')

@section('title', $product->name . ' - Catálogo Virtual')
@section('meta_description', Str::limit(strip_tags($product->description), 150) ?: 'Detalle de producto: ' . $product->name . ' en TecnoStore.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-8">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Inicio</a>
        <span>/</span>
        <a href="{{ route('catalog.category', $product->category->slug) }}" class="hover:text-blue-600 transition">
            {{ $product->category->name }}
        </a>
        <span>/</span>
        <span class="text-slate-800 truncate max-w-xs">{{ $product->name }}</span>
    </div>

    <!-- Main Product Card (Exact TecnoStore Detail Layout) -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-10 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-center">
            
            <!-- Left: Product Image Box -->
            <div class="w-full aspect-square bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-center p-6 overflow-hidden">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         class="max-h-full max-w-full object-contain hover:scale-105 transition-transform duration-300">
                @else
                    <div class="flex flex-col items-center justify-center text-slate-400 gap-2">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs font-medium">Sin imagen</span>
                    </div>
                @endif
            </div>

            <!-- Right: Product Info & Actions -->
            <div class="space-y-5 text-left">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        {{ $product->category->name }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-snug">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price Highlight -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Precio de Catálogo</span>
                        <span class="text-2xl sm:text-3xl font-black text-blue-600">
                            S/ {{ number_format($product->price, 2) }}
                        </span>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                        Stock Disponible
                    </span>
                </div>

                <!-- Description & Characteristics -->
                <div class="space-y-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Características:</h3>
                    <div class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/50 p-3.5 rounded-xl border border-slate-100">
                        {{ $product->description ?: 'Producto con garantía y alta durabilidad para trabajo, estudio y uso diario.' }}
                    </div>
                </div>

                <!-- Quantity & Buttons -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-slate-200 rounded-lg bg-white p-1">
                            <button type="button" onclick="adjustProductQty(-1)" class="w-8 h-8 rounded bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition cursor-pointer">-</button>
                            <input type="number" id="detail-product-qty" value="1" min="1" max="99" class="w-10 text-center font-bold text-slate-900 text-sm focus:outline-none" readonly>
                            <button type="button" onclick="adjustProductQty(1)" class="w-8 h-8 rounded bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition cursor-pointer">+</button>
                        </div>

                        <button type="button"
                                onclick="addProductToCartWithQty()"
                                class="flex-1 py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-bold text-xs sm:text-sm transition flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Agregar al Carrito</span>
                        </button>
                    </div>

                    <!-- Direct WhatsApp Buy Button -->
                    <button type="button"
                            onclick="orderSingleWhatsApp()"
                            class="w-full py-3 px-4 rounded-lg bg-[#0052cc] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                        </svg>
                        <span>Comprar por WhatsApp</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

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
            '{{ $product->image_url ?? '' }}',
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
        msg += `▪ Total: S/ ${total}\n`;
        @if($product->image_url)
            msg += `▪ 🖼️ Foto: {{ $product->image_url }}\n`;
        @else
            msg += `▪ 🔗 Enlace: {{ route('catalog.show', $product->slug) }}\n`;
        @endif
        msg += `━━━━━━━━━━━━━━━━━━━━\n`;
        msg += `Hola, deseo consultar la disponibilidad y comprar este producto por WhatsApp. ¡Gracias!`;

        const whatsappUrl = `https://api.whatsapp.com/send?phone=${COMPANY_WHATSAPP}&text=${encodeURIComponent(msg)}`;
        window.location.href = whatsappUrl;
    }
</script>
@endsection
