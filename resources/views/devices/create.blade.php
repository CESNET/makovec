@extends('layout')
@section('title', __('devices.add'))

@section('content')

    <div x-data="{ open: false, buttonText: 'Show' }">

        <x-model-create action="{{ route('devices.store') }}">

            <x-slot:header>{{ __('devices.device_profile') }}</x-slot:header>

            <x-dl-div>
                <x-slot:dt>
                    <x-label for="category_id">{{ __('common.category') }}</x-label>
                </x-slot:dt>

                <x-select name="category_id">
                    @if ($categories->count() === 1)
                        <option value="{{ $categories[0]->id }}" selected>{{ ucfirst($categories[0]->type) }}</option>
                    @else
                        <option value="">{{ __('devices.choose_category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>
                                {{ ucfirst($category->type) }}</option>
                        @endforeach
                    @endif
                </x-select>
                <x-error-message name="category_id" />
            </x-dl-div>

            <x-dl-div>
                <x-slot:dt>
                    <x-label for="mac">{{ __('common.mac') }}</x-label>
                </x-slot:dt>

                <x-input name="mac" maxlength="17" />
                <x-error-message name="mac" />
            </x-dl-div>

            <x-dl-div>
                <x-slot:dt><span class="text-gray-400">{{ __('common.optional_attributes') }}</span></x-slot:dt>

                <x-button @click="open = !open; buttonText = open ? 'Hide' : 'Show'" color="lightGray" type="button"
                    x-text="buttonText">
                </x-button>
            </x-dl-div>

            <div x-show="open" x-transition>

                <x-dl-div>
                    <x-slot:dt>
                        <x-label for="name">{{ __('common.name') }}</x-label>
                    </x-slot:dt>

                    <x-input name="name" maxlength="64" :required="false" />
                    <x-error-message name="name" />
                </x-dl-div>

                <x-dl-div>
                    <x-slot:dt>
                        <x-label for="description">{{ __('common.description') }}</x-label>
                    </x-slot:dt>

                    <x-input name="description" :required="false" />
                    <x-error-message name="description" />
                </x-dl-div>

                <x-dl-div>
                    <x-slot:dt>
                        <x-label for="enabled">{{ __('common.status') }}</x-label>
                    </x-slot:dt>

                    <x-select name="enabled">
                        <option value="1" @if (is_null(old('enabled')) || old('enabled') === '1') selected @endif>{{ __('common.enabled') }}
                        </option>
                        <option value="0" @if (old('enabled') === '0') selected @endif>
                            {{ __('common.disabled') }}
                        </option>
                    </x-select>
                    <x-error-message name="enabled" />
                </x-dl-div>

                <x-dl-div>
                    <x-slot:dt>
                        <x-label for="valid_from">{{ __('common.valid_from') }}</x-label>
                    </x-slot:dt>

                    <x-input name="valid_from" type="date" :required="false" />
                    <x-error-message name="valid_from" />
                </x-dl-div>

                <x-dl-div>
                    <x-slot:dt>
                        <x-label for="valid_to">{{ __('common.valid_to') }}</x-label>
                    </x-slot:dt>

                    <x-input name="valid_to" type="date" :required="false" />
                    <x-error-message name="valid_to" />
                </x-dl-div>

            </div>

        </x-model-create>

    </div>

    <div>
        <x-button-link href="{{ route('devices.index') }}">{{ __('common.back') }}</x-button-link>
        <x-button form="add">{{ __('common.add') }}</x-button>
    </div>

@endsection
