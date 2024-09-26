<?php

namespace App\Http\Controllers\User;

use App\Models\Post;

class PostController
{
    public function index()
    {
        return view('vendor.frontend.pages.index');
    }

    public function detail($id)
    {
        return view('vendor.frontend.pages.detail')->with('id', $id);
    }
}
