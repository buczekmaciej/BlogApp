<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function list(Request $request)
    {
        $order = explode('_', $request->get('order', 'date_desc'));
        $perPage = $request->get('perpage', 15);

        return view('layouts.articles.list')->with('articles', Article::orderBy(($order[0] === 'title' ? 'title' : 'created_at'), $order[1])->paginate($perPage));
    }

    public function view(Article $article)
    {
    }
}
