<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DepositTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_user_can_not_deposit_or_reset_account(): void
    {
        $this->deleteJson(route('reset'))->assertUnauthorized();
        $this->postJson(route('deposit'))->assertUnauthorized();
    }

    public function test_seller_can_not_deposit_or_reset_account(): void
    {
        $seller = User::factory()->seller()->create();
        $this->actingAs($seller)->deleteJson(route('reset'))->assertForbidden();
        $this->actingAs($seller)->postJson(route('deposit'))->assertForbidden();
    }

    public function test_buyer_can_deposit_valid_amounts(): void
    {
        $buyer = User::factory()->buyer()->create(['deposit' => 10]);

        $this->actingAs($buyer)->postJson(route('deposit'), ['amount' => 0.05])->assertCreated()->assertJson(['amount' => 0.05]);
        $this->assertEquals(10.05, $buyer->refresh()->deposit);
    }

    public function test_buyer_can_reset_their_account(): void
    {
        $user = User::factory()->buyer()->create(['deposit' => 100]);
        Deposit::factory()->for($user)->count(100)->create(['amount' => 1]);

        $this->actingAs($user)
            ->deleteJson(route('reset'))
            ->assertOk()
            ->assertJson(['deposit' => 0]);
    }

    public function test_buyer_can_not_deposit_invalid_amounts(): void
    {
        $buyer = User::factory()->buyer()->create(['deposit' => 10]);

        $this->actingAs($buyer)
            ->postJson(route('deposit'), ['amount' => 0.65])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('amount');

        $this->assertEquals(10, $buyer->refresh()->deposit);
    }
}
