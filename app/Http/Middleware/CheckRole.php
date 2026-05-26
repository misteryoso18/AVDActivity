<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // If user is not logged in, redirect to login page
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        // Check if user has one of the required roles
        $userRole = strtolower((string) session('role'));
        $allowedRoles = array_map(fn ($role) => strtolower((string) $role), $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            // Redirect to appropriate dashboard based on role
            return $this->redirectByRole($request, $userRole);
        }

        return $next($request);
    }

    /**
     * Redirect user to their appropriate dashboard based on role
     */
    private function redirectByRole(Request $request, string $role): Response
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => $this->clearInvalidSessionAndRedirectToLogin($request),
        };
    }

    /**
     * Ensure unknown role sessions are invalidated before redirecting to login.
     */
    private function clearInvalidSessionAndRedirectToLogin(Request $request): Response
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
