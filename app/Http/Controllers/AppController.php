<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AppServices;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppController extends Controller
{
    public function __construct(private readonly AppServices $appServices)
    {
    }

    public function index(): View
    {
        return view('layouts.app')->with('article', \App\Models\Article::with('author')->first());
    }

    public function search(Request $request): View
    {
        $q = $request->q;
        return view('layouts.search')->with('query', $q)->with('results', $this->appServices->getSearchMatchingData($q));
    }
}
