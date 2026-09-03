@extends('layouts.admin')

@section('title', 'Datos de la Empresa')
@section('header_title', 'Configuración de la Empresa')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h2 class="text-lg font-bold text-slate-800">Perfil del Negocio y Contacto</h2>
            <p class="text-xs text-slate-500 mt-0.5">Esta información y logo aparecerán en la cabecera, pie de página del catálogo web y en el PDF descargable.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Logo de la Empresa -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Logo de la Empresa (JPG, JPEG, PNG, WEBP, SVG)
                </label>
                <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50">
                    <div id="logo-preview-container" class="w-36 h-24 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm p-2">
                        @if($setting->logo && file_exists(public_path('storage/' . $setting->logo)))
                            <img id="logo-preview" src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->name }}" class="max-w-full max-h-full object-contain">
                            <svg id="logo-placeholder" class="hidden w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @else
                            <svg id="logo-placeholder" class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <img id="logo-preview" src="#" alt="Vista previa" class="hidden max-w-full max-h-full object-contain">
                        @endif
                    </div>
                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                        <p class="text-xs text-slate-400">Recomendado formato horizontal o transparente (PNG/SVG). Máx 5MB.</p>

                        @if($setting->logo)
                            <label class="inline-flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer pt-1">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                <span>Eliminar logo actual y usar texto</span>
                            </label>
                        @endif
                    </div>
                </div>
                @error('logo')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre de la Empresa -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Nombre de la Empresa / Negocio <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $setting->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm font-semibold"
                    placeholder="Ej. TechStore Perú S.A.C.">
                @error('name')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Teléfonos / WhatsApp -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                        Número de Teléfono / Central
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $setting->phone) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                        placeholder="Ej. (01) 456-7890 o +51 987 654 321">
                    @error('phone')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                        WhatsApp de Contacto (Solo números para enlace)
                    </label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                        placeholder="Ej. 51987654321 (con código de país)">
                    <p class="text-[11px] text-slate-400 mt-1">Permite a los clientes enviar mensajes directos con 1 clic.</p>
                    @error('whatsapp')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Correo Electrónico de Contacto
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', $setting->email) }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                    placeholder="Ej. ventas@miempresa.com">
                @error('email')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ubicación Física -->
            <div class="space-y-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Ubicación y Local</h3>

                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-600 mb-1">
                        Dirección / Lugar donde están ubicados
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address', $setting->address) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                        placeholder="Ej. Av. Garcilaso de la Vega 1250, C.C. CyberPlaza Tienda 204">
                    @error('address')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="city_province" class="block text-xs font-semibold text-slate-600 mb-1">
                            Provincia / Ciudad
                        </label>
                        <input type="text" name="city_province" id="city_province" value="{{ old('city_province', $setting->city_province) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                            placeholder="Ej. Lima, Arequipa, Trujillo, Cusco">
                        @error('city_province')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="region" class="block text-xs font-semibold text-slate-600 mb-1">
                            Región / Departamento
                        </label>
                        <input type="text" name="region" id="region" value="{{ old('region', $setting->region) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                            placeholder="Ej. Lima, Arequipa, La Libertad, Piura">
                        @error('region')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Banner Principal / Portada del Catálogo -->
            <div class="space-y-4 p-5 rounded-2xl bg-blue-50/40 border border-blue-100">
                <div class="flex items-center gap-2 border-b border-blue-100 pb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Banner Principal del Catálogo (Portada / Hero)</h3>
                        <p class="text-xs text-slate-500">Personaliza la imagen destacada y textos del banner superior de la página principal.</p>
                    </div>
                </div>

                <!-- Imagen del Banner -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Imagen Destacada del Banner (JPG, PNG, WEBP, SVG)
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-2xl border-2 border-dashed border-blue-200 bg-white">
                        <div id="hero-preview-container" class="w-48 h-32 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-xs p-2">
                            @if(!empty($setting->hero_image) && file_exists(public_path('storage/' . $setting->hero_image)))
                                <img id="hero-preview" src="{{ asset('storage/' . $setting->hero_image) }}" alt="Banner Hero" class="max-w-full max-h-full object-contain">
                                <div id="hero-placeholder" class="hidden flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-[11px] font-medium">Sin imagen</span>
                                </div>
                            @else
                                <div id="hero-placeholder" class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-[11px] font-medium">Ilustración por defecto</span>
                                </div>
                                <img id="hero-preview" src="#" alt="Vista previa" class="hidden max-w-full max-h-full object-contain">
                            @endif
                        </div>
                        <div class="flex-1 text-center sm:text-left space-y-2">
                            <input type="file" name="hero_image" id="hero_image" accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer">
                            <p class="text-xs text-slate-500">Puedes subir una foto de tus productos estrella, un banner publicitario o ilustración promocional (PNG con transparencia o JPG). Máx 10MB.</p>

                            @if(!empty($setting->hero_image))
                                <label class="inline-flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer pt-1">
                                    <input type="checkbox" name="remove_hero_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    <span>Eliminar imagen actual y volver a la ilustración por defecto</span>
                                </label>
                            @endif
                        </div>
                    </div>
                    @error('hero_image')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Título y Subtítulo del Banner -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="hero_title" class="block text-xs font-semibold text-slate-700 mb-1">
                            Título del Banner
                        </label>
                        <input type="text" name="hero_title" id="hero_title" value="{{ old('hero_title', $setting->hero_title ?? 'Catálogo Virtual de Cómputo y Accesorios') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm font-semibold"
                            placeholder="Ej. Catálogo Virtual de Cómputo y Accesorios">
                        @error('hero_title')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hero_badge" class="block text-xs font-semibold text-slate-700 mb-1">
                            Etiqueta / Badge de Categorías
                        </label>
                        <input type="text" name="hero_badge" id="hero_badge" value="{{ old('hero_badge', $setting->hero_badge ?? 'TECLADOS • LAPTOPS • HARDWARE') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                            placeholder="Ej. TECLADOS • LAPTOPS • HARDWARE">
                        @error('hero_badge')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="hero_subtitle" class="block text-xs font-semibold text-slate-700 mb-1">
                        Subtítulo / Mensaje Promocional
                    </label>
                    <input type="text" name="hero_subtitle" id="hero_subtitle" value="{{ old('hero_subtitle', $setting->hero_subtitle ?? 'Los mejores productos con la mejor calidad y al mejor precio.') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                        placeholder="Ej. Los mejores productos con la mejor calidad y al mejor precio.">
                    @error('hero_subtitle')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Descripción / Lema -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Descripción / Lema de la Empresa
                </label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                    placeholder="Breve reseña sobre la empresa o servicios para el pie de página y catálogo...">{{ old('description', $setting->description) }}</textarea>
                @error('description')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview');
                const placeholder = document.getElementById('logo-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    const heroInput = document.getElementById('hero_image');
    if (heroInput) {
        heroInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('hero-preview');
                    const placeholder = document.getElementById('hero-placeholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
