@extends('layout')
@section('title', __('devices.show', ['name' => $device->name, 'model' => $device->type]))

@section('content')

    <x-model-show>

        <x-slot:header>{{ __('devices.profile', ['model' => $device->type]) }}</x-slot:header>

        <x-dl-div>
            <x-slot:dt>{{ __('common.mac') }}</x-slot:dt>
            {{ $device->mac }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.name') }}</x-slot:dt>
            {{ $device->name }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.description') }}</x-slot:dt>
            {{ $device->description ?? '--' }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.status') }}</x-slot:dt>
            @if ($device->enabled)
                <span class="text-green-500 align-bottom">
                    <x-hi-check-circle />
                    {{ __('common.enabled') }}
                </span>
            @else
                <span class="text-red-500 align-bottom">
                    <x-hi-x-circle />
                    {{ __('common.disabled') }}
                </span>
            @endif
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.valid_from') }}</x-slot:dt>
            {{ $device->valid_from ?? '--' }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.valid_to') }}</x-slot:dt>
            {{ $device->valid_to ?? '--' }}
        </x-dl-div>

        @if ($device->log->count())
            <x-slot:footer>
                <div class="md:grow md:text-left">
                    @if ($device->logCreated)
                        {{ __('common.created_by') }}
                        @if ($device->logCreated->user)
                            <x-a>
                                <x-slot:href>mailto:{{ $device->logCreated->user->email }}</x-slot:href>
                                {{ $device->logCreated->user->name }}
                            </x-a>
                        @else
                            {{ __('common.unknown') }}
                        @endif
                        {{ __('common.at') }}
                        {{ $device->logCreated->created_at }} ({{ $device->logCreated->created_at->diffForHumans() }}).
                    @endif
                </div>
                <div class="md:grow md:text-right">
                    @if ($device->logUpdated)
                        {{ __('common.updated_by') }}
                        @if ($device->logUpdated->user)
                            <x-a>
                                <x-slot:href>mailto:{{ $device->logUpdated->user->email }}</x-slot:href>
                                {{ $device->logUpdated->user->name }}
                            </x-a>
                        @else
                            {{ __('common.unknown') }}
                        @endif
                        {{ __('common.at') }} {{ $device->logUpdated->updated_at }}
                        ({{ $device->logUpdated->updated_at->diffForHumans() }}).
                    @endif
                </div>
            </x-slot:footer>
        @endif

    </x-model-show>

    <div>
        <x-button-link href="{!! route('devices.index', $query) !!}">{{ __('common.back') }}</x-button-link>
        @can('update', $device)
            <x-button-link href="{{ route('devices.edit', $device) }}" color="yellow">{{ __('common.edit') }}</x-button-link>
        @endcan
        @can('delete', $device)
            <x-form-delete action="{{ route('devices.destroy', $device) }}" model="{{ $device->category->type }}"
                name="{{ $device->name }}" />
        @endcan
    </div>
@endsection
