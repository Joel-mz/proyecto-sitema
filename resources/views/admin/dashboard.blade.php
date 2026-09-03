@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-7xl mx-auto">
    
    <!-- Title -->
    <div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Dashboard</h1>
    </div>

    <!-- 4 Stats Cards (Exact TecnoStore Mockup Style) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- 1. Total Productos -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-1">
            <span class="text-xs font-bold text-slate-700 block">Productos</span>
            <div class="text-2xl sm:text-4xl font-black text-slate-900">
                {{ $totalProducts }}
            </div>
            <span class="text-[11px] text-slate-400 block">Total productos</span>
        </div>

        <!-- 2. Total Categorías -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-1">
            <span class="text-xs font-bold text-slate-700 block">Categorías</span>
            <div class="text-2xl sm:text-4xl font-black text-slate-900">
                {{ $totalCategories }}
            </div>
            <span class="text-[11px] text-slate-400 block">Total categorías</span>
        </div>

        <!-- 3. Productos Activos (Green) -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-1">
            <span class="text-xs font-bold text-slate-700 block">Productos Activos</span>
            <div class="text-2xl sm:text-4xl font-black text-emerald-600">
                {{ $activeProducts }}
            </div>
            <span class="text-[11px] text-slate-400 block">Activos</span>
        </div>

        <!-- 4. Productos Inactivos (Red) -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-1">
            <span class="text-xs font-bold text-slate-700 block">Productos Inactivos</span>
            <div class="text-2xl sm:text-4xl font-black text-rose-600">
                {{ $inactiveProducts }}
            </div>
            <span class="text-[11px] text-slate-400 block">Inactivos</span>
        </div>

    </div>

    <!-- Productos Recientes Table Card (Exact TecnoStore Mockup Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
        <h2 class="text-sm sm:text-base font-bold text-slate-900">Productos recientes</h2>

        <div class="overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
            <table class="w-full min-w-[520px] text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-y border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Producto</th>
                        <th class="py-3 px-4">Categoría</th>
                        <th class="py-3 px-4">Precio</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentProducts as $product)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $product->name }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $product->category->name ?? 'Sin categoría' }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                S/ {{ number_format($product->price, 2) }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($product->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-[10px]">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">
                                No hay productos registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Ver Todos Button (Centered) -->
        <div class="pt-3 text-center">
            <a href="{{ route('products.index') }}" class="inline-block px-6 py-2 bg-[#0052cc] hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm transition">
                Ver todos
            </a>
        </div>
    </div>

</div>
@endsection
