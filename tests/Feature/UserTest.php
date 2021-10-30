<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected $userData = [
        'username' => 'TestUsername',
        'password' => 'TestPassword',
        'role'     => 'seller',
    ];

    public function test_non_authorized_users_can_not_update_delete_or_show_users(): void
    {
        $this->getJson(route('users.index'))->assertUnauthorized();
        $user = User::factory()->create();
        $this->getJson(route('users.show', $user))->assertUnauthorized();
        $this->deleteJson(route('users.destroy', $user))->assertUnauthorized();
        $this->putJson(route('users.update', $user), [])->assertUnauthorized();
    }

    public function test_seller_user_can_be_created_successfully_without_authentication(): void
    {
        $this->postJson(route('users.store'), $this->userData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(User::first()->toArray());
    }

    public function test_buyer_user_can_be_created_successfully_without_authentication(): void
    {
        data_set($this->userData, 'role', User::BUYER);

        $this->postJson(route('users.store'), $this->userData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(User::first()->toArray());
    }

    public function test_buyer_user_can_not_be_created_without_password(): void
    {
        Arr::forget($this->userData, 'password');

        $this->postJson(route('users.store'), $this->userData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('password');
    }

    public function test_buyer_user_can_not_be_created_with_a_deposit(): void
    {
        data_set($this->userData, 'deposit', 1000);

        $this->postJson(route('users.store'), $this->userData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('deposit');
    }

    public function test_any_user_can_list_users(): void
    {
        $users = User::factory()->count(5)->create();
        $this->actingAs($users->first())
            ->getJson(route('users.index'))
            ->assertOk()
            ->assertJson(UserResource::collection($users)->resolve());
    }

    public function test_any_user_can_list_specific_user_with_id(): void
    {
        $users = User::factory()->count(2)->create();
        $this->actingAs($users->first())
            ->getJson(route('users.show', $users->last()))
            ->assertOk()
            ->assertJson((new UserResource($users->last()))->resolve());
    }

    public function test_a_user_can_update_only_their_data(): void
    {}

    public function test_a_user_can_not_update_others_accounts(): void
    {}

    public function test_no_user_can_update_deposit_value(): void
    {}

    public function test_a_user_can_delete_their_account(): void
    {}

    public function test_a_user_can_not_delete_others_accounts(): void
    {}


}
