@extends('layouts.security.base')

@section('title', 'Register')

@section('form')
    <div class="flex flex-col gap-4">
        <p class="text-5xl font-bold">Join</p>
        <p>
            <span>Got an account?</span>
            <a class="text-blue-900 font-bold"
               href="{{ route('security.login') }}">Sign in</a>
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
            'label' => 'Email',
            'id' => 'email',
            'name' => 'email',
            'type' => 'email',
        ])

        @include('components.form-box', [
            'errors' => $errors,
            'label' => 'Password',
            'id' => 'password',
            'name' => 'password',
            'type' => 'password',
        ])
        <div class="flex justify-between items-center">
            <div class="flex gap-2 cursor-pointer [&>*]:cursor-pointer">
                <input id="remember"
                       name="remember_me"
                       type="checkbox"
                       value="1">
                <label for="remember">Remember me</label>
            </div>
            <button class="self-end px-4 py-2 rounded-md bg-purple-800/10 text-purple-800 font-medium hover:bg-purple-800/20">Register</button>
        </div>
        @csrf
    </div>
@endsection
