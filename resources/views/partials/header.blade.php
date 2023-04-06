<header>
    <noscript>
        <div class="dark:bg-red-900 text-red-50 py-4 font-bold bg-red-700">
            <div class="flex items-center max-w-screen-xl px-4 mx-auto space-x-4">
                <div class="dark:bg-red-700 p-2 bg-red-900 rounded">
                    <svg class="text-red-50 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <p>
                    <span class="sm:hidden">{{ __('common.javascript_short') }}</span>
                    <span class="sm:inline hidden">{{ __('common.javascript_long') }}</span>
                </p>
            </div>
        </div>
    </noscript>

    <div class="md:h-16 dark:bg-gray-800 bg-gray-200">
        <div class="md:pl-4 h-full max-w-screen-xl mx-auto">
            <nav x-data="{ open: false }" class="md:flex-row md:items-center flex flex-col justify-between h-full">

                <div class="md:flex-row md:items-center flex flex-col">
                    <p class="flex items-center h-16">
                        <a class="inline-block px-4 py-2 text-lg font-bold" href="/">{{ config('app.name') }}</a>
                    </p>
                    <ul class="md:flex md:flex-row flex-col hidden" id="navigation">
                        @can('viewAny', App\Models\Device::class)
                            <li>
                                <x-button-header href="{{ route('devices.index') }}"
                                    active="{{ Str::of(Route::currentRouteName())->startsWith('devices.') }}">
                                    {{ __('common.devices') }}
                                </x-button-header>
                            </li>
                        @endcan
                        <li>
                            <x-button-header href="{{ route('users.show', auth()->user()->id) }}"
                                active="{{ Route::currentRouteName() === 'users.show' && request()->segment(2) === (string) auth()->user()->id }}">
                                {{ __('common.my_profile') }}</x-button-header>
                        </li>
                        @can('do-everything')
                            <li>
                                <x-button-header href="{{ route('categories.index') }}"
                                    active="{{ Str::of(Route::currentRouteName())->startsWith('categories.') }}">
                                    {{ __('common.categories') }}
                                </x-button-header>
                            </li>
                        @endcan
                        @can('viewAny', App\Models\User::class)
                            <li>
                                <x-button-header href="{{ route('users.index') }}"
                                    active="{{ Route::currentRouteName() === 'users.index' || (Route::currentRouteName() === 'users.show' && request()->segment(2) !== (string) auth()->user()->id) }}">
                                    {{ __('common.users') }}
                                </x-button-header>
                            </li>
                        @endcan
                    </ul>
                </div>

                <div class="md:flex-row flex flex-col">
                    <ul class="md:pr-4 md:flex md:flex-row md:text-sm md:items-center flex-col hidden" id="profile">
                        <li>
                            <a class="md:inline-block md:rounded hover:bg-gray-400 hover:text-gray-900 whitespace-nowrap block px-4 py-2"
                                @env(['local', 'testing']) href="/fakelogout"
                            @else
                                href="{{ route('logout') }}" @endenv>{{ __('common.logout') }}</a>
                        </li>
                    </ul>
                </div>

                <div class="md:hidden top-3 right-4 absolute block">
                    <button
                        @click="open = !open; document.querySelector('#navigation').classList.toggle('hidden'); document.querySelector('#profile').classList.toggle('hidden');"
                        class="hover:bg-gray-300 dark:hover:bg-gray-700 p-2 rounded-lg" id="menu">
                        <svg :class="open && 'hidden'" class="block w-6 h-6" id="open-menu"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-cloak :class="open || 'hidden'" class="w-6 h-6" id="close-menu"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </nav>
        </div>
    </div>

    <div class="dark:bg-gray-700 h-10 bg-gray-100">
        <div class="md:px-8 h-full max-w-screen-xl px-4 mx-auto">
            <div class="flex items-center justify-between h-full text-lg font-semibold">
                <div>
                    @yield('title')
                </div>
                <div class="gap-x-1 flex px-2 overflow-x-auto text-base font-normal">
                    @yield('links')
                </div>
                <div class="flex items-center">
                    @yield('subheader')
                </div>
            </div>
        </div>
    </div>

    <hr class="hidden">
</header>
