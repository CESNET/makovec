@props(['type' => 'text', 'models'])

<div class="mb-4">
    <form>
        <label class="sr-only" for="search">{{ __('common.search') }}</label>
        <input wire:model.live="search" class="dark:bg-transparent w-full px-4 py-2 border rounded-lg" type="search"
            name="search" id="search" placeholder="{{ __("inputs.searchbox_{$models}") }}" autofocus>
    </form>
    {{-- <div wire:loading class="font-bold">
        {{ __("inputs.loading_please_wait_{$models}") }}
    </div> --}}
</div>
