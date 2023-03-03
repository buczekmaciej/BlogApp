<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserServices $userServices)
    {
    }

    public function profile(User $user, ?string $view = null): View|RedirectResponse
    {
        $v = view('layouts.user.profile')->with('user', $user);
        if ((!$view || $view === 'articles') && $user->isWriter()) {
            $v = $v->with('data', $user->articles()->orderBy('created_at', 'DESC')->paginate(10))->with('view', 'articles');
        } else if ((!$view && !$user->isWriter()) || $view === 'comments') {
            $v = $v->with('data', $user->comments()->orderBy('created_at', 'DESC')->paginate(50))->with('view', 'comments');
        } else if ($view === 'followers') {
            $v = $v->with('data', $user->followedBy()->paginate(100))->with('view', 'followers');
        } else if ($view === 'following') {
            $v = $v->with('data', $user->following()->paginate(100))->with('view', 'following');
        } else {
            return redirect()->route('user.profile', $user->username);
        }

        return $v;
    }

    public function settings(): View
    {
        return view('layouts.user.settings');
    }

    public function updateSettings(): RedirectResponse
    {
        return back();
    }

    public function newFollower(User $user)
    {
        if ($user->username !== auth()->user()->username) {
            $this->userServices->addFollow($user);
        }

        return back();
    }
}
