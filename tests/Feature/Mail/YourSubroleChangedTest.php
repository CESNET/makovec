<?php

namespace Tests\Feature\Mail;

use App\Mail\YourSubroleChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YourSubroleChangedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function your_subrole_changed_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertFalse($user->manager);

        $mailable = new YourSubroleChanged($user);

        $mailable->assertHasSubject(
            __('emails.your_subrole_changed_subject'),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.your_subrole_changed_subject'),
            __('emails.your_subrole_changed_body_revoked'),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.your_subrole_changed_subject'),
            __('emails.your_subrole_changed_body_revoked'),
        ]);

        $user->manager = true;
        $user->update();
        $user->refresh();

        $this->assertTrue($user->manager);

        $mailable = new YourSubroleChanged($user);

        $mailable->assertHasSubject(
            __('emails.your_subrole_changed_subject'),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.your_subrole_changed_subject'),
            __('emails.your_subrole_changed_body_granted'),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.your_subrole_changed_subject'),
            __('emails.your_subrole_changed_body_granted'),
        ]);
    }
}
