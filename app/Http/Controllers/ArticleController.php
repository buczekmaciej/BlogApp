<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Services\ArticleServices;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct(private readonly Session $session, private readonly ArticleServices $articleServices)
    {
    }

    public function list(Request $request): View
    {
        $order = explode('_', $request->get('order', 'date_desc'));
        $perPage = $request->get('perpage', 15);

        return view('layouts.articles.list')->with('articles', Article::orderBy(($order[0] === 'title' ? 'title' : 'created_at'), $order[1])->paginate($perPage));
    }

    public function view(Article $article): View
    {
        return view('layouts.articles.view')->with('article', $article)->with('reports', $this->articleServices->getReportReasons());
    }

    public function like(Article $article): RedirectResponse
    {
        $this->articleServices->syncLikes($article);

        return back();
    }

    public function bookmark(Article $article): RedirectResponse
    {
        $this->articleServices->syncBookmarks($article);

        return back();
    }

    public function create(): View
    {
        $this->authorize('create', Article::class);

        return view('layouts.articles.create');
    }

    public function handleCreate(Request $request): RedirectResponse
    {
        $this->authorize('create', Article::class);

        return $this->articleServices->handleFilesUpload($request, 'uploaded', 'uploads', 'articles.createLayout');
    }

    public function createLayout(): View
    {
        $this->authorize('create', Article::class);

        return view('layouts.articles.create-layout')->with('tags', Tag::orderBy('name', 'ASC')->pluck('name'));
    }

    public function handleCreateLayout(Request $request): RedirectResponse
    {
        $this->authorize('create', Article::class);

        return $this->articleServices->createArticle($request);
    }

    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        if (!$this->session->has('existing'))
            $this->session->put('existing', json_decode($article->embeds));

        return view('layouts.articles.edit')->with('title', $article->title)->with('uuid', $article->uuid);
    }

    public function handleEdit(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('update', $article);

        return $this->articleServices->handleFilesUpload($request, 'existing', "images/{$article->uuid}", 'articles.editLayout', ['article' => $article->slug]);
    }

    public function editLayout(Article $article): View
    {
        $this->authorize('update', $article);

        return view('layouts.articles.editLayout')->with('article', $article)->with('unusedTags', $this->articleServices->getUnusedTags($article->tags()->pluck('name')));
    }

    public function handleEditLayout(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('update', $article);

        return $this->articleServices->updateArticle($request, $article, 'articles.view');
    }

    public function removeImage(Request $request): RedirectResponse
    {
        return $this->articleServices->deleteImage($request->get('filename'), $request->get('asset'), $request->get('session'));
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
