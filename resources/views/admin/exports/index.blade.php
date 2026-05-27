@extends('format.layout')

@section('title', 'Report')

@section('Content')
<div class="row mb-4">
    <div class="col-12">
        <div class="report-hero">
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
        <div class="card shadow-sm h-100 report-stat-card">
            <div class="card-body">
                <div class="text-muted small mb-1">Students</div>
                <div class="fs-3 fw-bold">{{ $students->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card shadow-sm h-100 report-stat-card">
            <div class="card-body">
                <div class="text-muted small mb-1">Degrees</div>
                <div class="fs-3 fw-bold">{{ $degrees->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card shadow-sm h-100 report-stat-card">
            <div class="card-body">
                <div class="text-muted small mb-1">Courses</div>
                <div class="fs-3 fw-bold">{{ $courses->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card shadow-sm h-100 report-stat-card">
            <div class="card-body">
                <div class="text-muted small mb-1">Users</div>
                <div class="fs-3 fw-bold">{{ $users->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm report-panel">
            <div class="card-header report-panel-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Students Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View all student details before downloading the report.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.students.pdf') }}" class="btn report-btn report-btn-pdf" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.students.excel') }}" class="btn report-btn report-btn-excel" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm report-panel">
            <div class="card-header report-panel-header">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Degrees Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View degree details and student counts.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.degrees.pdf') }}" class="btn report-btn report-btn-pdf" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.degrees.excel') }}" class="btn report-btn report-btn-excel" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm report-panel">
            <div class="card-header report-panel-header">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Courses Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View course details and enrolled student counts.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.courses.pdf') }}" class="btn report-btn report-btn-pdf" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.courses.excel') }}" class="btn report-btn report-btn-excel" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm report-panel">
            <div class="card-header report-panel-header">
                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Users Report</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View user details and account roles.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('export.users.pdf') }}" class="btn report-btn report-btn-pdf" title="Download as PDF">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <a href="{{ route('export.users.excel') }}" class="btn report-btn report-btn-excel" title="Download as Excel">
                        <i class="fas fa-file-excel me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .report-hero {
        background: linear-gradient(135deg, #ff9800 0%, #e65100 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 10px 28px rgba(230, 81, 0, 0.16);
    }

    .report-stat-card,
    .report-panel {
        border: none;
        border-top: 4px solid #ff9800;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .report-stat-card:hover,
    .report-panel:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.10) !important;
    }

    .report-panel-header {
        background: linear-gradient(135deg, #ff9800 0%, #e65100 100%);
        color: white;
        border-bottom: none;
    }

    .report-btn {
        border-width: 2px;
        font-weight: 700;
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }

    .report-btn-pdf {
        border-color: #ff9800;
        color: #e65100;
    }

    .report-btn-pdf:hover {
        background: #ff9800;
        color: white;
    }

    .report-btn-excel {
        border-color: #e65100;
        color: #e65100;
    }

    .report-btn-excel:hover {
        background: #e65100;
        color: white;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.95rem;
    }
</style>
@endsection
