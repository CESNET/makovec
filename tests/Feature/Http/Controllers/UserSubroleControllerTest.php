<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSubroleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_anonymouse_cannot_toggle_users_subrole(): void
    {
        $user = User::factory()->create();

        $this->assertCount(1, User::all());
        $this
            ->followingRedirects()
            ->patch(route('users.subrole', $user))
            ->assertOk()
            ->assertSeeText('login');
    }

    /** @test */
    public function a_user_cannot_toggle_users_subrole(): void
    {
        $user = User::factory()->create(['active' => true]);
        $another_user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.subrole', $another_user))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_toggle_own_subrole(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.subrole', $user))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_toggle_users_subrole(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertCount(2, User::all());
        $this->assertFalse($user->manager);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.subrole', $user))
            ->assertOk()
            ->assertSeeText(__('users.managered', ['name' => $user->name]));
        $user->refresh();
        $this->assertTrue($user->manager);
    }
}
