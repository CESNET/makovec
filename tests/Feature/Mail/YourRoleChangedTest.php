<?php

namespace Tests\Feature\Mail;

use App\Mail\YourRoleChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailables\Address;
use Tests\TestCase;

class YourRoleChangedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function your_role_changed_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertFalse($user->admin);

        $mailable = new YourRoleChanged($user);

        $mailable->assertHasSubject(
            __('emails.your_role_changed_subject'),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.your_role_changed_subject'),
            __('emails.your_role_changed_body_revoked'),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.your_role_changed_subject'),
            __('emails.your_role_changed_body_revoked'),
        ]);

        $user->admin = true;
        $user->update();
        $user->refresh();

        $this->assertTrue($user->admin);

        $mailable = new YourRoleChanged($user);

        $mailable->assertHasSubject(
            __('emails.your_role_changed_subject'),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.your_role_changed_subject'),
            __('emails.your_role_changed_body_granted'),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.your_role_changed_subject'),
            __('emails.your_role_changed_body_granted'),
        ]);
    }
}
