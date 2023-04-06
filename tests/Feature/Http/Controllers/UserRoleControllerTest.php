<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_anonymouse_cannot_toggle_users_role(): void
    {
        $user = User::factory()->create();

        $this->assertCount(1, User::all());
        $this
            ->followingRedirects()
            ->patch(route('users.role', $user))
            ->assertOk()
            ->assertSeeText('login');
    }

    /** @test */
    public function a_user_cannot_toggle_users_role(): void
    {
        $user = User::factory()->create(['active' => true]);
        $another_user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.role', $another_user))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_toggle_own_role(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.role', $user))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_toggle_users_role(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertCount(2, User::all());
        $this->assertFalse($user->admin);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.role', $user))
            ->assertOk()
            ->assertSeeText(__('users.admined', ['name' => $user->name]));
        $user->refresh();
        $this->assertTrue($user->admin);
    }
}
