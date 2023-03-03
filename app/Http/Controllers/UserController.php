<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        $timezones = timezone_identifiers_list();

        return view('layouts.user.settings')->with('timezones', $timezones);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->user()->uuid . ',uuid',
            'timezone' => 'timezone',
            'image' => 'nullable|file|max:5000|mimes:png,jpg,jpeg',
            'displayName' => 'nullable|string',
            'birthDate' => 'nullable|date',
            'location' => 'nullable|string',
            'bio' => 'nullable|string',
            'isSubscribed' => 'boolean'
        ]);

        if ($valid) {
            $user = User::where('username', auth()->user()->username)->first();
            if (File::exists(public_path('assets/profileImages/' . $user->username))) {
                File::delete(public_path('assets/profileImages/' . $user->username));
            }
            $file = $request->files->get('image');

            $newName = $user->username . '.' . $file->getClientOriginalExtension();

            $file->move('assets/profileImages', $newName);

            $data = array_merge(['birthDate' => Carbon::createFromFormat('Y-m-d', $valid['birthDate'], 'UTC')], $valid);
            $data['image'] = $newName;

            $user->update($data);
        }

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
