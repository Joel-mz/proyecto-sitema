@extends('layouts.admin')

@section('title', 'Agregar Producto')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Agregar Producto</h1>
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Volver a Productos
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                
                <!-- Left Column: Imagen del producto (Exact Mockup Box) -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-700">Imagen del producto</label>
                    <div class="w-full aspect-square rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center p-4 overflow-hidden relative group">
                        <svg id="placeholder-icon" class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <img id="image-preview" src="#" alt="Vista previa" class="hidden max-h-full max-w-full object-contain">
                    </div>

                    <div class="text-center">
                        <label for="image" class="inline-block w-full py-2 px-4 bg-blue-50 hover:bg-blue-100 text-[#0052cc] text-xs font-bold rounded-xl cursor-pointer border border-blue-200 transition">
                            Seleccionar imagen
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" class="hidden">
                    </div>
                    @error('image')
                        <p class="text-rose-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Right Column: Form Fields (Exact Mockup) -->
                <div class="md:col-span-2 space-y-4">
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nombre <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Ej. Laptop Lenovo Intel Core i5">
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoría -->
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1">Categoría <span class="text-rose-500">*</span></label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
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
                            class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Laptop Lenovo equipada con procesador Intel Core i5, 8GB RAM, SSD 480GB...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio y Estado Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-xs font-bold text-slate-700 mb-1">Precio (S/) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price') }}" required
                                class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold"
                                placeholder="2499.00">
                            @error('price')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="block text-xs font-bold text-slate-700 mb-1">Estado</label>
                            <select name="is_active" id="is_active"
                                class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactivo</option>
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
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('placeholder-icon');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
