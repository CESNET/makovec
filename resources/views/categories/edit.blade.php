@extends('layout')
@section('title', __('categories.edit', ['type' => $category->type]))

@section('content')

    <x-model-edit action="{{ route('categories.update', $category) }}">

        <x-slot:header>{{ __('categories.profile') }}</x-slot:header>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="type">{{ __('common.type') }}</x-label>
            </x-slot:dt>

            <x-input name="type" old="{{ $category->type }}" maxlength="32" />
            <x-error-message name="type" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="description">{{ __('common.description') }}</x-label>
            </x-slot:dt>

            <x-input name="description" old="{{ $category->description }}" />
            <x-error-message name="description" />
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>
                <x-label for="vlan">{{ __('common.vlan') }}</x-label>
            </x-slot:dt>

            <x-input name="vlan" old="{{ $category->vlan }}" maxlength="64" />
            <div class="text-right text-gray-500"><small>{{ __('categories.vlan_regexp') }}</small></div>
            <x-error-message name="vlan" />
        </x-dl-div>

    </x-model-edit>

    <div>
        <x-button-link href="{{ route('categories.show', $category) }}">{{ __('common.back') }}</x-button-link>
        <x-button form="edit">{{ __('common.update') }}</x-button>
    </div>

@endsection
