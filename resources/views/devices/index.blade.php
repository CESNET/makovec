@extends('layout')
@section('title', __('common.devices'))

@section('links')

    @if (count($types) > 0)
        <x-button-subheader href="?type=" :active="is_null(request('type'))">{{ __('common.all') }}</x-button-subheader>

        @foreach ($types as $type)
            <x-button-subheader href="?type={{ $type }}" :active="$active = request('type') == $type">{{ ucfirst($type) }}</x-button-subheader>
        @endforeach
    @endif

@endsection

@section('subheader')

    @can('create', App\Models\Device::class)
        <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded-sm"
            href="{{ route('devices.create') }}">{{ __('common.add') }}</a>
    @endcan

@endsection

@section('content')

    <livewire:search-devices />

@endsection
