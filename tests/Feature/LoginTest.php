<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    protected const PASSWORD = '12345678';
    protected $user;

    public function test_user_can_login_and_generate_token(): void
    {
        $this->postJson(route('login'), ['username' => $this->user->username, 'password' => self::PASSWORD])
            ->assertCreated()
            ->assertJsonStructure(['message', 'token'])
            ->assertJson(['message' => 'Success']);
    }

    public function test_user_can_logout_successfully(): void
    {
        $token = $this->user->createToken($this->user->role)->plainTextToken;

        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson(route('logout'))
            ->assertOk()
            ->assertJson(['message' => 'You have been logged out of your current session, your token is no longer valid']);

        $this->assertEquals(0, $this->user->tokens()->count());

    }

    public function test_user_will_get_warning_if_another_token_has_been_generated(): void
    {
        $this->user->createToken($this->user->role)->plainTextToken;

        $this->postJson(route('login'), ['username' => $this->user->username, 'password' => self::PASSWORD])
            ->assertCreated()
            ->assertJsonStructure(['message', 'token'])
            ->assertJson(['message' => 'There is already an active session using your account']);
    }

    public function test_user_can_logout_from_all_sessions(): void
    {
        $this->user->createToken($this->user->role)->plainTextToken;
        $token = $this->user->createToken($this->user->role)->plainTextToken;

        $this->assertEquals(2, $this->user->tokens()->count());

        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson(route('logout.all'))
            ->assertOk()
            ->assertJson(['message' => 'You have been logged out of all active sessions']);

        $this->assertEquals(0, $this->user->tokens()->count());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['password' => self::PASSWORD]);
    }
}
