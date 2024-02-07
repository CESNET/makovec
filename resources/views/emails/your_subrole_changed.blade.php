<x-mail::message>

# {{ __('emails.your_subrole_changed_subject') }}

{{ $user->manager ? __('emails.your_subrole_changed_body_granted') : __('emails.your_subrole_changed_body_revoked') }}

{{ config('app.name') }}
</x-mail::message>
