<?php

namespace App\Services;

use App\Models\User;

class SecurityServices
{
    public function declareIntended(): void
    {
        if (!session()->get('url.intended')) {
            session()->put('url.intended', url()->previous());
        }
    }
}
