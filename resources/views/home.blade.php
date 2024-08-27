@extends('layout')
@section('title', __('common.dashboard'))

@section('content')
    <p class="mb-6">
        {!! __('welcome.introduction') !!}
    </p>

    <p>
        {!! __('welcome.contact') !!} <a href="mailto:info@eduroam.cz" class="hover:underline text-blue-500">info@eduroam.cz</a>.
    </p>
@endsection
