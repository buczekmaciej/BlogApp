<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthorServices;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function list(): View
    {
        return view('layouts.authors.list')->with('authors', User::where('roles', 'LIKE', '%WRITER%')->orderBy('username', 'ASC')->get());
    }
}
