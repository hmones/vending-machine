<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStore;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create,App\Models\Order');
    }

    public function store(OrderStore $request): JsonResponse
    {
        $product = Product::findOrFail($request->input('product_id'));
        $amount = $request->input('amount', 0);
        $message = $this->checkOrderIssues($amount, $product);

        if ($message) {
            return response()->json(compact('message'), JsonResponse::HTTP_FORBIDDEN);
        }

        $order = Order::submitOrder($product, $amount);

        if (! $order) {
            return response()->json(['message' => 'An error occurred while submitting your order, try again later!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(new OrderResource($order), JsonResponse::HTTP_CREATED);
    }

    protected function checkOrderIssues($amount, $product): ?string
    {
        if ($amount > $product->amount_available) {
            return 'The amount for product you specified is unavailable!';
        }

        if (($product->cost * $amount) > (float) auth()->user()->deposit) {
            return 'Please deposit more money into your account to order this product!';
        }

        return false;
    }
}
