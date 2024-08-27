@extends('layout')
@section('title', __('categories.show', ['type' => $category->type]))

@section('content')

    <x-model-show>

        <x-slot:header>{{ __('categories.profile') }}</x-slot:header>

        <x-dl-div>
            <x-slot:dt>{{ __('common.type') }}</x-slot:dt>
            {{ $category->type }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.description') }}</x-slot:dt>
            {{ $category->description }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.vlan') }}</x-slot:dt>
            vlan_{{ $category->vlan }}
        </x-dl-div>

        <x-dl-div>
            <x-slot:dt>{{ __('common.managers') }}</x-slot:dt>
            <livewire:list-category-managers :category="$category->id" />
            <livewire:add-category-managers :category="$category->id" />
        </x-dl-div>

    </x-model-show>

    <div>
        <x-button-link href="{{ route('categories.index') }}">{{ __('common.back') }}</x-button-link>
        <x-button-link href="{{ route('categories.edit', $category) }}" color="yellow">{{ __('common.edit') }}
        </x-button-link>
        @unless ($devices)
            <x-form-delete action="{{ route('categories.destroy', $category) }}" model="Category" name="{{ $category->type }}" />
        @endunless
    </div>

@endsection
