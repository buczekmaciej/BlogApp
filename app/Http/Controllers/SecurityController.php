<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SecurityServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends Controller
{
    public function __construct(private readonly SecurityServices $securityServices)
    {
    }

    public function login(): View
    {
        $this->securityServices->declareIntended();

        return view('layouts.security.login');
    }

    public function handleLogin(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($valid)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors('Invalid credentials');
    }

    public function register(): View
    {
        $this->securityServices->declareIntended();

        return view('layouts.security.register');
    }

    public function handleRegister(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unqiue:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($valid) {
            $data = $request->merge(['password' => Hash::make($request->get('password'))])->all();

            $user = new User($data);

            if ($user->save()) {
                Auth::login($user);

                return redirect()->intended('/');
            } else return back()->withErrors('Failed to create account')->onlyInput('username', 'email');
        } else return back()->withErrors('Given credentials are not matching requirements')->onlyInput('username', 'email');

        return redirect()->intended('/');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return back();
    }
}
