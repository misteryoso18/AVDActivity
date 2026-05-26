# Code Reference - Laravel Blade Student Management Dashboard

## Complete Code Snippets for All Components

---

## 1. Master Layout Template

**File**: `resources/views/format/layout.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Management Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        .container.mt-4 {
            flex: 1;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        footer {
            margin-top: auto;
            box-shadow: 0 -1px 4px rgba(0,0,0,.1);
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,.15) !important;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap me-2"></i>MRJR PROJECT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Home Page
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('students.index') }}">
                            <i class="fas fa-list me-1"></i>Students Page
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-1"></i>About Page
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4 mb-4">
        @yield('Content')
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        @section('Footer')
            <div class="row">
                <div class="col-12">
                    <p class="mb-2">&copy; 2026 Student Management Dashboard</p>
                    <small class="text-muted">
                        Built with <i class="fas fa-heart text-danger"></i> using Laravel Blade & Bootstrap 5
                    </small>
                </div>
            </div>
        @show
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDzlPLNUeeqnsIY40JmqrP4ukNHlF7p3c/f7jmrxeXsRxRBtzPbkm5Z5Z0K" crossorigin="anonymous"></script>
</body>
</html>
```

**Key Blade Concepts**:

- `@yield('title', 'default')` - Placeholder for page title
- `@yield('Content')` - Placeholder for page content
- `@section()` and `@show` - Define section with output
- `{{ route() }}` - Generate URLs using named routes

---

## 2. Home/Dashboard Page

**File**: `resources/views/clientDashboard.blade.php`

```blade
@extends('format.layout')

@section('title', 'Home - Student Management Dashboard')

@section('Content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-4 text-primary">
                        <i class="fas fa-graduation-cap"></i> Welcome to Student Management Dashboard
                    </h1>
                    <p class="lead text-secondary mb-4">
                        This is a comprehensive platform to manage and view student records easily and efficiently.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-users"></i> View Students
                            </h5>
                            <p class="card-text text-muted mb-3">
                                Browse through all registered students, view their details, and manage records.
                            </p>
                            <a href="{{ route('students.index') }}" class="btn btn-primary">
                                Go to Students <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info mb-3">
                                <i class="fas fa-info-circle"></i> Learn More
                            </h5>
                            <p class="card-text text-muted mb-3">
                                Learn more about our organization and the purpose of this platform.
                            </p>
                            <a href="{{ route('about') }}" class="btn btn-info">
                                About Us <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-5">
                <div class="card-body">
                    <h5 class="card-title mb-3">Features</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-check text-success"></i> View complete student information</li>
                        <li class="list-group-item"><i class="fas fa-check text-success"></i> Filter students by status and year level</li>
                        <li class="list-group-item"><i class="fas fa-check text-success"></i> Responsive and user-friendly interface</li>
                        <li class="list-group-item"><i class="fas fa-check text-success"></i> Built with Laravel Blade templating</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
```

**Key Blade Concepts**:

- `@extends()` - Inherit from master layout
- `@section()` and `@endsection` - Define section content
- `{{ route() }}` - Generate named route URLs

---

## 3. Students List Page (Main Component)

**File**: `resources/views/students.blade.php`

```blade
@extends('format.layout')

@section('title', 'Students - Student Management Dashboard')

@section('Content')
    <div class="row mb-4">
        <div class="col-lg-10">
            <h1 class="display-5 mb-4">
                <i class="fas fa-list"></i> Student List
            </h1>
        </div>
    </div>

    @forelse($students as $student)
        @if($loop->first)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Contact No.</th>
                        <th scope="col">Age</th>
                        <th scope="col">Course/Program</th>
                        <th scope="col" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
        @endif

                    <tr>
                        <td scope="row" class="text-center fw-bold text-primary">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $student->lname }}, {{ $student->fname }}</strong>
                            @if($student->mname)
                                <br><small class="text-muted">{{ $student->mname }}</small>
                            @endif
                        </td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->contact_no }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $student->age }} yrs</span>
                        </td>
                        <td>
                            <small>{{ $student->course }}</small>
                        </td>
                        <td class="text-center">
                            @if($student->age == 19)
                                <span class="badge bg-success">Freshman Student</span>
                            @elseif($student->age == 20)
                                <span class="badge bg-info">Sophomore Student</span>
                            @elseif($student->age == 21)
                                <span class="badge bg-warning text-dark">Junior Student</span>
                            @elseif($student->age == 22)
                                <span class="badge bg-danger">Senior Student</span>
                            @else
                                <span class="badge bg-secondary">Other Status</span>
                            @endif
                        </td>
                    </tr>

        @if($loop->last)
                </tbody>
            </table>
        </div>
        @endif
    @empty
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>No Data Available</strong>
            <p class="mb-0">No students available at the moment. Please check back later or contact an administrator.</p>
        </div>
    @endforelse

    <!-- Summary Section -->
    @if(count($students) > 0)
    <div class="row mt-5 pt-4 border-top">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Students</h6>
                    <h4 class="text-primary">{{ count($students) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted">Freshman</h6>
                    <h4 class="text-success">{{ count(array_filter($students, fn($s) => $s->age == 19)) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted">Sophomore</h6>
                    <h4 class="text-info">{{ count(array_filter($students, fn($s) => $s->age == 20)) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted">Junior</h6>
                    <h4 class="text-warning">{{ count(array_filter($students, fn($s) => $s->age == 21)) }}</h4>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
```

**Key Blade Concepts**:

- `@forelse()` - Loop with empty state handling
- `@if()` - Conditional rendering
- `$loop->iteration` - Auto-numbering
- `$loop->first` and `$loop->last` - Loop position
- `@empty` - Content when collection is empty
- `{{ count() }}` and `array_filter()` - PHP in templates

---

## 4. About Page

**File**: `resources/views/clientAboutUs.blade.php`

```blade
@extends('format.layout')

@section('title', 'About Us - Student Management Dashboard')

@section('Content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-5 mb-4">
                <i class="fas fa-info-circle"></i> About Us
            </h1>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Student Management Dashboard</h5>
                    <p class="card-text text-muted">
                        This is a comprehensive Student Management Dashboard built with Laravel and Blade templating engine.
                        Our platform provides an easy-to-use interface for managing and viewing student records efficiently.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">
                                <i class="fas fa-bullseye"></i> Our Mission
                            </h6>
                            <p class="card-text text-muted">
                                To provide educational institutions with a reliable and user-friendly platform
                                for managing student information and academic records with ease.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-title text-success mb-3">
                                <i class="fas fa-eye"></i> Our Vision
                            </h6>
                            <p class="card-text text-muted">
                                To be the leading solution in educational management systems, empowering institutions
                                worldwide to streamline their operations and improve student outcomes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-tools"></i> Technologies Used
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-0">
                                    <strong>Laravel Framework</strong> - Robust PHP web framework
                                </li>
                                <li class="list-group-item border-0">
                                    <strong>Blade Templating</strong> - Elegant template engine
                                </li>
                                <li class="list-group-item border-0">
                                    <strong>Bootstrap 5</strong> - Responsive CSS framework
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-0">
                                    <strong>MySQL Database</strong> - Reliable data storage
                                </li>
                                <li class="list-group-item border-0">
                                    <strong>Font Awesome Icons</strong> - Beautiful UI elements
                                </li>
                                <li class="list-group-item border-0">
                                    <strong>MVC Architecture</strong> - Clean code structure
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 bg-light">
                <div class="card-body text-center">
                    <h6 class="card-title mb-2">Need Help?</h6>
                    <p class="card-text text-muted mb-3">
                        Have questions about the Student Management Dashboard?
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-home me-2"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
```

---

## 5. Student Model

**File**: `app/Models/Student.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['fname', 'mname', 'lname', 'email', 'contact_no', 'age', 'course'];
}
```

---

## 6. Student Controller

**File**: `app/Http/Controllers/StudentController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        // Create sample student data for demonstration
        $students = [
            (object)[
                'id' => 1,
                'fname' => 'John',
                'mname' => 'Michael',
                'lname' => 'Smith',
                'email' => 'john.smith@example.com',
                'contact_no' => '09123456789',
                'age' => 19,
                'course' => 'Bachelor of Science in Computer Science'
            ],
            (object)[
                'id' => 2,
                'fname' => 'Maria',
                'mname' => 'Grace',
                'lname' => 'Johnson',
                'email' => 'maria.johnson@example.com',
                'contact_no' => '09234567890',
                'age' => 20,
                'course' => 'Bachelor of Science in Information Technology'
            ],
            (object)[
                'id' => 3,
                'fname' => 'Robert',
                'mname' => 'James',
                'lname' => 'Williams',
                'email' => 'robert.williams@example.com',
                'contact_no' => '09345678901',
                'age' => 21,
                'course' => 'Bachelor of Business Administration'
            ],
            (object)[
                'id' => 4,
                'fname' => 'Anna',
                'mname' => 'Elizabeth',
                'lname' => 'Brown',
                'email' => 'anna.brown@example.com',
                'contact_no' => '09456789012',
                'age' => 22,
                'course' => 'Bachelor of Science in Nursing'
            ],
            (object)[
                'id' => 5,
                'fname' => 'David',
                'mname' => 'Peter',
                'lname' => 'Davis',
                'email' => 'david.davis@example.com',
                'contact_no' => '09567890123',
                'age' => 19,
                'course' => 'Bachelor of Science in Engineering'
            ],
        ];

        return view('students', ['students' => $students]);
    }

    public function home()
    {
        return view('clientDashboard');
    }

    public function about()
    {
        return view('clientAboutUs');
    }

    // Other CRUD methods (create, store, show, edit, update, destroy)...
}
```

---

## 7. Routes Configuration

**File**: `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

// These routes are already configured in your project:

Route::get('/', [StudentController::class, 'home'])->name('home');
Route::get('/about', [StudentController::class, 'about'])->name('about');
Route::resource('students', StudentController::class);
```

---

## 8. Database Migration

**File**: `database/migrations/2026_03_12_071134_create_students_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('email');
            $table->string('contact_no');
            $table->integer('age')->nullable();
            $table->string('course')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

---

## Blade Directives Reference

| Directive           | Purpose             | Example                                   |
| ------------------- | ------------------- | ----------------------------------------- |
| `@extends()`        | Inherit from layout | `@extends('format.layout')`               |
| `@section()`        | Define section      | `@section('Content')...@endsection`       |
| `@yield()`          | Output section      | `@yield('title', 'Default')`              |
| `@forelse()`        | Loop with empty     | `@forelse($items as $item)...@empty`      |
| `@if/@elseif/@else` | Conditionals        | `@if($age == 19)...@endif`                |
| `@foreach`          | Loop                | `@foreach($items as $item)...@endforeach` |
| `@endforelse`       | End forelse         |                                           |
| `@empty`            | Empty collection    | `@empty...@endempty`                      |
| `{{ }}`             | Echo/output         | `{{ $variable }}`                         |
| `{{ route() }}`     | Generate route URL  | `{{ route('home') }}`                     |
| `{{ count() }}`     | Count items         | `{{ count($students) }}`                  |
| `@show`             | Output section      | `@section()...@show`                      |

---

## Column Names in Student Table

| Column     | Type      | Nullable | Purpose        |
| ---------- | --------- | -------- | -------------- |
| id         | BigInt    | No       | Primary Key    |
| fname      | String    | No       | First Name     |
| mname      | String    | No       | Middle Name    |
| lname      | String    | No       | Last Name      |
| email      | String    | No       | Email Address  |
| contact_no | String    | No       | Contact Number |
| age        | Integer   | Yes      | Student Age    |
| course     | String    | Yes      | Course/Program |
| created_at | Timestamp | No       | Created Date   |
| updated_at | Timestamp | No       | Updated Date   |
