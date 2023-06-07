<?php

namespace Tests\Feature\Mail;

use App\Mail\UserRoleChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleChangedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_role_changed_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertFalse($user->admin);

        $mailable = new UserRoleChanged($user);

        $mailable->assertHasSubject(
            __('emails.user_role_changed_subject', ['name' => $user->name])
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.user_role_changed_header'),
            __('emails.user_role_changed_body_revoked', ['name' => $user->name]),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.user_role_changed_header'),
            __('emails.user_role_changed_body_revoked', ['name' => $user->name]),
        ]);

        $user->admin = true;
        $user->update();
        $user->refresh();

        $this->assertTrue($user->admin);

        $mailable = new UserRoleChanged($user);

        $mailable->assertHasSubject(
            __('emails.user_role_changed_subject', ['name' => $user->name]),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.user_role_changed_header'),
            __('emails.user_role_changed_body_granted', ['name' => $user->name]),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.user_role_changed_header'),
            __('emails.user_role_changed_body_granted', ['name' => $user->name]),
        ]);
    }
}
