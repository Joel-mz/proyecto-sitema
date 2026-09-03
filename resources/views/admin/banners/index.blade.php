@extends('layouts.admin')

@section('title', 'Banners del Slider')
@section('header_title', 'Gestión de Banners y Slider')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Banners del Slider Principal</h2>
            <p class="text-xs text-slate-500 mt-1">Administra las imágenes y promociones que aparecen en el carrusel superior del catálogo web.</p>
        </div>
        <a href="{{ route('banners.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Agregar Banner</span>
        </a>
    </div>

    <!-- Alert / Tips Info -->
    <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-blue-900 leading-relaxed">
            <strong>¿Cómo funciona el slider?</strong> Todos los banners activos se reproducirán automáticamente en el carrusel superior. Los visitantes podrán avanzar o retroceder con las flechas y puntos de navegación.
        </p>
    </div>

    <!-- Banners Table / List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($banners->isEmpty())
            <div class="py-16 text-center space-y-4 px-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">No hay banners registrados en el slider</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Agrega tu primer banner publicitario o promocional para que aparezca en el carrusel de la portada.</p>
                </div>
                <a href="{{ route('banners.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    <span>Crear Primer Banner</span>
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 text-center w-14">Orden</th>
                            <th class="py-3.5 px-4 w-36">Imagen</th>
                            <th class="py-3.5 px-4">Título / Textos</th>
                            <th class="py-3.5 px-4">Botón & Enlace</th>
                            <th class="py-3.5 px-4 text-center">Estado</th>
                            <th class="py-3.5 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($banners as $banner)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Order Position -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-black text-xs">
                                        {{ $banner->order }}
                                    </span>
                                </td>

                                <!-- Image Thumbnail -->
                                <td class="py-3.5 px-4">
                                    <div class="w-28 h-16 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center p-1 shadow-xs">
                                        @if($banner->image && file_exists(public_path('storage/' . $banner->image)))
                                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="max-w-full max-h-full object-contain">
                                        @else
                                            <span class="text-[10px] text-slate-400">Sin archivo</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Title, Subtitle, Badge -->
                                <td class="py-3.5 px-4">
                                    <div class="space-y-1 max-w-md">
                                        @if($banner->badge)
                                            <span class="inline-block px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-bold text-[10px] uppercase tracking-wider">
                                                {{ $banner->badge }}
                                            </span>
                                        @endif
                                        <p class="font-bold text-slate-900 text-sm">
                                            {{ $banner->title ?: 'Sin título especificado' }}
                                        </p>
                                        @if($banner->subtitle)
                                            <p class="text-slate-500 text-[11px] line-clamp-1">
                                                {{ $banner->subtitle }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Button & Link -->
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-700 block">{{ $banner->button_text ?: 'Ver Productos' }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $banner->button_link ?: '#productos' }}</span>
                                </td>

                                <!-- Active Status -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($banner->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-bold text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('banners.edit', $banner) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Editar Banner">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este banner del slider?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar Banner">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($banners->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $banners->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
