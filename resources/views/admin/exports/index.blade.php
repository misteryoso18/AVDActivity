@extends('format.layout')

@section('title', 'Export Reports')

@section('Content')
<div class="row mb-4">
    <div class="col-12">
        <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 2rem; border-radius: 8px;">
            <h1 style="color: white; margin: 0; font-size: 2rem;"><i class="fas fa-download me-2"></i>Export Reports</h1>
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

<div class="row">
    <!-- Students Export -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Students Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Export all students data to PDF or Excel format.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.students.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Export to PDF
                    </a>
                    <a href="{{ route('export.students.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Degrees Export -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Degrees Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Export all degrees data to PDF or Excel format.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.degrees.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Export to PDF
                    </a>
                    <a href="{{ route('export.degrees.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Export -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Courses Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Export all courses data to PDF or Excel format.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.courses.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Export to PDF
                    </a>
                    <a href="{{ route('export.courses.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Export -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--primary); color: white;">
                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Users Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Export all system users data to PDF or Excel format.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.users.pdf') }}" class="btn btn-outline-primary" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Export to PDF
                    </a>
                    <a href="{{ route('export.users.excel') }}" class="btn btn-outline-success" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
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
</style>
@endsection
