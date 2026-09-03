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
        <div class="flex items-center gap-2.5">
            @if($products->total() > 0)
                <form action="{{ route('products.deleteAll') }}" method="POST" onsubmit="return confirm('⚠️ ¿ESTÁS SEGURO DE ELIMINAR TODOS LOS PRODUCTOS? Esta acción no se puede deshacer y borrará todo el inventario.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs sm:text-sm font-semibold rounded-xl border border-rose-200 transition">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Eliminar Todo</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
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
</div>
@endsection

@push('scripts')
<script>
    const selectAllCheckbox = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const bulkActionsBar = document.getElementById('bulk-actions-bar');
    const selectedCountBadge = document.getElementById('selected-count-badge');
    const selectedCountText = document.getElementById('selected-count-text');

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
