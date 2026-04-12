<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SiteController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }
}
