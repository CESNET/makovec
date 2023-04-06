<form x-data="{ open: false }" class="inline-block" action="{{ $action }}" method="post">
    @csrf
    @method('delete')
    <x-button @click.prevent="open = !open" color="red">{{ __('common.delete') }}</x-button>

    <x-modal>
        <x-slot:title>
            {{ __('modal.delete_header', ['model' => $model]) }}
        </x-slot:title>
        {{ __('modal.delete_body', ['name' => $name]) }}
    </x-modal>
</form>
