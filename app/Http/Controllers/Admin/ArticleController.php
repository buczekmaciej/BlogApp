<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function list(Request $request): View
    {
        $search = explode("-", $request->get('order', 'created_at-desc'));
        return view('layouts.admin.articles')->with('articles', Article::select('articles.uuid', 'title', 'slug', 'embeds', 'thumbnail', 'author_uuid', 'articles.created_at', 'articles.updated_at')->join('users', 'articles.author_uuid', 'users.uuid')->orderBy($search[0], $search[1])->paginate(50));
    }

    public function delete(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);

        $uuidStripped = $article->getStrippedUuid();
        if (File::exists(public_path("assets/images/{$uuidStripped}"))) {
            File::deleteDirectory(public_path("assets/images/{$uuidStripped}"));
        }

        $article->comments()->delete();
        $article->reports()->delete();
        $article->warrant()->delete();
        $article->delete();

        return back();
    }
}
