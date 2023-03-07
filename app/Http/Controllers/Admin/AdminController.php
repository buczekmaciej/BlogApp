<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminServices;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly AdminServices $adminServices)
    {
    }

    public function dashboard(): View
    {
        return view('layouts.admin.dashboard')->with('data', $this->adminServices->getDashboardItems());
    }
}
