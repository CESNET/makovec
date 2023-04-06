<x-mail::message>

# {{ __('emails.user_role_changed_header') }}

{{ $user->admin ? __('emails.user_role_changed_body_granted', ['name' => $user->name]) : __('emails.user_role_changed_body_revoked', ['name' => $user->name]) }}

{{ config('app.name') }}
</x-mail::message>
