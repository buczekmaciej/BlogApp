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

<body class="bg-slate-50 text-neutral-900 flex {{ $__env->yieldContent('bodyClass', 'flex-col items-center') }}">
    @includeUnless(Route::is('security.*'), 'components.app-nav')
    @yield('body')
    @yield('javascripts')
</body>

</html>
