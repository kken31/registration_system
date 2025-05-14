<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontendAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('frontend.login.form')->withErrors(['session' => 'Please login to access this page.']);
        }
        // Optionally, you could make a call to /api/auth/me here to verify the token is still valid
        // For simplicity, we're trusting the session token for now.
        return $next($request);
    }
}
