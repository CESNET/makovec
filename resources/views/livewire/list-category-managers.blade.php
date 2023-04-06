<div>
    @forelse ($users as $user)
        <div x-data="{ open: false }" class="hover:bg-blue-50 flex">
            <div class="grow">
                <x-a href="{{ route('users.show', $user) }}">{{ $user->name }}</x-a>
            </div>
            <div>
                <x-button @click.prevent="open = !open" color="red" size="small">
                    {{ __('common.delete') }}</x-button>

                <x-modal wire:click="deleteManager('{{ $user->id }}')">
                    <x-slot:title>
                        {{ __('modal.delete_header', ['model' => 'user']) }}
                    </x-slot:title>
                    {{ __('modal.delete_body', ['name' => $user->name]) }}
                </x-modal>

            </div>
        </div>
    @empty
        {{ __('common.no_manager') }}
    @endforelse
</div>
