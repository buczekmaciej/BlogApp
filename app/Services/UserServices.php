<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    public function addFollow(User $user): void
    {
        $current = Auth::user();

        if ($user->followedBy()->get()->contains($current)) $user->followedBy()->detach($current);
        else $user->followedBy()->sync($current);
    }
}
