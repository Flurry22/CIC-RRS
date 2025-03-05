<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        // Check if the user is authenticated with any of the role-specific guards
        if (
            Auth::guard('academic_administrator')->check() ||
            Auth::guard('research_staff')->check() ||
            Auth::guard('researcher')->check()
        ) {
            return $next($request); // Continue to the next request
        }

        // Redirect to the login page if no guard matches
        return redirect()->route('login');
    }

    protected function redirectTo($request)
    {
        // Handle AJAX requests differently
        return $request->expectsJson() ? null : route('login');
    }
}
