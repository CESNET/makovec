<x-mail::message>

# {{ __('emails.user_subrole_changed_header') }}

{{ $user->manager ? __('emails.user_subrole_changed_body_granted', ['name' => $user->name]) : __('emails.user_subrole_changed_body_revoked', ['name' => $user->name]) }}

{{ config('app.name') }}
</x-mail::message>
