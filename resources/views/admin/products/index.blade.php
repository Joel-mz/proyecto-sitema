@extends('layouts.admin')

@section('title', 'Productos')
@section('header_title', 'Administración de Productos')

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Listado de Productos</h2>
            <p class="text-xs text-slate-500 mt-0.5">Administra precios de venta, precios mínimos, descripciones, categorías e imágenes</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Export Excel Button -->
            <a href="{{ route('products.export') }}" title="Descargar todos los productos en Excel / CSV"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 shadow-xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Exportar Excel</span>
            </a>

            <!-- Import Excel Button -->
            <button type="button" onclick="openImportModal()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>Importar Excel / CSV</span>
            </button>

            @if($products->total() > 0)
                <form action="{{ route('products.deleteAll') }}" method="POST" onsubmit="return confirm('⚠️ ¿ESTÁS SEGURO DE ELIMINAR TODOS LOS PRODUCTOS? Esta acción no se puede deshacer y borrará todo el inventario.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs sm:text-sm font-semibold rounded-xl border border-rose-200 transition">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span class="hidden sm:inline">Eliminar Todo</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nuevo Producto</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o descripción..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div class="w-full md:w-64">
                <select name="category_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                    <option value="">Todas las Categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl transition">
                    Filtrar
                </button>
                @if(request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('products.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bulk Action Toolbar (Active when at least 1 checkbox selected) -->
    <form id="bulk-delete-form" action="{{ route('products.bulkDelete') }}" method="POST">
        @csrf

        <div id="bulk-actions-bar" class="hidden mb-4 p-4 bg-slate-900 text-white rounded-2xl shadow-xl flex items-center justify-between transition transform duration-200">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-xs font-bold" id="selected-count-badge">0</span>
                <span class="text-xs sm:text-sm font-medium" id="selected-count-text">productos seleccionados</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="clearSelections()" class="px-3 py-1.5 text-xs text-slate-300 hover:text-white transition">
                    Deseleccionar
                </button>
                <button type="submit" onclick="return confirm('¿Estás seguro de eliminar los productos seleccionados?');" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Eliminar Seleccionados</span>
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">
                                <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" title="Seleccionar todo">
                            </th>
                            <th class="py-3.5 px-6">Producto</th>
                            <th class="py-3.5 px-6">Categoría</th>
                            <th class="py-3.5 px-6">Precio Venta (Público)</th>
                            <th class="py-3.5 px-6">Precio Mínimo (Piso)</th>
                            <th class="py-3.5 px-6">Estado</th>
                            <th class="py-3.5 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($products as $prod)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $prod->id }}" class="product-checkbox w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200/80 overflow-hidden flex-shrink-0 flex items-center justify-center p-1">
                                            @if($prod->image_url)
                                                <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-contain">
                                            @else
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $prod->name }}</p>
                                            <p class="text-xs text-slate-500 line-clamp-1 max-w-xs">{{ $prod->description ?: 'Sin descripción' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                        {{ $prod->category->name ?? 'Sin categoría' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-extrabold text-blue-600">
                                    S/ {{ number_format($prod->price, 2) }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($prod->min_price)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            S/ {{ number_format($prod->min_price, 2) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">No definido</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($prod->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('products.edit', $prod) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <button type="button" onclick="deleteSingleProduct('{{ $prod->id }}')" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    No se encontraron productos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </form>

    <!-- Hidden form for single delete -->
    <form id="single-delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Import Excel / CSV Modal -->
    <div id="import-modal-backdrop" onclick="closeImportModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden transition-opacity"></div>

    <div id="import-modal" class="fixed inset-0 z-50 hidden overflow-y-auto flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200 space-y-5 animate-in fade-in zoom-in duration-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Importar Productos en Masa</h3>
                        <p class="text-xs text-slate-400">Sube un archivo de Excel (.xlsx) o CSV</p>
                    </div>
                </div>
                <button type="button" onclick="closeImportModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Download Template Banner -->
            <div class="bg-blue-50/70 border border-blue-200/70 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <span class="block text-xs font-bold text-blue-900">¿No tienes el formato exacto?</span>
                    <span class="block text-[11px] text-blue-700">Descarga la plantilla con ejemplos listos para rellenar en Excel.</span>
                </div>
                <a href="{{ route('products.template') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold whitespace-nowrap shadow-xs transition flex items-center justify-center gap-1.5 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Descargar Plantilla (.CSV)</span>
                </a>
            </div>

            <!-- Import Form -->
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return handleImportSubmit(event)">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">
                        Selecciona tu archivo de Excel (.xlsx o .csv): <span class="text-rose-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-5 text-center cursor-pointer transition bg-slate-50/50 relative">
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.csv,.txt" required
                               onchange="updateImportFileName(this)"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <span id="import-file-name" class="block text-xs font-bold text-slate-700">Haz clic aquí o arrastra tu archivo Excel</span>
                            <span class="block text-[10px] text-slate-400">Formatos permitidos: .xlsx, .csv (Máx. 50 MB)</span>
                        </div>
                    </div>
                </div>

                <!-- Column Reference List -->
                <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100 text-[11px] text-slate-600 space-y-1">
                    <span class="block font-bold text-slate-800">Columnas reconocidas automáticamente:</span>
                    <p class="text-slate-500 leading-relaxed">
                        • <strong class="text-slate-700">nombre</strong> (Requerido) | • <strong class="text-slate-700">categoria</strong> (Ej. <em>Computación</em> o <em>Computación > Laptops</em>) | • <strong class="text-slate-700">precio</strong> (Ej. <em>2499.00</em>) | • <strong class="text-slate-700">stock</strong> | • <strong class="text-slate-700">descripcion</strong> | • <strong class="text-slate-700">url_imagen</strong> | • <strong class="text-slate-700">destacado</strong> (SI/NO).
                    </p>
                    <p class="text-slate-400 text-[10px] pt-1">
                        * Si el producto ya existe con el mismo nombre, se actualizarán su precio, stock y características automáticamente.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" id="btn-submit-import" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span id="btn-submit-import-text">Procesar e Importar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const selectAllCheckbox = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const bulkActionsBar = document.getElementById('bulk-actions-bar');
    const selectedCountBadge = document.getElementById('selected-count-badge');
    const selectedCountText = document.getElementById('selected-count-text');

    function openImportModal() {
        document.getElementById('import-modal-backdrop').classList.remove('hidden');
        document.getElementById('import-modal').classList.remove('hidden');
    }

    function closeImportModal() {
        document.getElementById('import-modal-backdrop').classList.add('hidden');
        document.getElementById('import-modal').classList.add('hidden');
    }

    function updateImportFileName(input) {
        const label = document.getElementById('import-file-name');
        if (input.files && input.files[0]) {
            label.textContent = '📄 ' + input.files[0].name + ' (' + (input.files[0].size / 1024).toFixed(1) + ' KB)';
            label.className = 'block text-xs font-bold text-emerald-700';
        }
    }

    function handleImportSubmit(e) {
        const fileInput = document.getElementById('excel_file');
        if (!fileInput.files || !fileInput.files[0]) {
            alert('Por favor selecciona un archivo de Excel primero.');
            e.preventDefault();
            return false;
        }

        const btn = document.getElementById('btn-submit-import');
        const txt = document.getElementById('btn-submit-import-text');
        btn.disabled = true;
        txt.textContent = 'Importando productos, por favor espera...';
        return true;
    }

    function updateBulkToolbar() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkActionsBar.classList.remove('hidden');
            selectedCountBadge.textContent = count;
            selectedCountText.textContent = count === 1 ? 'producto seleccionado' : 'productos seleccionados';
        } else {
            bulkActionsBar.classList.add('hidden');
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkboxes.length > 0 && count === checkboxes.length;
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateBulkToolbar();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkToolbar);
    });

    function clearSelections() {
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
        checkboxes.forEach(cb => cb.checked = false);
        updateBulkToolbar();
    }

    function deleteSingleProduct(productId) {
        if (confirm('¿Estás seguro de eliminar este producto? Se eliminará de forma permanente.')) {
            const form = document.getElementById('single-delete-form');
            form.action = `/admin/products/${productId}`;
            form.submit();
        }
    }
</script>
@endpush
