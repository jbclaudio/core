<header class="relative mb-5 border-b shadow">
    <div class="container flex flex-wrap items-center max-sm:px-3">
        <input
            class="peer hidden"
            id="navigation"
            type="checkbox"
        />
        <div class="py-2">
            <div class="mr-3 flex items-center text-xl sm:text-3xl">
                <a
                    href="/"
                    aria-label="@lang('Go to home')"
                >
                    <span class="hidden sm:inline">
                        <img
                            src="https://raw.githubusercontent.com/rapidez/art/master/logo.svg"
                            alt=""
                            height="48"
                            width="152"
                        >
                    </span>
                    <span class="inline sm:hidden">
                        <img
                            src="https://raw.githubusercontent.com/rapidez/art/master/r.svg"
                            alt=""
                            height="30"
                            width="30"
                        >
                    </span>
                </a>
                <label
                    class="ml-3 cursor-pointer sm:hidden"
                    for="navigation"
                >
                    <x-heroicon-o-menu class="inline w-7" />
                </label>
            </div>
        </div>
        <div class="w-6/12 h-12 flex items-center">
            @include('rapidez::layouts.partials.header.autocomplete')
        </div>
        <div class="w-1/3 sm:w-1/4 flex justify-end items-center pr-3">
            @include('rapidez::layouts.partials.header.account')
            @include('rapidez::layouts.partials.header.minicart')
        </div>
        <nav class="w-full">
            {{-- Because the lack of an @includeIf or @includeWhen equivalent for Blade components we're using a placeholder --}}
            <x-dynamic-component :component="App::providerIsLoaded('Rapidez\Menu\MenuServiceProvider') ? 'menu' : 'placeholder'" />
        </nav>
    </div>
</header>
