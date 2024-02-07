<?php

namespace Tests\Feature\Mail;

use App\Mail\UserSubroleChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSubroleChangedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_subrole_changed_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertFalse($user->manager);

        $mailable = new UserSubroleChanged($user);

        $mailable->assertHasSubject(
            __('emails.user_subrole_changed_subject', ['name' => $user->name]),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.user_subrole_changed_header'),
            __('emails.user_subrole_changed_body_revoked', ['name' => $user->name]),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.user_subrole_changed_header'),
            __('emails.user_subrole_changed_body_revoked', ['name' => $user->name]),
        ]);

        $user->manager = true;
        $user->update();
        $user->refresh();

        $this->assertTrue($user->manager);

        $mailable = new UserSubroleChanged($user);

        $mailable->assertHasSubject(
            __('emails.user_subrole_changed_subject', ['name' => $user->name]),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.user_subrole_changed_header'),
            __('emails.user_subrole_changed_body_granted', ['name' => $user->name]),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.user_subrole_changed_header'),
            __('emails.user_subrole_changed_body_granted', ['name' => $user->name]),
        ]);
    }
}
