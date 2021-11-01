<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_non_registered_user_can_not_order(): void
    {
        $this->postJson(route('buy'))->assertUnauthorized();
    }

    public function test_seller_can_not_order(): void
    {
        $seller = User::factory()->seller()->create();
        $this->actingAs($seller)->postJson(route('buy'))->assertForbidden();
    }

    public function test_buyer_can_order(): void
    {
        $buyer = User::factory()->buyer()->create(['deposit' => 17.6]);
        $product = Product::factory()->create(['amount_available' => 5, 'cost' => 3.15]);

        $this->actingAs($buyer)
            ->postJson(route('buy'), ['product_id' => $product->id, 'amount' => 5])
            ->assertExactJson([
                'total_spent'    => 5 * 3.15,
                'product_id'     => $product->id,
                'product_amount' => 5,
                'change'   => [
                    '100_cents' => 1,
                    '50_cents'  => 1,
                    '20_cents'  => 1,
                    '10_cents'  => 1,
                    '5_cents'   => 1,
                ],
            ]);
    }

    public function test_buyer_can_not_order_without_specifying_amount(): void
    {
        $buyer = User::factory()->buyer()->create(['deposit' => 17.6]);
        $product = Product::factory()->create(['amount_available' => 5, 'cost' => 3.15]);

        $this->actingAs($buyer)
            ->postJson(route('buy'), ['product_id' => $product->id])
            ->assertJsonValidationErrors('amount');
    }

    public function test_buyer_can_not_order_without_specifying_product(): void
    {
        $buyer = User::factory()->buyer()->create(['deposit' => 17.6]);
        Product::factory()->create(['amount_available' => 5, 'cost' => 3.15]);

        $this->actingAs($buyer)
            ->postJson(route('buy'), ['amount' => 5])
            ->assertJsonValidationErrors('product_id');
    }

    public function test_buyer_can_not_order_if_their_balance_is_not_enough(): void
    {
        $buyer = User::factory()->buyer()->create(['deposit' => 15.70]);
        $product = Product::factory()->create(['amount_available' => 5, 'cost' => 3.15]);

        $this->actingAs($buyer)
            ->postJson(route('buy'), ['product_id' => $product->id, 'amount' => 5])
            ->assertForbidden()
            ->assertExactJson(['message' => 'Please deposit more money into your account to order this product!']);
    }

    public function test_buyer_can_not_order_if_product_amount_not_available(): void
    {
        $buyer = User::factory()->buyer()->create(['deposit' => 15.75]);
        $product = Product::factory()->create(['amount_available' => 5, 'cost' => 3.15]);

        $this->actingAs($buyer)
            ->postJson(route('buy'), ['product_id' => $product->id, 'amount' => 6])
            ->assertForbidden()
            ->assertExactJson(['message' => 'The amount for product you specified is unavailable!']);
    }
}
