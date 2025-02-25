@props(['href', 'active' => false])

<a {{ $attributes->class([
    'inline-block px-2 py-1 rounded-sm',
    'hover:bg-gray-300 text-gray-600 bg-gray-100 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500',
    'bg-gray-300 font-bold' => $active === true,
]) }}
    href="{{ $href }}">{{ $slot }}</a>
