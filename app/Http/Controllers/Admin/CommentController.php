<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function list(Request $request): View
    {
        $search = explode("-", $request->get('order', 'comments.created_at-desc'));

        return view('layouts.admin.comments')->with('comments', Comment::join('users', 'comments.author_uuid', 'users.uuid')->orderBy($search[0], $search[1])->paginate(50));
    }

    public function delete(Comment $comment): RedirectResponse
    {
        $comment->article()->disassociate();
        $comment->author()->disassociate();
        $comment->reports()->delete();
        $comment->delete();

        return back();
    }
}
