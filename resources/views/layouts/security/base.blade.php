@extends('base')

@section('bodyClass', 'flex-col items-center justify-center w-screen h-screen text-slate-50 bg-gradient-to-br from-blue-900 via-blue-600 to-blue-300')

@section('body')
    @include('components.security-nav')
    <form class="rounded-lg p-8 flex flex-col gap-8 items-start w-1/3 bg-slate-50 text-neutral-900"
          method="POST">
        @yield('form')
        @if ($errors->any())
            @include('components.error', ['errors' => $errors->all()])
        @endif
    </form>
@endsection
