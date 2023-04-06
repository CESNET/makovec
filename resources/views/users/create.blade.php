@extends('layout')
@section('title', __('users.add'))

@section('content')

    <x-model-create action="{{ route('users.store') }}">
        <x-slot:header>{{ __('users.profile') }}</x-slot:header>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="name">{{ __('common.full_name') }}</x-label>
            </x-slot:dt>

            <x-input name="name" />
            <x-error-message name="name" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="uniqueid">{{ __('common.uniqueid_attribute') }}</x-label>
            </x-slot:dt>

            <x-input name="uniqueid" type="email" />
            <x-error-message name="uniqueid" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="email">{{ __('common.email_address') }}</x-label>
            </x-slot:dt>

            <x-input name="email" type="email" />
            <x-error-message name="email" />
        </x-dl-div>
    </x-model-create>

    <div>
        <x-button-link href="{{ route('users.index') }}">{{ __('common.back') }}</x-button-link>
        <x-button form="add">{{ __('common.add') }}</x-button>
    </div>

@endsection
