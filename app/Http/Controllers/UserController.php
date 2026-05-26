<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user_name = (string) ($request->old('username') ?? '');
        $usernameKey = strtolower(trim($user_name));

        $attemptsData = session('login_attempts', []);
        $locksData = session('login_lock_until', []);
        if (!is_array($attemptsData)) {
            $attemptsData = [];
        }
        if (!is_array($locksData)) {
            $locksData = [];
        }

        $lockUntil = 0;
        if ($usernameKey !== '') {
            $lockUntil = (int) ($locksData[$usernameKey] ?? 0);

            // If lock has expired, reset attempts for this username
            if ($lockUntil > 0 && $lockUntil <= time()) {
                unset($locksData[$usernameKey], $attemptsData[$usernameKey]);
                session(['login_attempts' => $attemptsData, 'login_lock_until' => $locksData]);
                $lockUntil = 0;
            }
        }

        // Only show countdown if there are errors (user tried to login while locked)
        $isLocked = false;
        $lockSecondsLeft = null;
        if ($lockUntil > time() && $request->session()->has('errors')) {
            $isLocked = true;
            $lockSecondsLeft = max(0, $lockUntil - time());
        }

        return response()
            ->view('loginPage', [
                'isLocked' => $isLocked,
                'lockSecondsLeft' => $lockSecondsLeft,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user_name = $request->input('username');
        $password = $request->input('password');
        $usernameKey = strtolower(trim((string) $user_name));

        $attemptsData = session('login_attempts', []);
        $locksData = session('login_lock_until', []);
        if (!is_array($attemptsData)) {
            $attemptsData = [];
        }
        if (!is_array($locksData)) {
            $locksData = [];
        }

        $lockUntil = (int) ($locksData[$usernameKey] ?? 0);
        // If lock has expired, reset attempts for this username
        if ($lockUntil > 0 && $lockUntil <= time()) {
            unset($locksData[$usernameKey], $attemptsData[$usernameKey]);
            session(['login_attempts' => $attemptsData, 'login_lock_until' => $locksData]);
            $lockUntil = 0;
        }
        if ($lockUntil > time()) {
            $secondsLeft = max(1, $lockUntil - time());
            return back()->withErrors([
                'login' => "Too many failed attempts. Please try again in {$secondsLeft} second(s)."
            ])->withInput(['username' => $user_name]);
        }

        $user = UserAccount::where('username', $user_name)->first();
        if ($user && Hash::check($password, $user->password)) {
            $mustChangePassword = $this->shouldForcePasswordChange($user);

            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => strtolower((string) $user->role),
                'must_change_password' => $mustChangePassword,
            ]);

            unset($attemptsData[$usernameKey], $locksData[$usernameKey]);
            session(['login_attempts' => $attemptsData, 'login_lock_until' => $locksData]);

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role
            ]);

            if ($mustChangePassword) {
                return redirect()->route('password.change')->with('info', 'Please change your password before continuing.');
            }

            // Redirect to appropriate dashboard based on role
            return $this->redirectByRole($user->role);
        }

        $attempts = (int) ($attemptsData[$usernameKey] ?? 0) + 1;
        $attemptsData[$usernameKey] = $attempts;
        session(['login_attempts' => $attemptsData]);

        $maxAttempts = 3;
        $remaining = max(0, $maxAttempts - $attempts);

        if ($attempts >= $maxAttempts) {
            $lockSeconds = 5;
            $locksData[$usernameKey] = time() + $lockSeconds;
            session(['login_lock_until' => $locksData]);
            return back()->withErrors([
                'login' => "Too many failed attempts. Please try again after {$lockSeconds} second(s)."
            ])->withInput(['username' => $user_name]);
        }

        return back()->withErrors([
            'login' => "Invalid credentials. {$remaining} attempt(s) left."
        ])->withInput(['username' => $user_name]);
    }

    public function studentDashboard(Request $request)
    {
        try {
            $userId = (int) session('user_id');
            $student = Student::where('user_account_id', $userId)->with('degree', 'courses')->first();
            
            if (!$student) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['login' => 'Student record not found.']);
            }
            return view('studentDashboard', ['student' => $student]);
        } catch (\Exception $e) {
            Log::error('Error loading student dashboard', [
                'user_id' => session('user_id'),
                'error' => $e->getMessage()
            ]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['login' => 'Error loading student data. Please try again.']);
        }
    }

    public function adminDashboard()
    {
        return view('adminDashboard');
    }

    public function teacherDashboard()
    {
        $userId = (int) session('user_id');
        try {
            return view('teacherDashboard');
        } catch (\Exception $e) {
            Log::error('Error loading teacher dashboard', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('login')->withErrors(['login' => 'Error loading teacher dashboard. Please try again.']);
        }
    }

    public function teacherCourses()
    {
        return view('teacher.action', [
            'title' => 'My Courses',
            'icon' => 'fa-book',
            'heading' => 'My Courses',
            'message' => 'This section will show your assigned courses. The page is being loaded through jQuery AJAX.',
        ]);
    }

    public function teacherStudents()
    {
        return view('teacher.action', [
            'title' => 'Students',
            'icon' => 'fa-users',
            'heading' => 'Students',
            'message' => 'This section will show students enrolled in your courses. The page is being loaded through jQuery AJAX.',
        ]);
    }

    public function teacherGrades()
    {
        return view('teacher.action', [
            'title' => 'Grades',
            'icon' => 'fa-chart-bar',
            'heading' => 'Grades',
            'message' => 'This section will let you manage grades. The page is being loaded through jQuery AJAX.',
        ]);
    }

    public function changePassword()
    {
        // Check if user is logged in
        if (!session('user_id')) {
            return redirect()->route('login')->withErrors(['login' => 'Please login first.']);
        }

        $user = UserAccount::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'User not found.']);
        }

        if (!$this->shouldForcePasswordChange($user)) {
            return $this->redirectByRole((string) $user->role)->with('info', 'Your password has already been updated.');
        }

        return view('changePassword');
    }

    public function updatePassword(Request $request)
    {
        // Check if user is logged in
        if (!session('user_id')) {
            return redirect()->route('login')->withErrors(['login' => 'Please login first.']);
        }

        $user = UserAccount::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'User not found.']);
        }

        if (!$this->shouldForcePasswordChange($user)) {
            return $this->redirectByRole((string) $user->role)->with('info', 'Your password has already been updated.');
        }

        // Validate the input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.min' => 'New password must be at least 8 characters long.',
            'new_password.different' => 'New password must be different from your current password.',
            'confirm_password.same' => 'Password confirmation does not match.',
        ]);

        try {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // Update password (model casts password as 'hashed')
            $user->password = $request->new_password;

            if (Schema::hasColumn('user_accounts', 'must_change_password')) {
                $user->must_change_password = false;
            }

            $user->save();

            session(['must_change_password' => false]);

            Log::info('Password changed successfully', [
                'user_id' => $user->id,
                'username' => $user->username
            ]);

            return $this->redirectByRole((string) $user->role)->with('success', 'Password changed successfully!');
        } catch (\Exception $e) {
            Log::error('Error changing password', [
                'user_id' => session('user_id'),
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['error' => 'An error occurred while changing your password. Please try again.']);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'username', 'role', 'must_change_password']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }

    /**
     * Redirect user to their appropriate dashboard based on role
     */
    private function redirectByRole(string $role): \Illuminate\Http\RedirectResponse
    {
        $role = strtolower($role);

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('password.change'),
        };
    }

    private function shouldForcePasswordChange(UserAccount $user): bool
    {
        $role = strtolower((string) $user->role);
        if (!in_array($role, ['teacher', 'student'], true)) {
            return false;
        }

        if (!Schema::hasColumn('user_accounts', 'must_change_password')) {
            return true;
        }

        return (bool) $user->must_change_password;
    }

    /**
     * Show form to add a new student
     */
    public function showAddStudentForm()
    {
        return view('admin.addStudent');
    }

    /**
     * Store a new student
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'contact_no' => 'required|string|max:20',
            'degree_id' => 'required|integer|exists:degrees,id',
        ]);

        try {
            $student = Student::create($request->only(['fname', 'mname', 'lname', 'email', 'contact_no', 'degree_id']));
            
            Log::info('Student created successfully', ['student_id' => $student->id]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student added successfully',
                    'student' => $student,
                    'redirect_url' => route('students.index'),
                ], 201);
            }
            return redirect()->route('admin.dashboard')->with('success', 'Student added successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating student', ['error' => $e->getMessage()]);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error adding student. Please try again.'], 500);
            }
            return back()->withErrors(['error' => 'Error adding student. Please try again.']);
        }
    }

    /**
     * Show form to add a new teacher
     */
    public function showAddTeacherForm()
    {
        return view('admin.addTeacher');
    }

    /**
     * Store a new teacher
     */
    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:user_accounts,email',
            'username' => 'required|string|max:255|unique:user_accounts,username',
            'password' => 'required|string|min:8',
        ]);

        try {
            $hashedPassword = Hash::make($request->password);
            
            $teacherData = [
                'username' => $request->username,
                'email' => $request->email,
                'password' => $hashedPassword,
                'role' => 'teacher',
                'is_active' => 1,
            ];

            if (Schema::hasColumn('user_accounts', 'must_change_password')) {
                $teacherData['must_change_password'] = true;
            }

            $teacher = UserAccount::create($teacherData);

            Log::info('Teacher account created successfully', ['username' => $request->username]);
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Teacher account created successfully', 'teacher' => $teacher], 201);
            }
            return redirect()->route('admin.dashboard')->with('success', 'Teacher account created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating teacher account', ['error' => $e->getMessage()]);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error adding teacher. Please try again.'], 500);
            }
            return back()->withErrors(['error' => 'Error adding teacher. Please try again.']);
        }
    }


}
