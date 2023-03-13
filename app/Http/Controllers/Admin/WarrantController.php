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

        return view('layouts.admin.warrants')->with('warrants', Warrant::orderBy($search[0], $search[1])->paginate(50));
    }

    public function handleEdit(Request $request, Warrant $warrant): RedirectResponse
    {
        $valid = $request->validate([
            'reason' => 'string|required'
        ]);

        if ($valid) {
            $warrant->update($valid);
        }

        return back();
    }

    public function handleCreate(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'reason' => 'string|required',
            'article_uuid' => 'uuid|required'
        ]);

        if ($valid) {
            $warrant = new Warrant($valid);
            $warrant->author()->associate(auth()->user());
            $warrant->article()->associate($valid['article_uuid']);
            $warrant->status = 'disabled';

            $warrant->save();
        }

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
