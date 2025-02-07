<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Timeout period in seconds (e.g., 15 minutes).
     */
    protected $timeout = 5 * 60; // 15 minutes
    // protected $timeout = 1; // 1s for test


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check if the admin is authenticated
        if (Auth::guard('admin')->check()) {
            $lastActivity = session('lastActivityTime', time());

            // If the elapsed time since last activity is greater than the timeout
            if (time() - $lastActivity > $this->timeout) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('dashboard.login.form')
                    ->withErrors(['message' => 'Your session has expired due to inactivity.']);
            }

            // Update the last activity time stamp
            session(['lastActivityTime' => time()]);
        }

        return $next($request);
    }
}
