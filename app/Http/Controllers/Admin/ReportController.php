<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function list(Request $request): View
    {
        $search = explode("-", $request->get('order', 'created_at-desc'));

        if ($search[0] === 'article') $reports = Report::orderByRaw('-`article_uuid` ' . $search[1]);
        else if ($search[0] === 'comment') $reports = Report::orderByRaw('-`comment_uuid` ' . $search[1]);
        else $reports = Report::orderBy($search[0], $search[1]);

        return view('layouts.admin.reports')->with('reports', $reports->paginate(50));
    }

    public function delete(Report $report): RedirectResponse
    {
        $report->author()->disassociate();
        $report->article()->disassociate();
        $report->comment()->disassociate();
        $report->save();

        $report->delete();
        return back();
    }
}
