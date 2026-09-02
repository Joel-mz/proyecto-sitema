@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header_title', 'Editar Usuario')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb / Back button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Volver a la lista de usuarios</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-800">Modificar Usuario: {{ $user->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Actualiza los datos personales, el rol de acceso o la contraseña.</p>
            </div>
            <div class="w-10 h-10 rounded-xl {{ $user->isAdmin() ? 'bg-indigo-600' : 'bg-emerald-600' }} text-white font-bold flex items-center justify-center text-sm shadow-sm">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name and Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nombre Completo <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-rose-400 bg-rose-50/20 @enderror">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Correo Electrónico <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-rose-400 bg-rose-50/20 @enderror">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role Selector -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Rol y Nivel de Permisos <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Option Editor -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ old('role', $user->role) === 'editor' ? 'border-emerald-500 bg-emerald-50/30' : 'border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-800 text-sm">Editor</span>
                            </div>
                            <input type="radio" name="role" value="editor" {{ old('role', $user->role) === 'editor' ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        </div>
                        <p class="text-xs text-slate-500">Puede gestionar productos, categorías, catálogo y descargar reportes PDF.</p>
                    </label>

                    <!-- Option Admin -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ old('role', $user->role) === 'admin' ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-800 text-sm">Administrador</span>
                            </div>
                            <input type="radio" name="role" value="admin" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                        </div>
                        <p class="text-xs text-slate-500">Acceso total al sistema, gestión de usuarios, configuración global y catálogo completo.</p>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Change Section (Optional) -->
            <div class="pt-6 border-t border-slate-100">
                <div class="mb-4">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Cambiar Contraseña (Opcional)</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Deja estos campos vacíos si no deseas modificar la contraseña del usuario.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-medium text-slate-700 mb-2">
                            Nueva Contraseña
                        </label>
                        <input type="password" id="password" name="password" minlength="6" placeholder="Dejar en blanco para conservar" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-rose-400 bg-rose-50/20 @enderror">
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-slate-700 mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" minlength="6" placeholder="Repite la nueva contraseña" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Actualizar Usuario</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
