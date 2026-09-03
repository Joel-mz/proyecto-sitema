<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'min_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99', 'lte:price'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ], [
            'min_price.lte' => 'El precio mínimo no puede ser mayor que el precio de venta.',
            'image_url.url' => 'La URL de la imagen debe ser un enlace válido (ej. https://ejemplo.com/foto.jpg).',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = Str::slug($validated['name']);

        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$originalSlug}-{$counter}";
            $counter++;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->input('image_url');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'min_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99', 'lte:price'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean'],
        ], [
            'min_price.lte' => 'El precio mínimo no puede ser mayor que el precio de venta.',
            'image_url.url' => 'La URL de la imagen debe ser un enlace válido (ej. https://ejemplo.com/foto.jpg).',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } elseif ($request->filled('image_url')) {
            if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->input('image_url');
        } elseif ($request->boolean('remove_image')) {
            if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:products,id'],
        ]);

        $products = Product::whereIn('id', $validated['ids'])->get();
        $count = $products->count();

        foreach ($products as $prod) {
            if ($prod->image && Storage::disk('public')->exists($prod->image)) {
                Storage::disk('public')->delete($prod->image);
            }
            $prod->delete();
        }

        return redirect()->route('products.index')->with('success', "Se eliminaron {$count} productos seleccionados correctamente.");
    }

    public function deleteAll(): RedirectResponse
    {
        $products = Product::all();
        $count = $products->count();

        foreach ($products as $prod) {
            if ($prod->image && Storage::disk('public')->exists($prod->image)) {
                Storage::disk('public')->delete($prod->image);
            }
            $prod->delete();
        }

        return redirect()->route('products.index')->with('success', "Todos los {$count} productos han sido eliminados correctamente.");
    }
}
