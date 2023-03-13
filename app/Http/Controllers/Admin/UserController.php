<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function list(Request $request): View
    {
        $search = explode("-", $request->get('order', 'username-asc'));

        $users = $search[0] === 'role' ? User::orderByRaw('LENGTH(`roles`) ' . $search[1]) : User::orderBy($search[0], $search[1]);
        return view('layouts.admin.users')->with('users', $users->paginate(50));
    }

    public function changeRoles(Request $request, User $user): RedirectResponse
    {
        $valid = $request->validate([
            'roles' => 'required|array'
        ]);

        if ($valid) {
            $user->roles = json_encode($valid['roles']);

            $user->save();
        }

        return back();
    }

    public function disable(User $user): RedirectResponse
    {
        $user->isDisabled = !$user->isDisabled;

        $user->save();
        return back();
    }

    public function delete(User $user): RedirectResponse
    {
        $user->comments()->detach();
        $user->articles()->detach();
        $user->followedBy()->detach();
        $user->following()->detach();
        $user->reports()->detach();
        $user->delete();
        return back();
    }
}
