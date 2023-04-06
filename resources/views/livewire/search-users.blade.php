<div>

    <x-searchbox models="users" />

    <x-main-div>

        <x-table>

            <x-slot:thead>
                <x-th>{{ __('common.name') }}</x-th>
                <x-th>{{ __('common.email') }}</x-th>
                <x-th>{{ __('common.status') }}</x-th>
                <x-th>&nbsp;</x-th>
            </x-slot:thead>

            @forelse ($users as $user)
                <x-tr>
                    <x-td>
                        <div class="font-bold">{{ $user->name }}</div>
                        <div class="text-gray-400">{{ $user->uniqueid }}</div>
                    </x-td>
                    <x-td>
                        <x-a href="mailto:{{ $user->email }}">{{ $user->email }}</x-a>
                    </x-td>
                    <x-td>
                        <x-user-status :model="$user" />
                    </x-td>
                    <x-td>
                        <x-a href="{{ route('users.show', $user) }}">{{ __('common.show') }}</x-a>
                    </x-td>
                </x-tr>
            @empty
                <x-tr>
                    <x-td colspan="4">{{ __('users.none_found') }}</x-td>
                </x-tr>
            @endforelse

        </x-table>

        {{ $users->links() }}

    </x-main-div>

</div>
