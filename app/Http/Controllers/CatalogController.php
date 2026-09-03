<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        $query = Product::with('category')->where('is_active', true)->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categoria')) {
            $categorySlug = $request->get('categoria');
            $selectedCat = Category::with('subcategories')->where('slug', $categorySlug)->first();
            if ($selectedCat) {
                $categoryIds = $selectedCat->subcategories->pluck('id')->push($selectedCat->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $banners = collect();
        if (Schema::hasTable('banners')) {
            $banners = Banner::where('is_active', true)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('catalog.index', compact('products', 'categories', 'banners'));
    }

    public function category(string $slug): View
    {
        $category = Category::with(['subcategories', 'parent'])->where('slug', $slug)->firstOrFail();
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        $categoryIds = $category->subcategories->pluck('id')->push($category->id);

        $products = Product::with('category')
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('catalog.category', compact('category', 'categories', 'products'));
    }

    public function show(string $slug): View
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}
