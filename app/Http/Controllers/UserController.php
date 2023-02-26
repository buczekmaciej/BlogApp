<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // auth()->user()->followedBy()->sync(User::class);
    // auth()->user()->followedBy()->detach(User::class);
    public function profile(User $user)
    {
    }

    public function settings()
    {
    }

    public function updateSettings()
    {
    }
}
