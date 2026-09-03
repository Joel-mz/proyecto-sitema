<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('order', 'asc')->orderBy('id', 'asc')->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
        ], [
            'image.required' => 'Debes seleccionar una imagen para el banner.',
            'image.image' => 'El archivo debe ser una imagen válida (JPG, PNG, WEBP, SVG).',
            'image.max' => 'La imagen no puede pesar más de 10MB.',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0) ?? 0;
        $validated['button_text'] = $request->input('button_text') ?: 'Ver Productos';
        $validated['button_link'] = $request->input('button_link') ?: '#productos';

        $imagePath = $request->file('image')->store('banners', 'public');
        $validated['image'] = $imagePath;

        Banner::create($validated);

        return redirect()->route('banners.index')->with('success', 'Banner agregado correctamente al slider.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
        ], [
            'image.image' => 'El archivo debe ser una imagen válida (JPG, PNG, WEBP, SVG).',
            'image.max' => 'La imagen no puede pesar más de 10MB.',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0) ?? 0;
        $validated['button_text'] = $request->input('button_text') ?: 'Ver Productos';
        $validated['button_link'] = $request->input('button_link') ?: '#productos';

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $imagePath = $request->file('image')->store('banners', 'public');
            $validated['image'] = $imagePath;
        }

        $banner->update($validated);

        return redirect()->route('banners.index')->with('success', 'Banner del slider actualizado correctamente.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner eliminado correctamente.');
    }
}
