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
            'error' => $errors->any() ? $errors->first() : null,
            'label' => 'Username',
            'id' => 'username',
            'name' => 'username',
            'value' => old('username'),
        ])

        @include('components.form-box', [
            'error' => $errors->any() ? $errors->first() : null,
            'label' => 'Password',
            'id' => 'password',
            'name' => 'password',
            'type' => 'password',
            'value' => null,
        ])
        <div class="flex justify-between items-center">
            <div class="flex gap-2 cursor-pointer [&>*]:cursor-pointer">
                <input id="remember"
                       name="remember_me"
                       type="checkbox"
                       value="1">
                <label for="remember">Remember me</label>
            </div>
            <button class="form-btn">Login</button>
        </div>
        @csrf
    </div>
@endsection
