<?php

namespace App\Http\Middleware;

use App\Models\UserAccount;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not logged in, redirect to login page
        if (!session('user_id')) {
            return redirect()->route('login')->withErrors(['login' => 'Please login first.']);
        }

        if ($this->shouldRedirectToPasswordChange($request)) {
            return redirect()->route('password.change')->with('info', 'Please change your password before continuing.');
        }

        $response = $next($request);

        // Prevent protected pages from being cached so browser back cannot reopen them after logout.
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }

    private function shouldRedirectToPasswordChange(Request $request): bool
    {
        if ($request->routeIs('password.change', 'password.update', 'logout')) {
            return false;
        }

        $mustChangePassword = session('must_change_password');

        $roleFromSession = strtolower((string) session('role'));

        // Backfill session state for existing authenticated sessions.
        if ($mustChangePassword === null) {
            if (!in_array($roleFromSession, ['teacher', 'student'], true)) {
                $mustChangePassword = false;
            } elseif (!Schema::hasColumn('user_accounts', 'must_change_password')) {
                $mustChangePassword = true;
            } else {
                $user = UserAccount::find(session('user_id'));
                $role = strtolower((string) ($user->role ?? $roleFromSession));
                $mustChangePassword = $user
                    && in_array($role, ['teacher', 'student'], true)
                    && (bool) $user->must_change_password;
            }

            session(['must_change_password' => (bool) $mustChangePassword]);
        }

        return (bool) $mustChangePassword;
    }
}

