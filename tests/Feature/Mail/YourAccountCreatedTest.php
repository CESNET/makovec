<?php

namespace Tests\Feature\Mail;

use App\Mail\YourAccountCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YourAccountCreatedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function your_account_created_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);

        $mailable = new YourAccountCreated($user);

        $mailable->assertHasSubject(
            __('emails.your_account_created_subject')
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.your_account_created_header'),
            __('emails.your_account_created_body'),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.your_account_created_header'),
            __('emails.your_account_created_body'),
        ]);
    }
}
