@extends('format.layout')

@section('title', 'Student Dashboard')

@section('Content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="dash-hero">
                <h1 style="color: white; margin: 0; font-size: 2rem;">Welcome, {{ $student->fname }} {{ $student->lname }}</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 0.5rem 0; font-size: 1rem;">
                    {{ $student->degree?->title ?? 'No degree assigned' }}
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Student Information Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;"><i class="fas fa-user me-2"></i>Personal Information</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-2"><strong>Name:</strong> {{ $student->fname }} {{ $student->mname }} {{ $student->lname }}</li>
                        <li class="mb-2"><strong>Email:</strong> {{ $student->email }}</li>
                        <li class="mb-2"><strong>Contact:</strong> {{ $student->contact_no }}</li>
                        <li><strong>Degree:</strong> {{ $student->degree?->title ?? 'Not assigned' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Courses Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;"><i class="fas fa-book me-2"></i>Enrolled Courses</h5>
                    @if($student->courses->count() > 0)
                        <ul style="list-style: none; padding: 0;">
                            @foreach($student->courses as $course)
                                <li class="mb-2">{{ $course->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No courses enrolled yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;"><i class="fas fa-cog me-2"></i>Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.dashboard') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm" style="background-color: var(--primary); color: white;">
                            Refresh Dashboard
                        </a>
                        <a href="{{ route('home') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm btn-secondary">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
