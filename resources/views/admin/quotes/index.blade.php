@extends('layouts.admin')

@section('title', 'Gestión de Cotizaciones')
@section('header_title', 'Cotizaciones Comerciales')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Cotizaciones Registradas</h2>
            <p class="text-xs text-slate-500">Administra, exporta a PDF y envía cotizaciones formales a tus clientes.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('quotes.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nueva Cotización</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('quotes.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar por N° Cotización, cliente, DNI/RUC..."
                       class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <select name="status" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">Todos los Estados</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                    <option value="aprobada" {{ request('status') == 'aprobada' ? 'selected' : '' }}>✅ Aprobada</option>
                    <option value="facturada" {{ request('status') == 'facturada' ? 'selected' : '' }}>💳 Facturada</option>
                    <option value="rechazada" {{ request('status') == 'rechazada' ? 'selected' : '' }}>❌ Rechazada</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                    Filtrar
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('quotes.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Quotes Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">N° Cotización</th>
                        <th class="py-3 px-4">Cliente / Razón Social</th>
                        <th class="py-3 px-4">Documento</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Fecha / Validez</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($quotes as $quote)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-extrabold text-blue-600">
                                <a href="{{ route('quotes.show', $quote) }}" class="hover:underline">
                                    {{ $quote->quote_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $quote->customer_name }}
                                @if($quote->customer_phone)
                                    <span class="block text-[10px] text-slate-400 font-normal">📱 {{ $quote->customer_phone }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">
                                    {{ $quote->customer_document_type }}: {{ $quote->customer_document ?: '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-black text-slate-900 text-sm">
                                S/ {{ number_format($quote->total, 2) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                <div>{{ $quote->created_at->format('d/m/Y') }}</div>
                                <span class="text-[10px] text-slate-400">Validez: {{ $quote->validity_days }} días</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($quote->status == 'aprobada')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">Aprobada</span>
                                @elseif($quote->status == 'facturada')
                                    <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-bold text-[10px]">Facturada</span>
                                @elseif($quote->status == 'rechazada')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 font-bold text-[10px]">Rechazada</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">Pendiente</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- View / Details -->
                                    <a href="{{ route('quotes.show', $quote) }}" class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition" title="Ver Detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <!-- Download PDF -->
                                    <a href="{{ route('quotes.pdf', $quote) }}" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Descargar PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('quotes.destroy', $quote) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta cotización?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No se encontraron cotizaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotes->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
