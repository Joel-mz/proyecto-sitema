<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $activeProducts = Product::where('is_active', true)->count();
        $inactiveProducts = $totalProducts - $activeProducts;
        $recentProducts = Product::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'activeProducts', 'inactiveProducts', 'recentProducts'));
    }
}
