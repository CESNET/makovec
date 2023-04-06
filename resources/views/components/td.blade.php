@props(['colspan' => null])

<td class="whitespace-nowrap px-6 py-3 text-sm @if ($colspan) font-semibold text-center @endif"
    @if ($colspan) colspan="{{ $colspan }}" @endif>
    {{ $slot }}
</td>
