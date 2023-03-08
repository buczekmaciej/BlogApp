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
    public function list(): View
    {
        return view('layouts.admin.articles')->with('articles', Article::select('uuid', 'title', 'slug', 'embeds', 'thumbnail', 'author_uuid', 'created_at', 'updated_at')->paginate(50));
    }

    public function delete(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);

        $uuidStripped = $article->getStrippedUuid();
        if (File::exists(public_path("assets/images/{$uuidStripped}"))) {
            File::deleteDirectory(public_path("assets/images/{$uuidStripped}"));
        }

        $article->delete();

        return back();
    }
}
