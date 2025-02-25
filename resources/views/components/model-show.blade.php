<div class="dark:bg-gray-800 sm:rounded-lg mb-6 overflow-hidden bg-white shadow-sm">
    <div class="sm:px-6 px-4 py-5">
        <h3 class="text-lg font-semibold">
            {{ $header }}
        </h3>
    </div>
    <div class="dark:border-gray-500 border-t border-gray-200">
        <dl>
            {{ $slot }}
        </dl>
    </div>
    @if ($footer ?? null)
        <div class="sm:px-6 odd:bg-gray-100 dark:odd:bg-gray-900 md:flex px-4 py-5 text-sm border-t">
            {{ $footer }}
        </div>
    @endif
</div>
