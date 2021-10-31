<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    public function test_anyone_can_list_products(): void
    {
        $products = Product::factory()->count(5)->create();

        $this->getJson(route('products.index'))
            ->assertJsonCount(5)
            ->assertJson($products->toArray());
    }

    public function test_anyone_can_see_a_product(): void
    {
        $product = Product::factory()->create();

        $this->getJson(route('products.show', $product))->assertJson($product->toArray());
    }

    public function test_seller_user_can_add_product(): void
    {
        $data = [
            'product_name'     => 'TestProduct',
            'amount_available' => 2,
            'cost'             => 2.3
        ];
        $seller = User::factory()->seller()->create();
        $this->actingAs($seller)->postJson(route('products.store'), $data)->assertCreated();
        $this->assertDatabaseHas('products', $data + ['seller_id' => $seller->id]);
    }

    public function test_seller_can_not_add_product_with_missing_data(): void
    {
        $data = [
            'product_name'     => 'TestProduct',
            'cost'             => 2.3
        ];
        $seller = User::factory()->seller()->create();
        $this->actingAs($seller)
            ->postJson(route('products.store'), $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('amount_available');
    }

    public function test_seller_can_not_add_a_product_to_another_seller_account(): void
    {
        [$seller1, $seller2] = User::factory()->seller()->count(2)->create();
        $data = [
            'product_name'     => 'TestProduct',
            'seller_id'        => $seller2->id,
            'amount_available' => 2,
            'cost'             => 2.3
        ];
        dump($seller1->id);
        $this->actingAs($seller1)->postJson(route('products.store'), $data)->assertCreated();
        $this->assertEquals($seller1->id, Product::first()->seller_id);
    }

    public function test_public_can_not_access_store_update_or_delete_routes(): void
    {
        $this->postJson(route('products.store'), [])->assertUnauthorized();
        $product = Product::factory()->create();
        $this->deleteJson(route('products.destroy', $product))->assertUnauthorized();
        $this->putJson(route('products.update', $product), [])->assertUnauthorized();
    }

    public function test_seller_can_update_their_product_details(): void
    {
        $product = Product::factory()->create(['product_name' => 'oldName']);
        $this->actingAs($product->seller)
            ->putJson(route('products.update', $product), ['product_name' => 'newName'])
            ->assertOk()
            ->assertJson(['product_name' => 'newName']);
    }

    public function test_seller_can_not_update_other_seller_product(): void
    {
        $product1 = Product::factory()->create(['product_name' => 'oldName']);
        $product2 = Product::factory()->create(['product_name' => 'oldName']);
        $this->actingAs($product1->seller)
            ->putJson(route('products.update', $product2), ['product_name' => 'newName'])
            ->assertForbidden();
    }

    public function test_seller_can_not_update_seller_id(): void
    {
        $product = Product::factory()->create(['product_name' => 'oldName']);
        $seller = $product->seller;
        $this->actingAs($seller)
            ->putJson(route('products.update', $product), ['seller_id' => null])
            ->assertOk();
        $this->assertEquals($seller->id, $product->refresh()->seller_id);
    }

    public function test_seller_can_delete_their_own_product(): void
    {
        $product = Product::factory()->create(['product_name' => 'oldName']);
        $this->actingAs($product->seller)
            ->deleteJson(route('products.destroy', $product))
            ->assertNoContent();
        $this->assertEquals(0, Product::count());
    }

    public function test_seller_can_not_delete_other_seller_product(): void
    {
        $product = Product::factory()->create(['product_name' => 'oldName']);
        $seller = User::factory()->seller()->create();
        $this->actingAs($seller)
            ->deleteJson(route('products.destroy', $product))
            ->assertForbidden();
        $this->assertEquals(1, Product::count());
    }
}
