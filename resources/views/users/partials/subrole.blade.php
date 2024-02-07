<form x-data="{ open: false }" class="inline-block" action="{{ route('users.subrole', $user) }}" method="POST">
    @csrf
    @method('patch')

    @if ($user->manager)
        <x-button @click.prevent="open = !open" color="blue">{{ __('common.manager_revoke') }}</x-button>
    @else
        <x-button @click.prevent="open = !open" color="red">{{ __('common.manager_grant') }}</x-button>
    @endif

    <x-modal>
        <x-slot:title>
            @if ($user->manager)
                {{ __('common.manager_revoke_rights') }}
            @else
                {{ __('common.manager_grant_rights') }}
            @endif
        </x-slot:title>
        @if ($user->manager)
            {{ __('common.manager_revoke_rights_body', ['name' => $user->name]) }}
        @else
            {{ __('common.manager_grant_rights_body', ['name' => $user->name]) }}
        @endif
    </x-modal>
</form>
