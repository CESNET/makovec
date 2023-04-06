<x-mail::message>

# {{ __('emails.user_account_created_header') }}

{{ __('emails.user_account_created_body', ['name' => $user->name]) }}

{{ config('app.name') }}
</x-mail::message>
