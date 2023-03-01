<?php

namespace App\Services;

use App\Models\Article;

class ArticleServices
{
    public function syncLikes(Article $article): void
    {
        if ($article->likes()->get()->contains(auth()->user())) {
            $article->likes()->detach(auth()->user());
        } else {
            $article->likes()->sync(auth()->user());
        }
    }

    public function syncBookmarks(Article $article): void
    {
        if (auth()->user()->bookmarks()->get()->contains($article)) {
            auth()->user()->bookmarks()->detach($article);
        } else {
            auth()->user()->bookmarks()->sync($article);
        }
    }

    public function titleToSlug(string $title)
    {
        return implode("-", explode(' ', strtolower($title)));
    }
}
