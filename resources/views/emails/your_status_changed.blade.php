<x-mail::message>

# {{ __('emails.your_status_changed_subject') }}

{{ $user->active ? __('emails.your_status_changed_body_active') : __('emails.your_status_changed_body_inactive') }}

{{ config('app.name') }}
</x-mail::message>
