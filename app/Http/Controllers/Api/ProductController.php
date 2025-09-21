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
    
        // Search varsa hem name hem description'da ara, küçük harfe çevir
        if ($request->has('search') && $request->search) {
            $search = mb_strtolower($request->search, 'UTF-8'); // Türkçe karakter için mb_strtolower
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }
    
        // Sayfalama, page parametresi backend tarafından otomatik algılanır
        $products = $query->paginate(20);
    
        // JSON dönüş, frontend paginated veriyi last_page, next_page_url vs ile alabilir
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
