<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStore;
use App\Http\Requests\ProductUpdate;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:update,product')->only(['update', 'destroy']);
        $this->middleware('can:create,App\Models\Product')->only('store');
    }

    public function index(): JsonResponse
    {
        return response()->json(Product::latest()->limit(100)->get()->toArray());
    }

    public function store(ProductStore $request): JsonResponse
    {
        $product = Product::create($request->safe()->toArray() + ['seller_id' => auth()->id()]);

        return response()->json($product->toArray(), JsonResponse::HTTP_CREATED);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->toArray());
    }

    public function update(Product $product, ProductUpdate $request): JsonResponse
    {
        $product->update($request->safe()->toArray());

        return response()->json($product->toArray());
    }

    public function destroy(Product $product): JsonResponse
    {
        return response()->json($product->delete(), JsonResponse::HTTP_NO_CONTENT);
    }
}
