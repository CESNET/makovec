<div class="mt-4">

    <div class="relative">
        <div class="absolute inset-y-0 top-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <input wire:model="search"
            class="focus:outline-none focus:placeholder-gray-400 focus:border-blue-300 focus:shadow-outline-blue sm:text-sm block w-full py-2 pl-10 pr-3 leading-5 placeholder-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md"
            type="search" id="search" placeholder="Search users...">
    </div>

    @if ($users)
        <ul
            class="w-full mt-2 text-sm text-gray-700 bg-white border border-gray-300 divide-y divide-gray-200 rounded-md">

            @forelse ($users as $user)
                <li wire:click="addManager('{{ $user->id }}')"
                    class="hover:bg-gray-200 p-4 transition ease-in-out cursor-pointer">
                    <div class="font-semibold">{{ $user->name }}</div>
                    <div class="text-gray-600">{{ $user->email }}</div>
                </li>
            @empty
                <li class="hover:bg-gray-200 p-4 transition ease-in-out">No results found.</li>
            @endforelse

        </ul>
    @endif
</div>
