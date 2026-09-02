@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard General')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card 1: Total Productos -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Productos</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalProducts }}</h3>
            </div>
        </div>

        <!-- Card 2: Total Categorías -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Categorías</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalCategories }}</h3>
            </div>
        </div>

        <!-- Card 3: Productos Activos -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Productos Activos</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $activeProducts }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Actions Banner -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl p-6 md:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-blue-900/10">
        <div class="space-y-2 text-center md:text-left">
            <h2 class="text-xl font-bold">Catálogo Virtual en Línea</h2>
            <p class="text-slate-300 text-sm max-w-xl">Administra tus categorías, mantén actualizados los precios de venta y precios mínimos de tus productos, y genera tu catálogo en PDF listo para compartir.</p>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-3 flex-shrink-0">
            <a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg transition">
                + Agregar Producto
            </a>
            <a href="{{ route('admin.pdf') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-xl backdrop-blur-md transition flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Descargar PDF</span>
            </a>
        </div>
    </div>

    <!-- Recent Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Últimos Productos Agregados</h3>
                <p class="text-xs text-slate-400 mt-0.5">Productos recientemente registrados en el sistema con sus precios</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Ver todos &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Producto</th>
                        <th class="py-3.5 px-6">Categoría</th>
                        <th class="py-3.5 px-6">Precio Venta</th>
                        <th class="py-3.5 px-6">Precio Mínimo</th>
                        <th class="py-3.5 px-6">Estado</th>
                        <th class="py-3.5 px-6 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($recentProducts as $prod)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200/60 flex items-center justify-center">
                                        @if($prod->image)
                                            <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $prod->name }}</p>
                                        <p class="text-xs text-slate-400">{{ Str::limit($prod->description, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $prod->category->name ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold text-blue-600">
                                S/ {{ number_format($prod->price, 2) }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-amber-700">
                                {{ $prod->min_price ? 'S/ ' . number_format($prod->min_price, 2) : '-' }}
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
                                <a href="{{ route('products.edit', $prod) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                                No hay productos registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
