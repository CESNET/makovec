<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FakeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_log_in_using_fakecontroller(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertCount(1, User::all());
        $this->assertNull($user->login_at);

        $this
            ->followingRedirects()
            ->from('/')
            ->post(route('fakelogin'), ['id' => $user->id])
            ->assertOk();

        $user->refresh();

        $this->assertEquals(route('home'), url()->current());
        $this->assertTrue(Auth::check());
        $this->assertNotNull($user->login_at);
    }

    #[Test]
    public function an_inactive_user_sees_blocked_information(): void
    {
        $user = User::factory()->create(['active' => false]);
        $user->refresh();

        $this->assertCount(1, User::all());
        $this->assertNull($user->login_at);

        $this
            ->followingRedirects()
            ->from('/')
            ->post(route('fakelogin'), ['id' => $user->id])
            ->assertOk()
            ->assertViewIs('blocked');

        $user->refresh();

        $this->assertFalse(Auth::check());
        $this->assertNotNull($user->login_at);
    }

    #[Test]
    public function a_user_can_log_out_using_fakecontroller(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertCount(1, User::all());
        $this->assertNull($user->login_at);

        Auth::login($user);
        Session::regenerate();

        $this->assertTrue(Auth::check());
        $this->assertFalse(Auth::guest());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->get(route('fakelogout'))
            ->assertOk();

        $this->assertEquals('http://localhost', url()->current());

        $this->assertFalse(Auth::check());
        $this->assertTrue(Auth::guest());
    }
}
