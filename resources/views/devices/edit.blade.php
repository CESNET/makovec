@extends('layout')
@section('title', __('devices.edit', ['name' => $device->mac, 'model' => $device->type]))

@section('content')

    <x-model-edit action="{{ route('devices.update', $device) }}">

        <x-slot:header>{{ __('devices.profile', ['model' => $device->type]) }}</x-slot:header>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="mac">{{ __('common.mac') }}</x-label>
            </x-slot:dt>

            <x-input name="mac" old="{{ $device->mac }}" maxlength="17" />
            <x-error-message name="mac" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="name">{{ __('common.name') }}</x-label>
            </x-slot:dt>

            <x-input name="name" old="{{ $device->name }}" maxlength="64" />
            <x-error-message name="name" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="description">{{ __('common.description') }}</x-label>
            </x-slot:dt>

            <x-input name="description" old="{{ $device->description }}" :required="false" />
            <x-error-message name="description" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="enabled">{{ __('common.status') }}</x-label>
            </x-slot:dt>

            <x-select name="enabled">
                <option value="1" @if ($device->enabled) selected @endif>{{ __('common.enabled') }}</option>
                <option value="0" @unless ($device->enabled) selected @endunless>{{ __('common.disabled') }}
                </option>
            </x-select>
            <x-error-message name="enabled" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="valid_from">{{ __('common.valid_from') }}</x-label>
            </x-slot:dt>

            <x-input name="valid_from" type="date" old="{{ $device->valid_from }}" :required="false" />
            <x-error-message name="valid_from" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="valid_to">{{ __('common.valid_to') }}</x-label>
            </x-slot:dt>

            <x-input name="valid_to" type="date" old="{{ $device->valid_to }}" :required="false" />
            <x-error-message name="valid_to" />
        </x-dl-div>

    </x-model-edit>

    <div>
        <x-button-link href="{{ route('devices.show', $device) }}">{{ __('common.back') }}</x-button-link>
        <x-button form="edit">{{ __('common.update') }}</x-button>
    </div>

@endsection
