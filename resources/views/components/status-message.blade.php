@props(['color' => 'green'])

@if (session('status'))
    <div
        {{ $attributes->class([
            'mb-4 p-4 border rounded-sm shadow-sm',
            'bg-green-100 text-green-700 border-green-200' => $color === 'green',
            'bg-red-100 text-red-700 border-red-200' => $color === 'red',
        ]) }}>
        {{ session('status') }}
    </div>
@endif
