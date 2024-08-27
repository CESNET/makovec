<?php

namespace Tests\Feature\Mail;

use App\Mail\YourStatusChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class YourStatusChangedTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function your_status_changed_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this->assertTrue($user->active);

        $mailable = new YourStatusChanged($user);

        $mailable->assertHasSubject(
            __('emails.your_status_changed_subject'),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.your_status_changed_subject'),
            __('emails.your_status_changed_body_active'),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.your_status_changed_subject'),
            __('emails.your_status_changed_body_active'),
        ]);

        $user->active = false;
        $user->update();
        $user->refresh();

        $this->assertFalse($user->active);

        $mailable = new YourStatusChanged($user);

        $mailable->assertHasSubject(
            __('emails.your_status_changed_subject'),
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.your_status_changed_subject'),
            __('emails.your_status_changed_body_inactive'),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.your_status_changed_subject'),
            __('emails.your_status_changed_body_inactive'),
        ]);
    }
}
