<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthorServices;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function __construct(private readonly AuthorServices $authorServices)
    {
    }

    public function list(): View
    {
        return view('layouts.authors.list')->with('authors', $this->authorServices->getWriters());
    }

    public function view(Request $request, User $user): View
    {
        return view('layouts.authors.view')->with('author', $user)->with('articles', $user->articles()->orderBy('created_at', 'DESC')->paginate(10));
    }
}
