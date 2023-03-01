<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserServices $userServices)
    {
    }

    public function profile(User $user)
    {
    }

    public function settings()
    {
    }

    public function updateSettings()
    {
    }

    public function newFollower(User $user)
    {
        if ($user->username !== auth()->user()->username) {
            $this->userServices->addFollow($user);
        }

        return back();
    }
}
