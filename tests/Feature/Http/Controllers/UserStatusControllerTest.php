<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_anonymouse_cannot_toggle_users_status(): void
    {
        $user = User::factory()->create();

        $this->assertCount(1, User::all());
        $this
            ->followingRedirects()
            ->patch(route('users.status', $user))
            ->assertOk()
            ->assertSeeText('login');
    }

    /** @test */
    public function a_user_cannot_toggle_users_status(): void
    {
        $user = User::factory()->create(['active' => true]);
        $another_user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.status', $another_user))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_toggle_own_status(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.status', $user))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_toggle_users_status(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this->assertTrue($user->active);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.status', $user))
            ->assertOk()
            ->assertSeeText(__('users.inactive', ['name' => $user->name]));
        $user->refresh();
        $this->assertFalse($user->active);
    }

    /** @test */
    public function an_admin_cannot_toggle_own_status(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this->assertTrue($admin->active);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.status', $admin))
            ->assertOk()
            ->assertSeeText(__('users.cannot_toggle_your_status'));
        $admin->refresh();
        $this->assertTrue($admin->active);
    }
}
