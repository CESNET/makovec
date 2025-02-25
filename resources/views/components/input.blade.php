@props(['name', 'type', 'old', 'required' => true, 'maxlength' => '255'])

<input
    class="text-sm dark:bg-transparent border-gray-300 dark:border-gray-700 @error($name) border-red-500 border @else @if (old($name) !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-xs rounded-md"
    type="{{ $type ?? 'text' }}" name="{{ $name }}" id="{{ $name }}" maxlength="{{ $maxlength }}"
    placeholder="{{ __("inputs.placeholder_$name") }}" value="{{ old($name) ?? ($old ?? null) }}"
    @if ($required) required @endif>
