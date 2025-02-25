@props(['name', 'required' => true])

<select
    class="text-sm dark:bg-transparent border-gray-300 dark:border-gray-700 @error($name) border-red-500 border @else @if (old($name) !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-xs rounded-md"
    name="{{ $name }}" id="{{ $name }}" @if ($required) required @endif>
    {{ $slot }}
</select>
