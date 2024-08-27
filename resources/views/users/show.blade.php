@extends('layout')
@section('title', __('users.show', ['name' => $user->name]))

@section('content')

    <x-model-show>
        <x-slot:header>{{ __('users.profile') }}</x-slot:header>

        <x-dl-div>
            <x-slot:dt>{{ __('common.full_name') }}</x-slot:dt>
            <span class="pr-4">{{ $user->name }}</span>
            <x-user-status :model="$user" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.uniqueid_attribute') }}</x-slot:dt>
            {{ $user->uniqueid }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.email_address') }}</x-slot:dt>
            <div>
                <x-a href="mailto:{{ $user->email }}">{{ $user->email }}</x-a>
            </div>
            @if (count($emails) > 1)
                @can('update', $user)
                    <div class="p-4 mt-4 bg-white border rounded-lg shadow">
                        <p class="mb-4 text-gray-500">
                            {{ __('users.select_different_email') }}
                        </p>
                        <form action="{{ route('users.update', $user) }}" method="post">
                            @csrf
                            @method('patch')
                            <select class="mr-4 rounded" name="email">
                                @foreach ($emails as $email)
                                    <option value="{{ $email }}" @if ($email === $user->email) selected @endif>
                                        {{ $email }}
                                    </option>
                                @endforeach
                            </select>
                            <x-button>{{ __('users.update_email') }}</x-button>
                        </form>
                    </div>
                @endcan
            @endif
        </x-dl-div>

        @can('do-everything')
            @unless ($user->id == auth()->id())
                <x-dl-div>
                    <x-slot:dt>{{ __('common.roles') }}</x-slot:dt>
                    <x-user-roles :user="$user" :categories="$categories" />
                </x-dl-div>
            @endunless
        @endcan

        @if ($user->login_at)
            <x-slot:footer>
                {{ __('common.last_login_at') }} {{ $user->login_at }} ({{ $user->login_at->diffForHumans() }}).
            </x-slot:footer>
        @endif

    </x-model-show>

    <div>
        <x-button-link href="{{ URL::previous() }}">{{ __('common.back') }}</x-button-link>
        @includeWhen(request()->user()->can('update', $user) && !request()->user()->is($user),
            'users.partials.status')

        @includeWhen(request()->user()->can('do-everything') && !request()->user()->is($user) && !$user->admin,
            'users.partials.subrole')

        @includeWhen(request()->user()->can('do-everything') && !request()->user()->is($user),
            'users.partials.role')
    </div>

@endsection
