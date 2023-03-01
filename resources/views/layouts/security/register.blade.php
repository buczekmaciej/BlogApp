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
            'error' => $errors->has('username') ? $errors->first('username') : null,
            'label' => 'Username',
            'id' => 'username',
            'name' => 'username',
            'value' => old('username'),
        ])
        @include('components.form-box', [
            'error' => $errors->has('email') ? $errors->first('email') : null,
            'label' => 'Email',
            'id' => 'email',
            'name' => 'email',
            'type' => 'email',
            'value' => old('email'),
        ])

        @include('components.form-box', [
            'error' => $errors->has('password') ? $errors->first('password') : null,
            'label' => 'Password',
            'id' => 'password',
            'name' => 'password',
            'type' => 'password',
            'extra' => "<span class='input-help'>Must be at least 4 characters long</span>",
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
            <button class="form-btn">Register</button>
        </div>
        @csrf
    </div>
@endsection
