@props(['href', 'color' => 'gray'])

<a {{ $attributes->class([
    'inline-block px-4 py-2 rounded-sm shadow-sm',
    'hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600 text-gray-600 bg-gray-300' =>
        $color === 'gray',
    'hover:bg-yellow-200 dark:bg-yellow-700 dark:text-yellow-100 dark:hover:bg-yellow-600 text-yellow-600 bg-yellow-300' =>
        $color === 'yellow',
]) }}class=""
    href="{{ $href }}">
    {{ $slot }}
</a>
