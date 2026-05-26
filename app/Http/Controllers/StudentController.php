<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\UserAccount;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // ----------------------------------------------------------
        // AJAX live-search support (jQuery #studentSearch input)
        // GET /students?search=juan  → filters by name, email, degree
        // ----------------------------------------------------------
        $search = trim((string) $request->query('search', ''));

        try {
            $query = Student::with(['degree', 'userAccount']);

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('fname',      'like', "%{$search}%")
                      ->orWhere('lname',    'like', "%{$search}%")
                      ->orWhere('mname',    'like', "%{$search}%")
                      ->orWhere('email',    'like', "%{$search}%")
                      ->orWhere('contact_no', 'like', "%{$search}%")
                      ->orWhereHas('degree', function ($dq) use ($search) {
                          $dq->where('title', 'like', "%{$search}%");
                      });
                });
            }

            $students = $query->paginate(10)->appends($request->only('search'));

        } catch (\Throwable $e) {
            Log::error('Unable to load students list due to database connection issue.', [
                'error' => $e->getMessage(),
            ]);

            // Keep the page usable even when the DB server is temporarily unavailable.
            $students = new LengthAwarePaginator(
                collect(),
                0,
                10,
                (int) $request->query('page', 1),
                [
                    'path'  => $request->url(),
                    'query' => $request->query(),
                ]
            );

            if ($request->ajax() && $request->boolean('table')) {
                return view('students.partials.table', ['students' => $students])
                    ->with('error', 'Database connection failed. Please start MySQL and try again.');
            }

            return view('students.index', ['students' => $students])
                ->with('error', 'Database connection failed. Please start MySQL and try again.');
        }

        if ($request->ajax() && $request->boolean('table')) {
            return view('students.partials.table', ['students' => $students]);
        }
        // AJAX + table=1  → return only the partial (for loadStudents() polling)
        // AJAX + no table  → return full view (for ajaxNavigate page transitions)
        if ($request->ajax() && $request->boolean('table')) {
            return view('students.partials.table', ['students' => $students]);
        }

        return view('students.index', ['students' => $students]);
    }

    public function home()
    {
        return view('clientDashboard');
    }

    public function about()
    {
        return view('clientAboutUs');
    }

    public function create()
    {
        $degrees = \App\Models\Degree::all();
        return view('students.create', ['degrees' => $degrees]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fname' => 'required|min:2',
            'mname' => 'required|string|max:1',
            'lname' => 'required|min:2',
            'email' => 'required|email|unique:students,email|unique:user_accounts,email',
            'contact_no' => 'required|digits:11',
            'username' => 'required|min:3|unique:user_accounts,username',
            'password' => 'required|min:6',
            'degree_id' => 'required'

        ]);

        if ($validator->fails()){
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect ()->back()->withErrors($validator)->withInput();
        }

        try {
            $student = DB::transaction(function () use ($request) {
                $userData = [
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'role' => 'student',
                    'is_active' => 1,
                ];

                if (Schema::hasColumn('user_accounts', 'must_change_password')) {
                    $userData['must_change_password'] = true;
                }

                $user = UserAccount::create($userData);

                return Student::create([
                    'user_account_id' => $user->id,
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'lname' => $request->input('lname'),
                    'email' => $request->input('email'),
                    'contact_no' => $request->input('contact_no'),
                    'degree_id' => $request->input('degree_id'),
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Student creation failed', [
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unable to create student. Please check your inputs and try again.'], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Unable to create student. Please check your inputs and try again.'])
                ->withInput();
        }

        // Log the new student creation with degree information
        Log::info('Student created successfully', [
            'student_id' => $student->id,
            'student_name' => $student->fname . ' ' . $student->mname . ' ' . $student->lname,
            'email' => $student->email,
            'contact_no' => $student->contact_no,
            'degree' => $student->degree?->title ?? 'N/A',
            'degree_id' => $student->degree_id,
            'timestamp' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'student' => $student,
                'redirect_url' => route('students.index'),
            ], 201);
        }

        return redirect()->route('students.index')->with('success', 'Student created successfully');
    }

    public function show(string $id)
    {
        $student = Student::with(['degree', 'userAccount'])->find($id);

        if (!$student) {
            return redirect()->route('students.index')
                           ->with('error', 'Student not found!');
        }

        return view('students.show', ['student' => $student]);
    }

    public function edit(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return redirect()->route('students.index')
                           ->with('error', 'Student not found!');
        }

        $degrees = \App\Models\Degree::all();
        return view('students.edit', ['student' => $student, 'degrees' => $degrees]);
    }

    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Student not found!'], 404);
            }
            return redirect('/students')->with('error', 'Student not found!');
        }

        $validator = Validator::make($request->all(), [
            'fname' => 'required|min:2',
            'mname' => 'required|string|max:1',
            'lname' => 'required|min:2',
            'email' => 'required|email|unique:students,email,' . $id,
            'contact_no' => 'required|digits:11',
            'degree_id' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Capture old values before update for logging
        $oldDegree = $student->degree?->title ?? 'N/A';
        $oldName = $student->fname . ' ' . $student->mname . ' ' . $student->lname;
        $oldEmail = $student->email;
        $oldPhone = $student->contact_no;

        try {
            $student->update([
                'fname' => $request->input('fname'),
                'mname' => $request->input('mname'),
                'lname' => $request->input('lname'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'degree_id' => $request->input('degree_id'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Student update failed', [
                'student_id' => $id,
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unable to update student. Please try again.'], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Unable to update student. Please try again.'])
                ->withInput();
        }

        // Log the student update with before/after details
        $student->refresh(); // Refresh to get updated degree relationship
        Log::info('Student updated successfully', [
            'student_id' => $student->id,
            'old_name' => $oldName,
            'new_name' => $student->fname . ' ' . $student->mname . ' ' . $student->lname,
            'old_email' => $oldEmail,
            'new_email' => $student->email,
            'old_phone' => $oldPhone,
            'new_phone' => $student->contact_no,
            'old_degree' => $oldDegree,
            'new_degree' => $student->degree?->title ?? 'N/A',
            'timestamp' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'student' => $student,
                'redirect_url' => route('students.index'),
            ]);
        }

        return redirect()->route('students.index')->with('message', 'Student Updated Successfully');
    }

    public function destroy(Request $request, string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Student not found!'], 404);
            }

            return redirect()->route('students.index')
                           ->with('error', 'Student not found!');
        }

        // Capture student details before deletion for logging
        $deletedStudentData = [
            'student_id' => $student->id,
            'student_name' => $student->fname . ' ' . $student->mname . ' ' . $student->lname,
            'email' => $student->email,
            'contact_no' => $student->contact_no,
            'degree' => $student->degree?->title ?? 'N/A',
            'degree_id' => $student->degree_id,
            'user_account_id' => $student->user_account_id,
        ];

        try {
            DB::transaction(function () use ($student) {
                $userAccountId = $student->user_account_id;

                $student->delete();

                if ($userAccountId) {
                    UserAccount::whereKey($userAccountId)->delete();
                }
            });
        } catch (\Throwable $e) {
            Log::error('Student deletion failed', [
                'student_id' => $id,
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unable to delete student. Please try again.'], 500);
            }

            return redirect()->route('students.index')
                ->with('error', 'Unable to delete student. Please try again.');
        }

        // Log the student deletion
        Log::warning('Student deleted', array_merge($deletedStudentData, [
            'timestamp' => now(),
        ]));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
        }

        return redirect()->route('students.index')
                        ->with('success', 'Student deleted successfully!');
    }
}