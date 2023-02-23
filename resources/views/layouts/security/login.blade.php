@extends('layouts.security.base')

@section('title', 'Login')

@section('form')
    <div class="flex flex-col gap-4">
        <p class="text-5xl font-bold">Sign in</p>
        <p>
            <span>Got no account?</span>
            <a class="text-blue-900 font-bold"
               href="{{ route('security.register') }}">Join now</a>
        </p>
    </div>

    <div class="flex flex-col gap-4 w-full">
        @include('components.form-box', [
            'errors' => $errors,
            'label' => 'Username',
            'id' => 'username',
            'name' => 'username',
        ])

        @include('components.form-box', [
            'errors' => $errors,
            'label' => 'Password',
            'id' => 'password',
            'name' => 'password',
            'type' => 'password',
        ])
        <button class="self-end px-4 py-2 rounded-md bg-purple-800/10 text-purple-800 font-medium hover:bg-purple-800/20">Login</button>
        @csrf
    </div>
@endsection
