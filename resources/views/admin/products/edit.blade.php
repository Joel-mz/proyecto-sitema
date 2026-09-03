@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Editar Producto</h1>
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Volver a Productos
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                
                <!-- Left Column: Imagen del producto (Archivo o URL) -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-700">Imagen del producto</label>
                    <div class="w-full aspect-square rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center p-4 overflow-hidden relative group">
                        @if($product->image_url)
                            <img id="image-preview" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                            <svg id="placeholder-icon" class="hidden w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @else
                            <svg id="placeholder-icon" class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <img id="image-preview" src="#" alt="Vista previa" class="hidden max-h-full max-w-full object-contain">
                        @endif
                    </div>

                    <!-- Opción 1: Subir Archivo -->
                    <div class="text-center space-y-1">
                        <label for="image" class="inline-block w-full py-2 px-3 bg-blue-50 hover:bg-blue-100 text-[#0052cc] text-xs font-bold rounded-xl cursor-pointer border border-blue-200 transition">
                            📁 Cambiar archivo
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" class="hidden">
                        <span id="file-name-display" class="block text-[10px] text-slate-400 truncate"></span>
                    </div>

                    <!-- Separador O -->
                    <div class="relative flex py-1 items-center">
                        <div class="flex-grow border-t border-slate-200"></div>
                        <span class="flex-shrink mx-2 text-[10px] text-slate-400 font-bold uppercase">O mediante enlace</span>
                        <div class="flex-grow border-t border-slate-200"></div>
                    </div>

                    <!-- Opción 2: Pegar URL de Imagen -->
                    <div>
                        <label for="image_url" class="block text-[11px] font-semibold text-slate-600 mb-1">
                            URL de imagen externa (Web / CDN):
                        </label>
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url', str_starts_with($product->image ?? '', 'http') ? $product->image : '') }}"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="https://ejemplo.com/foto-producto.jpg">
                        <p class="text-[10px] text-slate-400 mt-1">Pega el link directo de una imagen de Google, Amazon o tu proveedor.</p>
                        @error('image_url')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($product->image)
                        <div class="pt-1">
                            <label class="inline-flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                <span>Eliminar imagen actual</span>
                            </label>
                        </div>
                    @endif

                    @error('image')
                        <p class="text-rose-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Right Column: Form Fields (Exact Mockup) -->
                <div class="md:col-span-2 space-y-4">
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nombre <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoría / Subcategoría -->
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1">Categoría / Subcategoría <span class="text-rose-500">*</span></label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white font-medium">
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" class="font-bold text-slate-900 bg-slate-50" {{ old('category_id', $product->category_id) == $parent->id ? 'selected' : '' }}>
                                    📁 {{ $parent->name }}
                                </option>
                                @foreach($parent->subcategories as $sub)
                                    <option value="{{ $sub->id }}" class="text-slate-600" {{ old('category_id', $product->category_id) == $sub->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $sub->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio y Estado Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-xs font-bold text-slate-700 mb-1">Precio (S/) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', $product->price) }}" required
                                class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold">
                            @error('price')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="block text-xs font-bold text-slate-700 mb-1">Estado</label>
                            <select name="is_active" id="is_active"
                                class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('is_active', $product->is_active) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-[#0052cc] hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('image');
    const urlInput = document.getElementById('image_url');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('placeholder-icon');
    const fileNameDisplay = document.getElementById('file-name-display');

    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                if (fileNameDisplay) fileNameDisplay.textContent = 'Seleccionado: ' + file.name;
                if (urlInput) urlInput.value = '';
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (urlInput) {
        urlInput.addEventListener('input', function() {
            const url = this.value.trim();
            if (url) {
                if (fileInput) fileInput.value = '';
                if (fileNameDisplay) fileNameDisplay.textContent = '';
                preview.src = url;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
        });
    }
</script>
@endsection
