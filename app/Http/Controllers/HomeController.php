<?php

namespace App\Http\Controllers;

use App\Support\HomepageContent;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $content = HomepageContent::load();

        return view('home', [
            'whatWeDo' => $content['what_we_do'],
            'ourProcess' => $content['our_process'],
        ]);
    }
}
