<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = CustomPage::where('slug', $slug)->published()->firstOrFail();
        return view('pages.show', compact('page'));
    }
}
