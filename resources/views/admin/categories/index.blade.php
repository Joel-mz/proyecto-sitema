@extends('layouts.admin')

@section('title', 'Categorías')
@section('header_title', 'Administración de Categorías')

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Listado de Categorías</h2>
            <p class="text-xs text-slate-500 mt-0.5">Organiza tus productos por familias y categorías</p>
        </div>
        <div class="flex items-center gap-2.5">
            @if($categories->total() > 0)
                <form action="{{ route('categories.deleteAll') }}" method="POST" onsubmit="return confirm('⚠️ ¿ESTÁS SEGURO DE ELIMINAR TODAS LAS CATEGORÍAS? Esta acción no se puede deshacer y también eliminará o desvinculará sus productos asociados.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs sm:text-sm font-semibold rounded-xl border border-rose-200 transition">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Eliminar Todo</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Nueva Categoría / Sub</span>
            </a>
        </div>
    </div>

    <!-- Bulk Action Toolbar (Active when at least 1 checkbox selected) -->
    <form id="bulk-delete-form" action="{{ route('categories.bulkDelete') }}" method="POST">
        @csrf

        <div id="bulk-actions-bar" class="hidden mb-4 p-4 bg-slate-900 text-white rounded-2xl shadow-xl flex items-center justify-between transition transform duration-200">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-xs font-bold" id="selected-count-badge">0</span>
                <span class="text-xs sm:text-sm font-medium" id="selected-count-text">categorías seleccionadas</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="clearSelections()" class="px-3 py-1.5 text-xs text-slate-300 hover:text-white transition">
                    Deseleccionar
                </button>
                <button type="submit" onclick="return confirm('¿Estás seguro de eliminar las categorías seleccionadas? Si tienen productos asociados, también se verán afectados.');" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow transition flex items-center gap-1.5">
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
                <table class="w-full min-w-[650px] text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">
                                <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" title="Seleccionar todo">
                            </th>
                            <th class="py-3.5 px-4">Tipo / Jerarquía</th>
                            <th class="py-3.5 px-4">Nombre</th>
                            <th class="py-3.5 px-4">Slug (URL)</th>
                            <th class="py-3.5 px-4">Descripción</th>
                            <th class="py-3.5 px-4">Total Productos</th>
                            <th class="py-3.5 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/50 transition {{ $cat->parent_id ? 'bg-slate-50/30' : '' }}">
                                <td class="py-4 px-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $cat->id }}" class="category-checkbox w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="py-4 px-4">
                                    @if($cat->parent)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                            <span>↳ Sub de: <strong>{{ $cat->parent->name }}</strong></span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span>📁 Principal</span>
                                            @if($cat->subcategories->isNotEmpty())
                                                <span class="text-[10px] text-blue-500 font-normal">({{ $cat->subcategories->count() }} subs)</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-800">
                                    {{ $cat->name }}
                                </td>
                                <td class="py-4 px-4 text-xs font-mono text-blue-600 bg-blue-50/50 px-2 py-1 rounded inline-block my-3">
                                    /categoria/{{ $cat->slug }}
                                </td>
                                <td class="py-4 px-4 text-slate-500 text-xs max-w-xs truncate">
                                    {{ $cat->description ?: 'Sin descripción' }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $cat->products_count }} productos
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('categories.create', ['parent_id' => $cat->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-lg transition" title="Agregar subcategoría dentro de {{ $cat->name }}">
                                            <span>+ Sub</span>
                                        </a>

                                        <a href="{{ route('categories.edit', $cat) }}" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <button type="button" onclick="deleteSingleCategory('{{ $cat->id }}')" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Eliminar">
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
                                    No se encontraron categorías registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $categories->links() }}
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
    const checkboxes = document.querySelectorAll('.category-checkbox');
    const bulkActionsBar = document.getElementById('bulk-actions-bar');
    const selectedCountBadge = document.getElementById('selected-count-badge');
    const selectedCountText = document.getElementById('selected-count-text');

    function updateBulkToolbar() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkActionsBar.classList.remove('hidden');
            selectedCountBadge.textContent = count;
            selectedCountText.textContent = count === 1 ? 'categoría seleccionada' : 'categorías seleccionadas';
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

    function deleteSingleCategory(categoryId) {
        if (confirm('¿Estás seguro de eliminar esta categoría? Si tiene productos asociados, también se verán afectados.')) {
            const form = document.getElementById('single-delete-form');
            form.action = `/admin/categories/${categoryId}`;
            form.submit();
        }
    }
</script>
@endpush
