<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminAuthenticated
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if ((bool) $request->session()->get('admin_authenticated', false)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
