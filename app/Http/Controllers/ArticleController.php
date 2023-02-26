<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function list(Request $request): View
    {
        $order = explode('_', $request->get('order', 'date_desc'));
        $perPage = $request->get('perpage', 15);

        return view('layouts.articles.list')->with('articles', Article::orderBy(($order[0] === 'title' ? 'title' : 'created_at'), $order[1])->paginate($perPage));
    }

    public function view(Article $article): View
    {
        return view('layouts.articles.view')->with('article', $article);
    }

    public function like(Article $article): RedirectResponse
    {
        if ($article->likes()->get()->contains(auth()->user())) {
            $article->likes()->detach(auth()->user());
        } else {
            $article->likes()->sync(auth()->user());
        }

        return back();
    }

    public function bookmark(Article $article): RedirectResponse
    {
        if (auth()->user()->bookmarks()->get()->contains($article)) {
            auth()->user()->bookmarks()->detach($article);
        } else {
            auth()->user()->bookmarks()->sync($article);
        }

        return back();
    }

    public function create(Article $article): View
    {
        $this->authorize('create', Article::class);

        return view('layouts.articles.create');
    }

    public function handleCreate(Article $article): RedirectResponse
    {
        $this->authorize('create', Article::class);

        return back();
    }

    public function edit(Article $article): View
    {
        $this->authorize('update', Article::class);

        return view('layouts.articles.edit');
    }

    public function handleEdit(Article $article): RedirectResponse
    {
        $this->authorize('update', Article::class);

        return back();
    }

    public function addComment(Request $request, Article $article): RedirectResponse
    {
        $valid = $request->validate([
            'content' => 'required|string'
        ]);

        if ($valid) {
            $comment = new Comment($valid);
            $comment->author()->associate(auth()->user());
            $article->comments()->save($comment);
        }

        return back();
    }
}
