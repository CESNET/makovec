<form id="add" action="{{ $action }}" method="POST">
    @csrf
    <div class="dark:bg-gray-800 sm:rounded-lg mb-6 overflow-hidden bg-white shadow-sm">
        <div class="sm:px-6 px-4 py-5">
            <h3 class="text-lg font-semibold">{{ $header }}</h3>
        </div>
        <div class="dark:border-gray-500 border-t border-gray-200">
            <dl>
                {{ $slot }}
            </dl>
        </div>
    </div>
</form>
