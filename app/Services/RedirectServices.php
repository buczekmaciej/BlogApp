<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class RedirectServices
{
    public function declareIntended(): void
    {
        if (!Session::has('url.intended')) {
            Session::put('url.intended', url()->previous());
        }
    }

    public function getIntended(): RedirectResponse
    {
        $intended = redirect()->intended('/');
        $this->clearIntended();

        return $intended;
    }

    public function clearIntended(): void
    {
        if (Session::has('url.intended')) {
            Session::forget('url.intended');
        }
    }
}
