@extends('layouts.admin')

@section('title', 'Detalle de Cotización ' . $quote->quote_number)
@section('header_title', 'Cotización ' . $quote->quote_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Status Bar & Actions -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xl shadow-xs">
                📄
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">{{ $quote->quote_number }}</h2>
                    @if($quote->status == 'aprobada')
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">Aprobada</span>
                    @elseif($quote->status == 'facturada')
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-xs">Facturada</span>
                    @elseif($quote->status == 'rechazada')
                        <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-xs">Rechazada</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-xs">Pendiente</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Emitida el {{ $quote->created_at->format('d/m/Y H:i A') }} • Validez: {{ $quote->validity_days }} días
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- WhatsApp Direct Share -->
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $quote->customer_phone ?? '');
                if (strlen($cleanPhone) === 9 && str_starts_with($cleanPhone, '9')) {
                    $cleanPhone = '51' . $cleanPhone;
                }
                $pdfUrl = route('quotes.pdf', $quote);
                $whatsappMsg = "📄 *HOLA {$quote->customer_name} - {$company->name}*\n";
                $whatsappMsg .= "Le hacemos llegar la *Cotización N° {$quote->quote_number}* por un total de *S/ " . number_format($quote->total, 2) . "*.\n\n";
                $whatsappMsg .= "📦 *PRODUCTOS:* \n";
                foreach($quote->items as $idx => $it) {
                    $whatsappMsg .= ($idx+1) . ". {$it->product_name} x {$it->quantity} und. = S/ " . number_format($it->subtotal, 2) . "\n";
                }
                $whatsappMsg .= "\n💰 *TOTAL A PAGAR: S/ " . number_format($quote->total, 2) . "*\n\n";
                $whatsappMsg .= "Puede descargar la proforma formal en PDF o responder este mensaje para confirmar su pedido. ¡Muchas gracias!";
            @endphp

            @if($cleanPhone)
                <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}&text={{ urlencode($whatsappMsg) }}" target="_blank"
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.529 1.771.815 2.796.815 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.536-.492-5.021-1.354l-6.979 1.827 1.861-6.804c-.958-1.545-1.503-3.364-1.503-5.309 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                    </svg>
                    <span>Enviar al Cliente por WhatsApp</span>
                </a>
            @endif

            <!-- Download PDF -->
            <a href="{{ route('quotes.pdf', $quote) }}" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Descargar PDF</span>
            </a>

            <!-- Back -->
            <a href="{{ route('quotes.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                Volver
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col Left: Products & Totals -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Products Card -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base border-b border-slate-100 pb-3">
                    Productos Cotizados
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-700 font-bold uppercase text-[10px]">
                            <tr>
                                <th class="py-2.5 px-3">#</th>
                                <th class="py-2.5 px-3">Descripción</th>
                                <th class="py-2.5 px-3 text-center">Cant.</th>
                                <th class="py-2.5 px-3 text-right">P. Unitario</th>
                                <th class="py-2.5 px-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($quote->items as $idx => $item)
                                <tr>
                                    <td class="py-3 px-3 font-bold">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-3 font-bold text-slate-900">
                                        {{ $item->product_name }}
                                        @if($item->product && $item->product->image)
                                            <a href="{{ asset('storage/' . $item->product->image) }}" target="_blank" class="text-[10px] text-blue-600 block hover:underline">Ver Imagen</a>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center font-bold">{{ $item->quantity }}</td>
                                    <td class="py-3 px-3 text-right">S/ {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 px-3 text-right font-black text-slate-900">S/ {{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals Breakdown -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <div class="w-full sm:w-64 space-y-1.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span class="font-bold text-slate-800">S/ {{ number_format($quote->subtotal, 2) }}</span>
                        </div>
                        @if($quote->discount > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>Descuento:</span>
                                <span class="font-bold">- S/ {{ number_format($quote->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-base font-black text-slate-900 pt-2 border-t border-slate-200">
                            <span>TOTAL:</span>
                            <span class="text-blue-600">S/ {{ number_format($quote->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($quote->notes)
                <!-- Notes Card -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Notas y Condiciones</h4>
                    <p class="text-xs text-slate-600 whitespace-pre-line leading-relaxed">{{ $quote->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Col Right: Customer Details & Status Updater -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-100 pb-2.5">
                    Datos del Cliente
                </h3>

                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Cliente / Razón Social</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $quote->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">{{ $quote->customer_document_type }}</span>
                        <span class="font-semibold text-slate-700">{{ $quote->customer_document ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Teléfono / WhatsApp</span>
                        <span class="font-semibold text-slate-700">{{ $quote->customer_phone ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Correo Electrónico</span>
                        <span class="font-semibold text-slate-700">{{ $quote->customer_email ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Ubicación / Ciudad</span>
                        <span class="font-semibold text-slate-700">{{ $quote->city ?: 'Moyobamba' }}</span>
                    </div>

                    @if($quote->customer_address)
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Dirección</span>
                            <span class="font-semibold text-slate-700">{{ $quote->customer_address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Status Card -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-100 pb-2.5">
                    Actualizar Estado
                </h3>

                <form action="{{ route('quotes.status', $quote) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white font-bold">
                        <option value="pendiente" {{ $quote->status == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                        <option value="aprobada" {{ $quote->status == 'aprobada' ? 'selected' : '' }}>✅ Aprobada por Cliente</option>
                        <option value="facturada" {{ $quote->status == 'facturada' ? 'selected' : '' }}>💳 Facturada / Vendida</option>
                        <option value="rechazada" {{ $quote->status == 'rechazada' ? 'selected' : '' }}>❌ Rechazada / Cancelada</option>
                    </select>

                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                        Actualizar Estado
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
