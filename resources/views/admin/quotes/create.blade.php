@extends('layouts.admin')

@section('title', 'Nueva Cotización Comercial')
@section('header_title', 'Generar Cotización')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Form Container -->
    <form action="{{ route('quotes.store') }}" method="POST" id="quote-form" class="space-y-6">
        @csrf

        <!-- Card 1: Client & Quote Details -->
        <div class="bg-white p-5 sm:p-7 rounded-2xl border border-slate-200 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Datos del Cliente y Cotización</h3>
                    <p class="text-xs text-slate-500">Completa la información del solicitante para la proforma formal.</p>
                </div>
                <div class="px-3 py-1 bg-blue-50 text-blue-700 font-extrabold text-xs rounded-xl border border-blue-200 self-start sm:self-auto">
                    N° {{ $nextNumber }}
                </div>
            </div>

            <!-- Client Type & Document -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tipo de Documento <span class="text-rose-500">*</span></label>
                    <select name="customer_document_type" id="customer_document_type" onchange="toggleDocType(this.value)" class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="DNI" {{ old('customer_document_type') == 'DNI' ? 'selected' : '' }}>DNI (Persona Natural)</option>
                        <option value="RUC" {{ old('customer_document_type') == 'RUC' ? 'selected' : '' }}>RUC (Empresa / Factura)</option>
                    </select>
                </div>

                <div>
                    <label id="lbl-doc" class="block text-xs font-bold text-slate-700 mb-1">Número de DNI (8 dígitos)</label>
                    <input type="text" name="customer_document" id="customer_document" value="{{ old('customer_document') }}" maxlength="11" placeholder="Ej. 74859612" class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label id="lbl-name" class="block text-xs font-bold text-slate-700 mb-1">Nombre Completo / Contacto <span class="text-rose-500">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required placeholder="Ej. Carlos Mendoza" class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <!-- Contact, City, Address -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teléfono / Celular</label>
                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Ej. 987654321" class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="Ej. cliente@empresa.pe" class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ciudad / Región</label>
                    <input type="text" name="city" value="{{ old('city', 'Moyobamba') }}" placeholder="Ej. Moyobamba, Tarapoto, etc." class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Dirección de Entrega / Referencia</label>
                    <input type="text" name="customer_address" value="{{ old('customer_address') }}" placeholder="Ej. Jr. San Martín 450" class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Días de Validez de la Oferta</label>
                    <input type="number" name="validity_days" value="{{ old('validity_days', 15 Webber ?? 15) }}" min="1" max="180" class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Card 2: Items / Products Builder -->
        <div class="bg-white p-5 sm:p-7 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Productos & Ítems de la Cotización</h3>
                    <p class="text-xs text-slate-500">Agrega productos del catálogo o escribe conceptos personalizados.</p>
                </div>

                <!-- Add Row Button -->
                <button type="button" onclick="addItemRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl border border-blue-200 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Agregar Fila</span>
                </button>
            </div>

            <!-- Quick Add from Catalog Dropdown -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex flex-col sm:flex-row items-center gap-3">
                <label class="text-xs font-bold text-slate-700 flex-shrink-0">📦 Cargar rápido desde Catálogo:</label>
                <select id="quick-product-select" class="flex-1 w-full px-3 py-2 text-xs border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Selecciona un producto para añadir --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}">
                            {{ $p->name }} (S/ {{ number_format($p->price, 2) }})
                        </option>
                    @endforeach
                </select>
                <button type="button" onclick="addSelectedProduct()" class="w-full sm:w-auto px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition cursor-pointer">
                    Insertar
                </button>
            </div>

            <!-- Items Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="items-table">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="py-2.5 px-3" style="width: 50%;">Descripción del Producto</th>
                            <th class="py-2.5 px-3 text-center" style="width: 15%;">Cantidad</th>
                            <th class="py-2.5 px-3 text-right" style="width: 18%;">P. Unitario (S/)</th>
                            <th class="py-2.5 px-3 text-right" style="width: 12%;">Subtotal</th>
                            <th class="py-2.5 px-3 text-center" style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody id="items-tbody" class="divide-y divide-slate-100">
                        <!-- Default first row -->
                        <tr class="item-row">
                            <td class="py-2 px-2">
                                <input type="hidden" name="items[0][product_id]" class="row-product-id" value="">
                                <input type="text" name="items[0][product_name]" required placeholder="Nombre o modelo del producto" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none row-name">
                            </td>
                            <td class="py-2 px-2">
                                <input type="number" name="items[0][quantity]" value="1" min="1" required oninput="calculateTotals()" class="w-full px-2 py-2 text-center text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none row-qty font-bold">
                            </td>
                            <td class="py-2 px-2">
                                <input type="number" step="0.01" name="items[0][unit_price]" value="0.00" min="0" required oninput="calculateTotals()" class="w-full px-3 py-2 text-right text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none row-price font-bold">
                            </td>
                            <td class="py-2 px-2 text-right font-black text-slate-800 row-subtotal">
                                S/ 0.00
                            </td>
                            <td class="py-2 px-2 text-center">
                                <button type="button" onclick="removeRow(this)" class="p-1.5 text-slate-400 hover:text-rose-600 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes and Summary Totals -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Notas / Condiciones Especiales</label>
                    <textarea name="notes" rows="3" placeholder="Ej. Incluye instalación, garantía de 12 meses, entrega inmediata en tienda." class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Subtotal:</span>
                        <span id="calc-subtotal" class="font-bold text-slate-800">S/ 0.00</span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Descuento global (S/):</span>
                        <input type="number" step="0.01" name="discount" id="discount-input" value="0.00" min="0" oninput="calculateTotals()" class="w-24 px-2 py-1 text-right text-xs border border-slate-200 rounded-lg bg-white font-bold text-rose-600">
                    </div>

                    <div class="flex items-center justify-between text-sm sm:text-base font-black text-slate-900 pt-2 border-t border-slate-200">
                        <span>TOTAL NETO:</span>
                        <span id="calc-total" class="text-blue-600 text-lg">S/ 0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('quotes.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-black shadow-md shadow-blue-600/25 transition cursor-pointer">
                Guardar y Generar PDF
            </button>
        </div>
    </form>

</div>

<script>
    let rowIndex = 1;

    function toggleDocType(type) {
        const lblDoc = document.getElementById('lbl-doc');
        const inputDoc = document.getElementById('customer_document');
        const lblName = document.getElementById('lbl-name');
        const inputName = document.getElementById('customer_name');

        if (type === 'DNI') {
            lblDoc.textContent = 'Número de DNI (8 dígitos)';
            inputDoc.placeholder = 'Ej. 74859612';
            inputDoc.maxLength = 8;
            lblName.innerHTML = 'Nombre Completo / Contacto <span class="text-rose-500">*</span>';
            inputName.placeholder = 'Ej. Carlos Mendoza';
        } else {
            lblDoc.textContent = 'Número de RUC (11 dígitos)';
            inputDoc.placeholder = 'Ej. 20601234567';
            inputDoc.maxLength = 11;
            lblName.innerHTML = 'Razón Social / Empresa <span class="text-rose-500">*</span>';
            inputName.placeholder = 'Ej. Inversiones San Martín S.A.C.';
        }
    }

    function addItemRow(productId = '', productName = '', unitPrice = '0.00', quantity = 1) {
        const tbody = document.getElementById('items-tbody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="py-2 px-2">
                <input type="hidden" name="items[${rowIndex}][product_id]" class="row-product-id" value="${productId}">
                <input type="text" name="items[${rowIndex}][product_name]" value="${productName}" required placeholder="Nombre o modelo del producto" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none row-name">
            </td>
            <td class="py-2 px-2">
                <input type="number" name="items[${rowIndex}][quantity]" value="${quantity}" min="1" required oninput="calculateTotals()" class="w-full px-2 py-2 text-center text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none row-qty font-bold">
            </td>
            <td class="py-2 px-2">
                <input type="number" step="0.01" name="items[${rowIndex}][unit_price]" value="${unitPrice}" min="0" required oninput="calculateTotals()" class="w-full px-3 py-2 text-right text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none row-price font-bold">
            </td>
            <td class="py-2 px-2 text-right font-black text-slate-800 row-subtotal">
                S/ ${(quantity * unitPrice).toFixed(2)}
            </td>
            <td class="py-2 px-2 text-center">
                <button type="button" onclick="removeRow(this)" class="p-1.5 text-slate-400 hover:text-rose-600 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        calculateTotals();
    }

    function addSelectedProduct() {
        const select = document.getElementById('quick-product-select');
        const selected = select.options[select.selectedIndex];
        if (!select.value) return;

        const id = select.value;
        const name = selected.getAttribute('data-name');
        const price = selected.getAttribute('data-price');

        // Check if the first row is empty, then replace it
        const firstRow = document.querySelector('#items-tbody .item-row');
        const firstName = firstRow?.querySelector('.row-name');
        if (firstRow && firstName && !firstName.value.trim()) {
            firstRow.querySelector('.row-product-id').value = id;
            firstName.value = name;
            firstRow.querySelector('.row-price').value = price;
            calculateTotals();
        } else {
            addItemRow(id, name, price, 1);
        }

        select.selectedIndex = 0;
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('#items-tbody .item-row');
        if (rows.length <= 1) {
            alert('La cotización debe tener al menos un producto.');
            return;
        }
        btn.closest('tr').remove();
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#items-tbody .item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.row-qty')?.value) || 0;
            const price = parseFloat(row.querySelector('.row-price')?.value) || 0;
            const lineTotal = qty * price;
            const subtotalEl = row.querySelector('.row-subtotal');
            if (subtotalEl) subtotalEl.textContent = `S/ ${lineTotal.toFixed(2)}`;
            subtotal += lineTotal;
        });

        const discount = parseFloat(document.getElementById('discount-input')?.value) || 0;
        const total = Math.max(0, subtotal - discount);

        document.getElementById('calc-subtotal').textContent = `S/ ${subtotal.toFixed(2)}`;
        document.getElementById('calc-total').textContent = `S/ ${total.toFixed(2)}`;
    }

    document.addEventListener('DOMContentLoaded', calculateTotals);
</script>
@endsection
