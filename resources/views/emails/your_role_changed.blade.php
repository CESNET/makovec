<x-mail::message>

# {{ __('emails.your_role_changed_subject') }}

{{ $user->admin ? __('emails.your_role_changed_body_granted') : __('emails.your_role_changed_body_revoked') }}

{{ config('app.name') }}
</x-mail::message>
