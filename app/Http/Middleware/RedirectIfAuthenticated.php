<?php

namespace App\Http\Middleware;

use App\Models\UserAccount;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is already logged in, redirect them to their appropriate dashboard
        if (session('user_id')) {
            if ($this->mustChangePassword()) {
                return redirect()->route('password.change');
            }

            $role = strtolower((string) session('role'));
            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                default => $this->clearInvalidSessionAndContinue($request, $next),
            };
        }

        return $next($request);
    }

    private function mustChangePassword(): bool
    {
        $mustChangePassword = session('must_change_password');
        if ($mustChangePassword !== null) {
            return (bool) $mustChangePassword;
        }

        $roleFromSession = strtolower((string) session('role'));
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

        return (bool) $mustChangePassword;
    }

    /**
     * Prevent login redirect loops for invalid/incomplete session role data.
     */
    private function clearInvalidSessionAndContinue(Request $request, Closure $next): Response
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $next($request);
    }
}
