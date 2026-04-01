<?php

namespace App\Http\Controllers;

use App\Support\HomepageContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HomeController extends Controller
{
    public function index(): View
    {
        $content = HomepageContent::load();

        return view('home', [
            'logo' => $content['logo'],
            'whatWeDo' => $content['what_we_do'],
            'ourProcess' => $content['our_process'],
        ]);
    }

    public function asset(string $path): BinaryFileResponse
    {
        $path = HomepageContent::storedPath($path);
        abort_unless($path !== '' && str_starts_with($path, 'homepage/'), 404);
        abort_unless(Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path));
    }
}
