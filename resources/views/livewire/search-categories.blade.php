<div>

    <x-searchbox models="categories" />

    <x-main-div>

        <x-table>

            <x-slot:thead>
                <x-th>{{ __('common.type') }}</x-th>
                <x-th>{{ __('common.description') }}</x-th>
                <x-th>{{ __('common.devices') }}</x-th>
                <x-th>{{ __('common.managers') }}</x-th>
                <x-th>{{ __('common.vlan') }}</x-th>
                <x-th>&nbsp;</x-th>
            </x-slot:thead>

            @forelse ($categories as $category)
                <x-tr>
                    <x-td>{{ $category->type }}</x-td>
                    <x-td>{{ Str::limit($category->description, 40) }}</x-td>
                    <x-td>{{ $category->devices_count }}</x-td>
                    <x-td>{{ $category->users_count }}</x-td>
                    <x-td>vlan_{{ $category->vlan }}</x-td>
                    <x-td>
                        <x-a href="{{ route('categories.show', $category) }}">{{ __('common.show') }}</x-a>
                    </x-td>
                </x-tr>
            @empty
                <x-tr>
                    <x-td colspan="5">{{ __('categories.none_found') }}</x-td>
                </x-tr>
            @endforelse
        </x-table>

    </x-main-div>

</div>
