<nav class="flex items-center justify-between w-full px-24 h-20">
    <p class="font-bold text-3xl">Blog</p>
    <div class="flex items-center gap-8 h-full">
        @foreach ($links as $link)
            @include('components.nav-link', ['link' => $link])
        @endforeach
        @if (auth()->user())
            @include('components.nav-link', [
                'link' => [
                    'routeGroup' => 'user.*',
                    'route' => 'user.profile',
                    'routeName' => 'Profile',
                    'arguments' => [auth()->user()->username],
                ],
            ])
            @if (auth()->user()->isAdmin())
                @include('components.nav-link', [
                    'link' => [
                        'routeGroup' => '',
                        'route' => 'admin.dashboard',
                        'routeName' => 'Dashboard',
                        'arguments' => [],
                    ],
                ])
            @endif
        @endif
    </div>
    <div class="flex gap-3 items-center">
        @if (auth()->user() &&
                auth()->user()->isWriter())
            <a class="p-3 bg-blue-700 rounded-md"
               href="{{ route('articles.create') }}"
               title="Create article">
                <svg class="h-4 fill-slate-50"
                     viewBox="0 0 448 512"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                </svg>
            </a>
        @endif
        <form action="{{ route('app.search') }}"
              autocomplete="off"
              class="p-3 border-[1px] border-solid border-neutral-200 flex items-center rounded-xl"
              id="search-form">
            <input class="bg-transparent outline-transparent w-80"
                   id="search"
                   name="q"
                   placeholder="Title, tag, username..."
                   type="text"
                   value="{{ request()->q }}">
            <button class="">
                <svg class="h-5"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M0 0h24v24H0z"
                              fill="none"></path>
                        <path d="M11 2c4.968 0 9 4.032 9 9s-4.032 9-9 9-9-4.032-9-9 4.032-9 9-9zm0 16c3.867 0 7-3.133 7-7 0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7zm8.485.071l2.829 2.828-1.415 1.415-2.828-2.829 1.414-1.414z"></path>
                    </g>
                </svg>
            </button>
        </form>
        @if (auth()->user())
            @include('components.current-user')
            @include('components.nav-link', [
                'link' => [
                    'routeGroup' => '',
                    'route' => 'security.logout',
                    'routeName' => 'Logout',
                    'arguments' => [],
                ],
            ])
        @else
            @include('components.nav-link', [
                'link' => [
                    'routeGroup' => '',
                    'route' => 'security.login',
                    'routeName' => 'Login',
                    'arguments' => [],
                ],
            ])
            @include('components.nav-link', [
                'link' => [
                    'routeGroup' => '',
                    'route' => 'security.register',
                    'routeName' => 'Join',
                    'arguments' => [],
                ],
            ])
        @endif
    </div>
</nav>
