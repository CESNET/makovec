<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_anonymouse_cannot_set_users_categories(): void
    {
        $user = User::factory()->create();

        $this->assertCount(1, User::all());
        $this
            ->followingRedirects()
            ->patch(route('users.categories', $user))
            ->assertOk()
            ->assertSeeText('login');
    }

    /** @test */
    public function a_user_cannot_set_users_categories(): void
    {
        $user = User::factory()->create(['active' => true]);
        $another_user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.categories', $another_user))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_set_own_categories(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.categories', $user))
            ->assertForbidden();
    }

    /** @test */
    public function an_admin_can_set_users_categories(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertCount(2, User::all());
        $this->assertCount(0, $user->categories()->get());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.categories', $user))
            ->assertOk()
            ->assertSeeText(__('users.roles_updated'));
    }

    /** @test */
    public function an_admin_cannot_set_own_categories(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.categories', $admin))
            ->assertOk()
            ->assertSeeText(__('users.cannot_tweak_your_roles'));
    }
}
