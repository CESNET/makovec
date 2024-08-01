<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function an_anonymouse_cannot_see_the_list_of_users(): void
    {
        User::factory()->times(10)->create();

        $this->assertCount(10, User::all());
        $this
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_see_users_details(): void
    {
        $user = User::factory()->create();

        $this->assertCount(1, User::all());
        $this
            ->followingRedirects()
            ->get(route('users.show', $user))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_update_a_user(): void
    {
        $user = User::factory()->create();

        $this->assertCount(1, User::all());
        $this
            ->followingRedirects()
            ->patch(route('users.update', $user))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function a_user_cannot_see_the_list_of_users(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();
        User::factory()->times(10)->create();

        $this->assertCount(11, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_cannot_see_users_details(): void
    {
        $user = User::factory()->create(['active' => true]);
        $another_user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.show', $another_user))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_can____see_their_details(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertCount(1, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.show', $user))
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee($user->uniqueid)
            ->assertSee($user->email);
    }

    #[Test]
    public function a_user_cannot_update_users_details(): void
    {
        $user = User::factory()->create(['active' => true]);
        $another_user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.update', $another_user))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_can_update_own_details(): void
    {
        $old_email = fake()->safeEmail();
        $new_email = fake()->safeEmail();
        $user = User::factory()->create([
            'active' => true,
            'email' => $old_email,
            'emails' => "{$old_email};{$new_email}",
        ]);
        $user->refresh();

        $this->assertCount(1, User::all());
        $this->assertEquals($old_email, $user->email);
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.update', $user), [
                'email' => $new_email,
            ])
            ->assertOk()
            ->assertSeeText(__('users.email_changed'));
        $user->refresh();
        $this->assertEquals($new_email, $user->email);
    }

    #[Test]
    public function an_admin_can_see_the_list_of_users(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        User::factory()->times(15)->create();

        $this->assertCount(16, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertOk()
            ->assertSeeTextInOrder(['Showing', '1', 'to', '10', 'of', '16']);
    }

    #[Test]
    public function an_admin_can_see_users_details(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('users.show', $user))
            ->assertOk()
            ->assertSeeText($user->name)
            ->assertSeeText($user->uniqueid)
            ->assertSeeText($user->email);
    }

    #[Test]
    public function an_admin_can_see_their_details(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('users.show', $admin))
            ->assertOk()
            ->assertSeeText($admin->name)
            ->assertSeeText($admin->uniqueid)
            ->assertSeeText($admin->email);
    }

    #[Test]
    public function an_admin_can_update_a_users_details(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $old_email = fake()->safeEmail();
        $new_email = fake()->safeEmail();
        $user = User::factory()->create([
            'active' => true,
            'email' => $old_email,
            'emails' => "{$old_email};{$new_email}",
        ]);

        $this->assertCount(2, User::all());
        $this->assertEquals($old_email, $user->email);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.update', $user), [
                'email' => $new_email,
            ])
            ->assertOk()
            ->assertSeeText(__('users.email_changed'));
        $user->refresh();
        $this->assertEquals($new_email, $user->email);
    }

    #[Test]
    public function an_admin_can_update_a_users_details_with_no_change(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create();

        $this->assertCount(2, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.update', $user), $user->toArray())
            ->assertOk();
    }

    #[Test]
    public function an_admin_can_update_own_details(): void
    {
        $old_email = fake()->safeEmail();
        $new_email = fake()->safeEmail();
        $admin = User::factory()->create([
            'active' => true,
            'admin' => true,
            'email' => $old_email,
            'emails' => "{$old_email};{$new_email}",
        ]);

        $this->assertCount(1, User::all());
        $this->assertEquals($old_email, $admin->email);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.update', $admin), [
                'email' => $new_email,
            ])
            ->assertOk()
            ->assertSeeText(__('users.email_changed'));
        $admin->refresh();
        $this->assertEquals($new_email, $admin->email);
    }

    #[Test]
    public function an_admin_cannot_toggle_own_role(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $admin->refresh();

        $this->assertCount(1, User::all());
        $this->assertTrue($admin->admin);
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.role', $admin))
            ->assertOk()
            ->assertSeeText(__('users.cannot_toggle_your_role'));
        $this->assertTrue($admin->admin);
    }
}
