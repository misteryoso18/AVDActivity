@extends('format.layout')

@section('title', 'Teacher Dashboard')

@section('Content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="dash-hero">
                <h1 style="color: white; margin: 0; font-size: 2rem;"><i class="fas fa-chalkboard-user me-2"></i>Teacher Dashboard</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 0.5rem 0; font-size: 1rem;">Welcome, {{ session('username') }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- My Courses Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;"><i class="fas fa-book me-2"></i>My Courses</h5>
                    <p class="card-text text-muted">View and manage your assigned courses.</p>
                    <a href="{{ route('teacher.courses') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm" style="background-color: var(--primary); color: white;">
                        View Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Students Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;"><i class="fas fa-users me-2"></i>Students</h5>
                    <p class="card-text text-muted">View students enrolled in your courses.</p>
                    <a href="{{ route('teacher.students') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm" style="background-color: var(--primary); color: white;">
                        View Students
                    </a>
                </div>
            </div>
        </div>

        <!-- Grades Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;"><i class="fas fa-chart-bar me-2"></i>Grades</h5>
                    <p class="card-text text-muted">Manage student grades and evaluations.</p>
                    <a href="{{ route('teacher.grades') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm" style="background-color: var(--primary); color: white;">
                        Manage Grades
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
