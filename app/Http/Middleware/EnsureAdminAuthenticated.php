<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (! (bool) $request->session()->get('admin_authenticated', false)) {
            return redirect()->route('admin.login')->with('status', 'Please log in to continue.');
        }

        return $next($request);
    }
}
