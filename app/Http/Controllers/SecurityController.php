<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RedirectServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SecurityController extends Controller
{
    public function __construct(private readonly RedirectServices $redirectServices)
    {
    }

    public function login(): View
    {
        $this->redirectServices->declareIntended();

        return view('layouts.security.login');
    }

    public function handleLogin(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:4'
        ]);

        if (Auth::attempt($valid, $request->get('remember_me'))) {
            $request->session()->regenerate();

            return $this->redirectServices->getIntended();
        }

        return back()->withErrors('Invalid credentials');
    }

    public function register(): View
    {
        $this->redirectServices->declareIntended();

        return view('layouts.security.register');
    }

    public function handleRegister(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4',
        ]);

        if ($valid) {
            $data = $request->merge(['password' => Hash::make($request->get('password'))])->all();

            $user = new User($data);

            if ($user->save()) {
                Auth::login($user, $request->get('remember_me'));

                return $this->redirectServices->getIntended();
            } else return back()->withErrors('Failed to create account')->onlyInput('username', 'email');
        } else return back()->withErrors('Given credentials are not matching requirements')->onlyInput('username', 'email');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return back();
    }
}
