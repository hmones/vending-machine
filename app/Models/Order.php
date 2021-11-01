<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function submitOrder(Product $product, int $amount): ?Order
    {
        DB::beginTransaction();

        $order = self::create(['user_id' => auth()->id(), 'product_id' => $product->id, 'amount' => $amount]);
        $userUpdate = auth()->user()->update(['deposit' => ((float) auth()->user()->deposit - ($product->cost * $amount))]);
        $productUpdate = $product->update(['amount_available' => $product->amount_available - $amount]);

        if (! $order || ! $userUpdate || ! $productUpdate) {
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $order;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
