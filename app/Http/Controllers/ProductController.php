<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $products = Product::with('category')->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->orderBy('name')->paginate(25)->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'pic' => ['required', 'integer', 'min:1'],
            'opening_stock_ctn' => ['nullable', 'numeric', 'min:0'],
            'opening_stock_pcs' => ['nullable', 'numeric', 'min:0'],
            'low_stock_threshold_ctn' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['opening_stock_ctn'] = $data['opening_stock_ctn'] ?? 0;
        $data['opening_stock_pcs'] = $data['opening_stock_pcs'] ?? 0;
        $data['low_stock_threshold_ctn'] = $data['low_stock_threshold_ctn'] ?? 5;
        $data['cost_price'] = $data['cost_price'] ?? 0;

        return $data;
    }
}
