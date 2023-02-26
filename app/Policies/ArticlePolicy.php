<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function create(User $user): Response
    {
        return $user->isWriter() ? Response::allow() : Response::deny('You are not writer');
    }

    public function update(User $user, Article $article): Response
    {
        return ($user->isWriter() && $article->author()->get()->username === $user->username) || $user->isAdmin() ? Response::allow() : Response::deny('You are not author or admin');
    }

    public function delete(User $user, Article $article): Response
    {
        return $user->isAdmin() ? Response::allow() : Response::deny('You are not admin');
    }

    public function forceDelete(User $user, Article $article): Response
    {
        return $user->isAdmin() ? Response::allow() : Response::deny('You are not admin');
    }
}
