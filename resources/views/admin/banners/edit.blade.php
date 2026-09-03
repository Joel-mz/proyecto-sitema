@extends('layouts.admin')

@section('title', 'Editar Banner del Slider')
@section('header_title', 'Editar Banner del Slider')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Editar Slide del Banner</h2>
                <p class="text-xs text-slate-500 mt-0.5">Modifica la imagen o la información que se muestra en este slide.</p>
            </div>
            <a href="{{ route('banners.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                ← Volver al listado
            </a>
        </div>

        <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Imagen del Banner -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Imagen del Banner
                </label>
                <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-2xl border-2 border-dashed border-blue-200 bg-blue-50/20">
                    <div id="image-preview-container" class="w-52 h-36 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-xs p-2">
                        @if($banner->image && file_exists(public_path('storage/' . $banner->image)))
                            <img id="image-preview" src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="max-w-full max-h-full object-contain">
                            <div id="image-placeholder" class="hidden flex-col items-center justify-center text-slate-400">
                                <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[11px] font-medium">Sin imagen</span>
                            </div>
                        @else
                            <div id="image-placeholder" class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[11px] font-medium">Sin imagen</span>
                            </div>
                            <img id="image-preview" src="#" alt="Vista previa" class="hidden max-w-full max-h-full object-contain">
                        @endif
                    </div>
                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer">
                        <p class="text-xs text-slate-500">Deja este campo vacío si deseas mantener la imagen actual. Máximo 10MB.</p>
                    </div>
                </div>
                @error('image')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Título y Etiqueta/Badge -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Título del Slide (Opcional)
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm font-semibold"
                        placeholder="Ej. Ofertas en Laptops Gamer">
                    @error('title')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="badge" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Insignia / Etiqueta Superior (Opcional)
                    </label>
                    <input type="text" name="badge" id="badge" value="{{ old('badge', $banner->badge) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                        placeholder="Ej. NUEVO INGRESO • HASTA 20% OFF">
                    @error('badge')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subtítulo / Descripción -->
            <div>
                <label for="subtitle" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Subtítulo / Mensaje Promocional (Opcional)
                </label>
                <textarea name="subtitle" id="subtitle" rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                    placeholder="Ej. Encuentra las mejores marcas de computación con garantía y envío rápido...">{{ old('subtitle', $banner->subtitle) }}</textarea>
                @error('subtitle')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botón y Enlace -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="button_text" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Texto del Botón
                    </label>
                    <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $banner->button_text) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm font-semibold"
                        placeholder="Ej. Ver Productos">
                    @error('button_text')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="button_link" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Enlace de Destino (URL o sección)
                    </label>
                    <input type="text" name="button_link" id="button_link" value="{{ old('button_link', $banner->button_link) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                        placeholder="Ej. #productos o /categoria/laptops">
                    @error('button_link')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Orden y Estado -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <label for="order" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Orden de Visualización
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', $banner->order) }}" min="0"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm font-bold"
                        placeholder="0">
                    <p class="text-[11px] text-slate-400 mt-1">Los números menores aparecen primero (1, 2, 3...).</p>
                    @error('order')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center pt-5 sm:pt-0">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="block text-sm font-bold text-slate-800">Banner Activo</span>
                            <span class="block text-xs text-slate-400">Marcar para mostrarlo en el carrusel público.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('banners.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('image-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
