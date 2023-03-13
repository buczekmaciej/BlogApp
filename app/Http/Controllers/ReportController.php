<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Report;
use App\Services\ArticleServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function submitReport(Request $request, ArticleServices $articleServices): RedirectResponse
    {
        $reason = $articleServices->getReportReasons()[$request->input('reason')];

        if ($el = $request->input('article_slug')) {
            $article = Article::select('uuid')->where('slug', $el)->get()[0];
            $report = new Report();
            $report->reason = $reason;
            $report->author()->associate(auth()->user());
            $report->article()->associate($article);

            $report->save();
        } else if ($el = $request->input('comment_id')) {
            $report = new Report();
            $report->reason = $reason;
            $report->author()->associate(auth()->user());
            $report->comment()->associate($el);

            $report->save();
        }


        return back();
    }
}
