@props(['href', 'active' => false])

<a {{ $attributes->class([
    'md:inline-block md:rounded hover:bg-gray-400 hover:text-gray-900 block px-4 py-2',
    'bg-gray-300 font-bold dark:bg-gray-400 dark:text-gray-700' => $active,
]) }}
    href="{{ $href }}">{{ $slot }}</a>
