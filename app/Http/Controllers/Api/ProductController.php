<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('type', 'final'); // sadece final ürünler

        // Search varsa hem name hem description'da ara
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Infinity scroll (sayfa sayfa)
        $products = $query->paginate(20); // sayfa başı 20

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        if ($product->type !== 'final') {
            abort(403, 'Bu ürüne erişim yok.');
        }

        return new ProductResource($product);
    }
}
