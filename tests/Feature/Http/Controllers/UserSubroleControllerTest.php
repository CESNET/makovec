<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserSubroleControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function an_admin_cannot_toggle_own_subrole(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this->assertTrue($admin->active);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.subrole', $admin))
            ->assertOk()
            ->assertSeeText(__('users.cannot_toggle_your_role'));
        $admin->refresh();
        $this->assertTrue($admin->active);
    }
}
