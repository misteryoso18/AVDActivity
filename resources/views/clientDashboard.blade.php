@extends('format.layout')

@section('title', 'Home - Student Management Dashboard')

@section('Content')
    <div class="row mb-5">
        <div class="col-12">
            <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 3rem 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <h1 style="color: white; margin: 0; font-size: 2.5rem; margin-bottom: 0.5rem;">Student Management Dashboard</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 1.1rem;">A comprehensive platform to manage and view student records easily and efficiently.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Students Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;">Student Records</h5>
                    <p class="card-text text-muted">Browse, manage, and view all student information in one place.</p>
                    <a href="{{ route('students.index') }}" class="btn btn-sm" style="background-color: var(--primary); color: white; font-weight: 600; transition: all 0.3s ease;">
                        View Students <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Degrees Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;">Degree Programs</h5>
                    <p class="card-text text-muted">Manage academic degree programs and qualifications.</p>
                    <a href="{{ route('degrees.index') }}" class="btn btn-sm" style="background-color: var(--primary); color: white; font-weight: 600; transition: all 0.3s ease;">
                        View Degrees <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Logs Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;">
                        <i class="fas fa-list"></i>
                    </div>
                    <h5 class="card-title" style="color: var(--primary); font-weight: 700;">System Logs</h5>
                    <p class="card-text text-muted">Monitor system activity and view detailed application logs.</p>
                    <a href="{{ route('logs') }}" class="btn btn-sm" style="background-color: var(--primary); color: white; font-weight: 600; transition: all 0.3s ease;">
                        View Logs <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-left: 5px solid var(--primary);">
                <div class="card-body">
                    <div style="display: flex; align-items: center; gap: 1.5rem;">
                        <div style="font-size: 3rem; color: var(--primary);">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h5 class="mb-2" style="color: var(--primary); font-weight: 700;"><i class="fas fa-question-circle me-2"></i>About This System</h5>
                            <p class="mb-0 text-muted">Learn more about the platform and its features. Get answers to frequently asked questions and explore the capabilities of our student management system.</p>
                            <a href="{{ route('about') }}" class="mt-3 d-inline-block btn btn-sm" style="background-color: var(--primary); color: white; font-weight: 600;">
                                Learn More <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection