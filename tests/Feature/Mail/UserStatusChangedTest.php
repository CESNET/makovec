<?php

namespace Tests\Feature\Mail;

use App\Mail\UserStatusChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailables\Address;
use Tests\TestCase;

class UserStatusChangedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_status_changed_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertTrue($user->active);

        $mailable = new UserStatusChanged($user);

        $mailable->assertHasSubject(
            __('emails.user_status_changed_subject', ['name' => $user->name])
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.user_status_changed_header'),
            __('emails.user_status_changed_body_active', ['name' => $user->name]),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.user_status_changed_header'),
            __('emails.user_status_changed_body_active', ['name' => $user->name]),
        ]);

        $user->active = false;
        $user->update();
        $user->refresh();

        $this->assertFalse($user->active);

        $mailable = new UserStatusChanged($user);

        $mailable->assertHasSubject(
            __('emails.user_status_changed_subject', ['name' => $user->name])
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.user_status_changed_header'),
            __('emails.user_status_changed_body_inactive', ['name' => $user->name]),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.user_status_changed_header'),
            __('emails.user_status_changed_body_inactive', ['name' => $user->name]),
        ]);
    }
}
