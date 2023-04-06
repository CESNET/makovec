<?php

namespace Tests\Feature\Mail;

use App\Mail\UserAccountCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailables\Address;
use Tests\TestCase;

class UserAccountCreatedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_account_created_notification_email_check(): void
    {
        $user = User::factory()->create(['active' => true]);

        $mailable = new UserAccountCreated($user);

        $mailable->assertHasSubject(
            __('emails.user_account_created_subject', ['name' => $user->name])
        );
        $mailable->assertHasReplyTo(
            [new Address(
                config('mail.reply_to.address'),
                config('mail.reply_to.name')
            )]
        );
        $mailable->assertSeeInOrderInHtml([
            __('emails.user_account_created_header'),
            __('emails.user_account_created_body', ['name' => $user->name]),
        ]);
        $mailable->assertSeeInOrderInText([
            __('emails.user_account_created_header'),
            __('emails.user_account_created_body', ['name' => $user->name]),
        ]);
    }
}
