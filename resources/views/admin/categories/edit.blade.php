@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('header_title', 'Editar Categoría')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Volver a Categorías</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <h2 class="text-lg font-bold text-slate-800 mb-6">Modificar Categoría: {{ $category->name }}</h2>

        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Categoría Padre (Para subcategorías) -->
            <div>
                <label for="parent_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Categoría Principal / Padre <span class="text-xs font-normal text-slate-400 lowercase">(opcional)</span>
                </label>
                <select name="parent_id" id="parent_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm bg-white font-medium">
                    <option value="">-- Ninguna (Es una Categoría Principal) --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            📁 {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Si seleccionas una categoría padre, se guardará como <strong>Subcategoría</strong>.</p>
                @error('parent_id')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Nombre de la Categoría / Subcategoría <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                @error('name')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Descripción (Opcional)</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Actualizar Categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
