@extends('format.layout')

@section('title', 'Change Password - Student Management Dashboard')

@section('Content')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-key"></i>
                    <span>Change Password</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Update your password to keep your account secure.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                                <div class="mb-2">{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input 
                                type="password" 
                                class="form-control @error('current_password') is-invalid @enderror" 
                                id="current_password" 
                                name="current_password" 
                                placeholder="Enter your current password"
                                autocomplete="current-password"
                                required>
                            @error('current_password')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input 
                                type="password" 
                                class="form-control @error('new_password') is-invalid @enderror" 
                                id="new_password" 
                                name="new_password" 
                                placeholder="Enter your new password"
                                autocomplete="new-password"
                                required>
                            @error('new_password')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1">Password must be at least 8 characters long</small>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirm Password *</label>
                            <input 
                                type="password" 
                                class="form-control @error('confirm_password') is-invalid @enderror" 
                                id="confirm_password" 
                                name="confirm_password" 
                                placeholder="Confirm your new password"
                                autocomplete="new-password"
                                required>
                            @error('confirm_password')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary" style="padding: 10px;">
                                Update Password
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="font-weight: 600;">
                                Back to Dashboard
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
