<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warrant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarrantController extends Controller
{
    public function list(Request $request): View
    {
        $search = explode("-", $request->get('order', 'created_at-desc'));

        return view('layouts.admin.warrants.list')->with('warrants', Warrant::orderBy($search[0], $search[1])->paginate(50));
    }

    public function edit(Warrant $warrant): View
    {
        return view('layouts.admin.warrants.edit')->with('warrant', $warrant);
    }

    public function handleEdit(Request $request, Warrant $warrant): RedirectResponse
    {
        return back();
    }

    public function delete(Warrant $warrant): RedirectResponse
    {
        $warrant->article()->disassociate();
        $warrant->author()->disassociate();
        $warrant->delete();
        return back();
    }
}
