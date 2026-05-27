<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PSUController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExportController;

// Maintenance route - ALWAYS accessible
Route::get('/maintenance', function () {
    return view('maintenance');
})->name('maintenance');

// All other routes with maintenance middleware
Route::middleware('App\Http\Middleware\DownForMaintnanceMW')->group(function () {

    // Default entry point: always show login page
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    // Public routes (no login required)
    Route::get('/login',[UserController::class, 'login'])->middleware('auth.redirect')->name('login');
    Route::post('/login',[UserController::class, 'authenticate'])->name('authenticate');
    Route::get('/logout',[UserController::class, 'logout'])->name('logout');

    // Protected routes (login required)
    Route::middleware(\App\Http\Middleware\CheckLogin::class)->group(function () {
        Route::get('/dashboard', function () {
            $role = strtolower((string) session('role'));

            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                default => redirect()->route('logout'),
            };
        })->name('home');

        Route::get('/psu/welcome', [PSUController::class, 'welcome'])->name('psu.welcome');
        Route::get('/psu/mission', [PSUController::class, 'mission'])->name('psu.mission');
        Route::get('/psu/vision', [PSUController::class, 'vision'])->name('psu.vision');
        Route::get('/psu/eoms-policy', [PSUController::class, 'EOMSPolicy'])->name('psu.eoms.policy');

        Route::get('/change-password', [UserController::class, 'changePassword'])->name('password.change');
        Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update');

        Route::get('/greetings',[ClientController::class, 'displayGreetings']);
        Route::get('/about',[StudentController::class, 'about'])->name('about');

        Route::get('/user_profile', [PageController::class, 'userProfile'])->name('user.profile');
        Route::get('/user_posts', [PageController::class, 'userPosts']);
        Route::get('/student__courses', [PageController::class, 'studentCourse']);
        Route::get('/enrolled-students', [PageController::class, 'enrolledStudents']);
        Route::get('/setup-test-data', [PageController::class, 'setupTestData']);
        // Role-based dashboard routes
        Route::middleware('role:student')->group(function () {
            Route::get('/student-dashboard', [UserController::class, 'studentDashboard'])->name('student.dashboard');
        });

        Route::middleware('role:teacher')->group(function () {
            Route::get('/teacher-dashboard', [UserController::class, 'teacherDashboard'])->name('teacher.dashboard');
            Route::get('/teacher-courses', [UserController::class, 'teacherCourses'])->name('teacher.courses');
            Route::get('/teacher-students', [UserController::class, 'teacherStudents'])->name('teacher.students');
            Route::get('/teacher-grades', [UserController::class, 'teacherGrades'])->name('teacher.grades');
        });

        Route::middleware('role:admin')->group(function () {
            Route::get('/admin-dashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
            Route::get('/logs', [PageController::class, 'logs'])->name('logs');

            Route::resource('students', StudentController::class);
            Route::resource('degrees', DegreeController::class);

            Route::get('/admin/add-student', [UserController::class, 'showAddStudentForm'])->name('admin.add.student');
            Route::post('/admin/store-student', [UserController::class, 'storeStudent'])->name('admin.store.student');
            Route::get('/admin/add-teacher', [UserController::class, 'showAddTeacherForm'])->name('admin.add.teacher');
            Route::post('/admin/store-teacher', [UserController::class, 'storeTeacher'])->name('admin.store.teacher');

            // Export routes
            Route::get('/exports', [ExportController::class, 'index'])->name('export.index');
            Route::get('/exports/students/pdf', [ExportController::class, 'exportStudentsPDF'])->name('export.students.pdf');
            Route::get('/exports/students/excel', [ExportController::class, 'exportStudentsExcel'])->name('export.students.excel');
            Route::get('/exports/degrees/pdf', [ExportController::class, 'exportDegreesPDF'])->name('export.degrees.pdf');
            Route::get('/exports/degrees/excel', [ExportController::class, 'exportDegreesExcel'])->name('export.degrees.excel');
            Route::get('/exports/courses/pdf', [ExportController::class, 'exportCoursesPDF'])->name('export.courses.pdf');
            Route::get('/exports/courses/excel', [ExportController::class, 'exportCoursesExcel'])->name('export.courses.excel');
            Route::get('/exports/users/pdf', [ExportController::class, 'exportUsersPDF'])->name('export.users.pdf');
            Route::get('/exports/users/excel', [ExportController::class, 'exportUsersExcel'])->name('export.users.excel');
        });


    });

    // If user types any other URL, force redirect (security)
    Route::fallback(function () {
        if (!session('user_id')) {
            return view('loginPage', ['isLocked' => false, 'lockSecondsLeft' => null]);
        }

        return redirect()->route('home');
    });

});