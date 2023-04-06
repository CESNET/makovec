<form x-data="{ open: false }" class="inline-block" action="{{ route('users.role', $user) }}" method="POST">
    @csrf
    @method('patch')

    @if ($user->admin)
        <x-button @click.prevent="open = !open" color="blue">{{ __('common.admin_revoke') }}</x-button>
    @else
        <x-button @click.prevent="open = !open" color="red">{{ __('common.admin_grant') }}</x-button>
    @endif

    <x-modal>
        <x-slot:title>
            @if ($user->admin)
                {{ __('common.admin_revoke_rights') }}
            @else
                {{ __('common.admin_grant_rights') }}
            @endif
        </x-slot:title>
        @if ($user->admin)
            {{ __('common.admin_revoke_rights_body', ['name' => $user->name]) }}
        @else
            {{ __('common.admin_grant_rights_body', ['name' => $user->name]) }}
        @endif
    </x-modal>
</form>
