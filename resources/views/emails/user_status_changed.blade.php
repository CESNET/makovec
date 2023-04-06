<x-mail::message>

# {{ __('emails.user_status_changed_header') }}

{{ $user->active ? __('emails.user_status_changed_body_active', ['name' => $user->name]) : __('emails.user_status_changed_body_inactive', ['name' => $user->name]) }}

{{ config('app.name') }}
</x-mail::message>
