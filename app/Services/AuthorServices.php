<?php

namespace App\Services;

use App\Models\User;

class AuthorServices
{
    public function getWriters()
    {
        return User::where('roles', 'LIKE', '%WRITER%')->orderBy('username', 'ASC')->get();
    }
}
