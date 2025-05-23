@extends('layout')
@section('title', __('common.categories'))

@section('subheader')

    <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded-sm"
        href="{{ route('categories.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    <livewire:search-categories />

@endsection
