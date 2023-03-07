<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Models\Warrant;
use Carbon\Carbon;

class AdminServices
{
    public function getDashboardItems(): array
    {
        $articles = Article::all();
        $reports = Report::all();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'created_users' => User::count(),
            'created_articles' => $articles->count(),
            'active_warrants' => Warrant::count(),
            'pending_reports' => $reports->count(),
            'assigned_authors' => User::where('roles', 'LIKE', '%WRITER%')->count(),
            'assigned_admins' => User::where('roles', 'LIKE', '%ADMIN%')->count(),
            'new_users' => User::where('created_at', '>', $thisMonth)->count(),
            'new_articles' => $articles->where('created_at', '>', $thisMonth)->count(),
            'new_reports' => $reports->where('created_at', '>', $thisMonth)->count(),
            'new_comments' => Comment::where('created_at', '>', $thisMonth)->count(),
        ];
    }
}
