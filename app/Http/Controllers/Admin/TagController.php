<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function list(Request $request): View
    {
        $search = explode("-", $request->get('order', 'name-asc'));

        return view('layouts.admin.tags')->with('tags', Tag::withCount('articles')->orderBy($search[0], $search[1])->paginate(50));
    }

    public function create(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'tag' => 'required|string|unique:tags,name'
        ]);

        if ($valid) {
            $tag = new Tag;
            $tag->name = $valid['tag'];

            $tag->save();

            return back();
        }

        return back()->onlyInput('tag');
    }

    public function handleEdit(Request $request, Tag $tag): RedirectResponse
    {
        $valid = $request->validate([
            'name' => 'string|required|unique:tags,name,' . $tag->uuid . ',uuid'
        ]);

        if ($valid) {
            $tag->update($valid);

            return back();
        }

        return back()->onlyInput('name');
    }

    public function delete(Tag $tag): RedirectResponse
    {
        $tag->articles()->detach();
        $tag->delete();
        return back();
    }
}
