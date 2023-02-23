<!DOCTYPE html>
<html class="min-h-screen"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1"
          name="viewport">

    <title>@yield('title', 'Default title') | Blog</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50 text-neutral-900 flex{{ $__env->yieldContent('bodyClass', '') }}">
    @if (!Route::is('security.*'))
        <nav class="flex items-center justify-between w-full px-24 h-20 border-b-[1px] border-b-solid border-b-slate-150">
            <p class="font-bold text-3xl">Blog</p>
            <div class="flex items-center gap-8 h-full">
                <a @if (Route::is('app.*')) class="active-link" @endif
                   href="{{ route('app.homepage') }}">Home</a>
                <a @if (Route::is('articles.*')) class="active-link" @endif
                   href="{{ route('articles.list') }}">Articles</a>
                <a @if (Route::is('authors.*')) class="active-link" @endif
                   href="{{ route('authors.list') }}">Authors</a>
                <a @if (Route::is('tags.*')) class="active-link" @endif
                   href="{{ route('tags.list') }}">Tags</a>
                @if (auth()->user())
                    <a @if (Route::is('profile')) class="active-link" @endif
                       href="{{ route('profile') }}">Profile</a>
                    @if (auth()->user()->isAdmin())
                        <a class=""
                           href="{{ route('admin.dashboard') }}">Dashboard</a>
                    @endif
                @endif
            </div>
            <div class="flex gap-6 items-center">
                <form action="{{ route('app.search') }}"
                      autocomplete="off"
                      class="p-3 border-[1px] border-solid border-neutral-200 flex items-center rounded-xl"
                      id="search-form">
                    <input class="bg-transparent outline-transparent w-80"
                           id="search"
                           name="q"
                           placeholder="Title, author..."
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
                    <div class="flex items-center gap-4">
                        <img alt=""
                             class="h-6 rounded-xl"
                             src="{{ asset('assets/profileImages/' . auth()->user()->image) }}">
                        <p class="">{{ auth()->user()->displayName }}</p>
                    </div>
                    <a class=""
                       href="{{ route('security.logout') }}">Logout</a>
                @else
                    <a class=""
                       href="{{ route('security.login') }}">Login</a>
                    <a class=""
                       href="{{ route('security.register') }}">Join</a>
                @endif
            </div>
        </nav>
    @endif
    @yield('body')
    @yield('javascripts')
</body>

</html>
