@props(['name', 'checked' => false, 'value' => null])

<input class="focus:ring-blue-500 dark:border-gray-700 focus:border-blue-500 border-gray-300 rounded-md shadow-xs"
    type="checkbox" name="{{ $name }}" id="{{ $name }}"
    @if ($value) value="{{ $value }}" @endif
    @if ($checked) checked @endif>
