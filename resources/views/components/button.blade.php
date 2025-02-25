@props(['color' => 'blue', 'type' => 'submit', 'size' => 'medium'])

<button
    {{ $attributes->class([
        'rounded-sm shadow-sm',
        'bg-blue-600 hover:bg-blue-700 text-blue-50' => $color === 'blue',
        'bg-red-600 hover:bg-red-700 text-red-50' => $color === 'red',
        'bg-gray-600 hover:bg-gray-700 text-gray-50' => $color === 'gray',
        'bg-gray-400 hover:bg-gray-500 text-gray-50' => $color === 'lightGray',
        'bg-green-600 hover:bg-green-700 text-green-50' => $color === 'green',
        'px-1 py-[0.5] text-sm' => $size === 'small',
        'px-4 py-2' => $size === 'medium',
    ]) }}
    type="{{ $type }}">
    {{ $slot }}
</button>
