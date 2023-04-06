<div>

    <x-searchbox models="devices" />

    <x-main-div>

        <x-table>

            <x-slot:thead>
                <x-th>{{ __('common.mac') }}
                    <x-db-sort :search="$search" sort="mac" />
                </x-th>
                <x-th>{{ __('common.type') }}
                </x-th>
                <x-th>{{ __('common.name') }}
                    <x-db-sort :search="$search" sort="name" />
                </x-th>
                <x-th>{{ __('common.enabled') }}
                    <x-db-sort :search="$search" sort="enabled" />
                </x-th>
                <x-th>{{ __('common.valid_from') }}
                    <x-db-sort :search="$search" sort="valid_from" />
                </x-th>
                <x-th>{{ __('common.valid_to') }}
                    <x-db-sort :search="$search" sort="valid_to" />
                </x-th>
                <x-th>&nbsp;</x-th>
            </x-slot:thead>

            @forelse ($devices as $device)
                <x-tr>
                    <x-td>{{ $device->mac }}</x-td>
                    <x-td>{{ $device->category->type }}</x-td>
                    <x-td>{{ $device->name }}</x-td>
                    <x-td>
                        @if ($device->enabled)
                            <span class="text-green-500">
                                <x-hi-check-circle />
                            </span>
                        @else
                            <span class="text-red-500">
                                <x-hi-x-circle />
                            </span>
                        @endif
                    </x-td>
                    <x-td>{{ $device->valid_from ?? '--' }}</x-td>
                    <x-td>{{ $device->valid_to ?? '--' }}</x-td>
                    <x-td>
                        <x-a href="{{ route('devices.show', $device) }}">{{ __('common.show') }}</x-a>
                    </x-td>
                </x-tr>
            @empty
                <x-tr>
                    <x-td colspan="7">{{ __('devices.none_found') }}</x-td>
                </x-tr>
            @endforelse

        </x-table>

        {{ $devices->links() }}

    </x-main-div>

</div>
