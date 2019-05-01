<?php

namespace App\Http\Controllers\Manage;

class IndexController extends Controller
{
    public function index()
    {
        return view('manage.index');
    }

    public function work()
    {
        return view('manage.work');
    }

}
