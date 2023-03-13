<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\View\View;

class TagController extends Controller
{
    public function list(): View
    {
        return view('layouts.tags.list')->with('tags', Tag::orderBy('name', 'ASC')->get());
    }

    public function view(Tag $tag): View
    {
        return view('layouts.tags.view')->with('tag', $tag)->with('articles', $tag->articles()->paginate(10));
    }
}
