<form x-data="{ open: false }" class="inline-block" action="{{ route('users.status', $user) }}" method="POST">
    @csrf
    @method('patch')

    @if ($user->active)
        <x-button @click.prevent="open = !open" color="blue">{{ __('common.deactivate') }}</x-button>
    @else
        <x-button @click.prevent="open = !open" color="red">{{ __('common.activate') }}</x-button>
    @endif

    <x-modal>
        <x-slot:title>
            @if ($user->active)
                {{ __('common.deactivate_user') }}
            @else
                {{ __('common.activate_user') }}
            @endif
        </x-slot:title>
        @if ($user->active)
            {{ __('common.deactivate_user_body', ['name' => $user->name]) }}
        @else
            {{ __('common.activate_user_body', ['name' => $user->name]) }}
        @endif
    </x-modal>
</form>
