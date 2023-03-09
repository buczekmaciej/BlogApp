<nav class="flex items-center gap-8 w-3/4 h-20">
    <a class="font-bold text-3xl"
       href="{{ route('app.homepage') }}">Blog</a>
    <div class="flex items-center gap-8 h-full">
        @foreach ($links as $link)
            @include('components.nav-link', ['link' => $link])
        @endforeach
    </div>
    <div class="ml-auto flex gap-4 items-center">
        @include('components.current-user')
        @include('components.nav-link', [
            'link' => [
                'routeGroup' => '',
                'route' => 'security.logout',
                'routeName' => 'Logout',
                'arguments' => [],
            ],
        ])
    </div>
</nav>
