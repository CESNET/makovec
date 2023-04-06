<form action="{{ route('users.categories', $user) }}" method="POST">
    @csrf
    @method('patch')

    <ul class="mb-4">
        @foreach ($categories as $category)
            <li>
                <label>
                    <x-checkbox name="categories[]" :value="$category->id" :checked="$user
                        ->categories()
                        ->pluck('type')
                        ->contains($category->type)" /><span
                        class="ml-2">{{ $category->description }}</span>
                </label>
            </li>
        @endforeach
    </ul>

    <x-button type="reset" color="gray">{{ __('common.reset') }}</x-button>
    <x-button>{{ __('users.update_roles') }}</x-button>
</form>
