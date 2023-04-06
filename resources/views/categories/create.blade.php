@extends('layout')
@section('title', __('categories.add'))

@section('content')

    <x-model-create action="{{ route('categories.store') }}">

        <x-slot:header>{{ __('categories.profile') }}</x-slot:header>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="type">{{ __('common.type') }}</x-label>
            </x-slot:dt>

            <x-input name="type" maxlength="32" />
            <x-error-message name="type" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="description">{{ __('common.description') }}</x-label>
            </x-slot:dt>

            <x-input name="description" />
            <x-error-message name="description" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="vlan">{{ __('common.vlan') }}</x-label>
            </x-slot:dt>

            <x-input name="vlan" maxlength="64" />
            <div class="text-right text-gray-500"><small>{{ __('categories.vlan_regexp') }}</small></div>
            <x-error-message name="vlan" />
        </x-dl-div>

    </x-model-create>

    <div>
        <x-button-link href="{{ route('categories.index') }}">{{ __('common.back') }}</x-button-link>
        <x-button form="add">{{ __('common.add') }}</x-button>
    </div>

@endsection
