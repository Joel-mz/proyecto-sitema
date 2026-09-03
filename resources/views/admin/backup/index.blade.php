@extends('layouts.admin')

@section('title', 'Copias de Seguridad y Restauración')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Copia de Seguridad & Restauración</h1>
            <p class="text-xs text-slate-500 mt-1">
                Descarga un respaldo completo de tu catálogo (datos e imágenes) o restaura tu información en cualquier momento.
            </p>
        </div>
    </div>

    <!-- System Data Overview Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">📁</div>
            <div>
                <span class="block text-lg font-black text-slate-900">{{ $stats['categories'] }}</span>
                <span class="text-[11px] text-slate-400 font-semibold">Categorías</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">📦</div>
            <div>
                <span class="block text-lg font-black text-slate-900">{{ $stats['products'] }}</span>
                <span class="text-[11px] text-slate-400 font-semibold">Productos</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">🖼️</div>
            <div>
                <span class="block text-lg font-black text-slate-900">{{ $stats['banners'] }}</span>
                <span class="text-[11px] text-slate-400 font-semibold">Banners</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">📑</div>
            <div>
                <span class="block text-lg font-black text-slate-900">{{ $stats['quotes'] }}</span>
                <span class="text-[11px] text-slate-400 font-semibold">Cotizaciones</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-3 col-span-2 sm:col-span-1">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">🛒</div>
            <div>
                <span class="block text-lg font-black text-slate-900">{{ $stats['orders'] }}</span>
                <span class="text-[11px] text-slate-400 font-semibold">Pedidos</span>
            </div>
        </div>
    </div>

    <!-- Grid: Export & Restore Panels -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Panel 1: DESCARGAR COPIA DE SEGURIDAD (EXPORT) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between">
            <div class="p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">1. Generar y Descargar Copia</h2>
                        <p class="text-xs text-slate-400">Guarda una copia de seguridad en tu computadora o teléfono.</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs text-slate-600">
                    <p class="leading-relaxed">
                        Este archivo incluye todas tus <strong>categorías, subcategorías, productos con sus precios y stock, banners del slider, datos de empresa, cotizaciones y pedidos</strong>.
                    </p>

                    <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-3 space-y-1.5 text-blue-800 text-[11px]">
                        <p class="font-bold flex items-center gap-1.5">
                            <span>💡 Recomendación:</span>
                        </p>
                        <p>Descarga la opción <strong>Copia Completa (ZIP)</strong> para que además de los datos se incluyan todas las imágenes subidas de tus productos.</p>
                    </div>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="p-5 sm:p-6 bg-slate-50 border-t border-slate-100 space-y-2.5">
                <a href="{{ route('admin.backup.export', ['type' => 'zip']) }}"
                   class="w-full py-3 px-4 bg-[#0052cc] hover:bg-blue-700 active:scale-98 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Descargar Copia Completa (.ZIP + Fotos)</span>
                </a>

                <a href="{{ route('admin.backup.export', ['type' => 'json']) }}"
                   class="w-full py-2.5 px-4 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 active:scale-98 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Descargar Copia Rápida (.JSON)</span>
                </a>
            </div>
        </div>

        <!-- Panel 2: SUBIR Y RESTAURAR COPIA (RESTORE) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between">
            <form action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data" class="flex flex-col justify-between h-full" onsubmit="return confirmRestore(event)">
                @csrf
                <div class="p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">2. Subir y Restablecer Todo</h2>
                            <p class="text-xs text-slate-400">Restaura tus productos, categorías y fotos desde un archivo.</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700">
                            Seleccionar Archivo de Respaldo (.ZIP o .JSON):
                        </label>

                        <div class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-4 text-center cursor-pointer transition bg-slate-50/50 relative">
                            <input type="file" name="backup_file" id="backup_file" accept=".zip,.json" required
                                   onchange="updateFileName(this)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="space-y-1.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <span id="file-label" class="block text-xs font-bold text-slate-700">Haz clic para elegir archivo .ZIP o .JSON</span>
                                <span class="block text-[10px] text-slate-400">Tamaño máximo: 100 MB</span>
                            </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-3 text-[11px] text-amber-800 space-y-1">
                            <p class="font-bold flex items-center gap-1.5">
                                <span>⚠️ Importante al restaurar:</span>
                            </p>
                            <p>Esta acción actualizará e insertará todos los registros del archivo en el sistema. Asegúrate de haber descargado una copia previa si deseas conservarla.</p>
                        </div>
                    </div>
                </div>

                <!-- Restore Action Button -->
                <div class="p-5 sm:p-6 bg-slate-50 border-t border-slate-100">
                    <button type="submit" id="btn-restore" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span id="btn-restore-text">Restaurar Copia de Seguridad</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Panel 3: ZONA DE LIMPIEZA / RESET (FACTORY RESET) -->
    <div class="bg-white rounded-2xl border border-rose-200 shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 space-y-4">
            <div class="flex items-center gap-3 border-b border-rose-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-rose-900">3. Zona de Peligro: Limpiar / Restablecer Catálogo</h2>
                    <p class="text-xs text-rose-500">Elimina todos los productos, categorías, cotizaciones y pedidos para iniciar desde cero.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Si deseas limpiar todos los datos de prueba o comenzar un catálogo nuevo desde cero, puedes restablecer las tablas. Tu cuenta de Administrador y la configuración de tu empresa <strong>se mantendrán intactas</strong>.
            </p>

            <form action="{{ route('admin.backup.reset') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2" onsubmit="return confirmReset(event)">
                @csrf
                <div class="flex-1">
                    <input type="text" name="confirm_text" id="confirm_reset_input" placeholder="Escribe RESTABLECER para confirmar" required
                           class="w-full px-3.5 py-2.5 text-xs border border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none uppercase font-mono font-bold">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-98 text-white rounded-xl font-bold text-xs shadow-md transition whitespace-nowrap cursor-pointer">
                    Restablecer Catálogo a Cero
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const label = document.getElementById('file-label');
        if (input.files && input.files[0]) {
            label.textContent = '📄 Archivo seleccionado: ' + input.files[0].name + ' (' + (input.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
            label.className = 'block text-xs font-bold text-emerald-700';
        }
    }

    function confirmRestore(e) {
        const fileInput = document.getElementById('backup_file');
        if (!fileInput.files || !fileInput.files[0]) {
            alert('Por favor selecciona un archivo .ZIP o .JSON primero.');
            e.preventDefault();
            return false;
        }

        if (!confirm('¿Estás seguro de que deseas RESTABLECER y RESTAURAR la información desde este archivo? Los registros existentes se actualizarán con los datos de la copia de seguridad.')) {
            e.preventDefault();
            return false;
        }

        const btn = document.getElementById('btn-restore');
        const txt = document.getElementById('btn-restore-text');
        btn.disabled = true;
        txt.textContent = 'Restaurando información, por favor espera...';
        return true;
    }

    function confirmReset(e) {
        const input = document.getElementById('confirm_reset_input').value.trim();
        if (input !== 'RESTABLECER') {
            alert('Debes escribir la palabra RESTABLECER exactamente para confirmar.');
            e.preventDefault();
            return false;
        }

        if (!confirm('¡ADVERTENCIA CRÍTICA! Se borrarán todos los productos, categorías, banners y pedidos actuales. ¿Estás absolutamente seguro de continuar?')) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endsection
