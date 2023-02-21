<!DOCTYPE html>
<html class="min-h-screen"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1"
          name="viewport">

    <title>@yield('title', 'Default page') | Blog</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100 text-neutral-900 {{ $__env->yieldContent('bodyClass', 'flex items-center justify-center w-screen h-screen') }}">
    @yield('body')
    @yield('javascripts')
</body>

</html>
