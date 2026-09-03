<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with(['parent', 'subcategories'])
            ->withCount('products')
            ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, name')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parentCategories = Category::with('parent')->orderBy('name')->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$originalSlug}-{$counter}";
            $counter++;
        }

        Category::create($validated);

        $msg = ! empty($validated['parent_id']) ? 'Subcategoría creada exitosamente.' : 'Categoría creada exitosamente.';

        return redirect()->route('categories.index')->with('success', $msg);
    }

    public function edit(Category $category): View
    {
        $parentCategories = Category::with('parent')->where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id', Rule::notIn([$category->id])],
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        $category->update($validated);

        $msg = ! empty($validated['parent_id']) ? 'Subcategoría actualizada exitosamente.' : 'Categoría actualizada exitosamente.';

        return redirect()->route('categories.index')->with('success', $msg);
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:categories,id'],
        ]);

        $count = Category::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('categories.index')->with('success', "Se eliminaron {$count} categorías seleccionadas correctamente.");
    }

    public function deleteAll(): RedirectResponse
    {
        $count = Category::count();
        Category::query()->delete();

        return redirect()->route('categories.index')->with('success', "Todas las {$count} categorías han sido eliminadas correctamente.");
    }
}
