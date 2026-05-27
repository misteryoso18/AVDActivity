@extends('format.layout')

@section('title', 'Report')

@section('Content')
<div class="row mb-4">
    <div class="col-12">
        <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 2rem; border-radius: 8px;">
            <h1 style="color: white; margin: 0; font-size: 2rem;"><i class="fas fa-chart-column me-2"></i>Report</h1>
            <p class="mb-0 mt-2" style="color: rgba(255,255,255,0.85);">View the latest records first, then download PDF or Excel if needed.</p>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row mb-3">
    <div class="col-md-3 col-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Students</div>
                <div class="fs-3 fw-bold">{{ $students->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Degrees</div>
                <div class="fs-3 fw-bold">{{ $degrees->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Courses</div>
                <div class="fs-3 fw-bold">{{ $courses->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Users</div>
                <div class="fs-3 fw-bold">{{ $users->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Students Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View all student details before downloading the report.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.students.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.students.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Degrees Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View degree details and student counts.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.degrees.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.degrees.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Courses Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View course details and enrolled student counts.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.courses.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.courses.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Users Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View user details and account roles.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.users.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.users.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #4A90E2;
        --primary-dark: #2E5C8A;
        --secondary: #50C878;
        --danger: #E74C3C;
        --warning: #F39C12;
    }

    .btn-outline-primary {
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .btn-outline-success {
        border-color: var(--secondary);
        color: var(--secondary);
    }

    .btn-outline-success:hover {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: white;
    }

    .card {
        border: none;
        border-top: 4px solid var(--primary);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        border-radius: 0;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .table thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom-color: #e9ecef;
    }
</style>
@endsection
