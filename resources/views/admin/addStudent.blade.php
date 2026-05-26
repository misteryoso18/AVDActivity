@extends('format.layout')

@section('title', 'Add Student')

@section('Content')
    <div class="row mb-4">
        <div class="col-12">
            <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 2rem; border-radius: 8px;">
                <h1 style="color: white; margin: 0; font-size: 2rem;"><i class="fas fa-user-plus me-2"></i>Add New Student</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.store.student') }}" method="POST" novalidate data-ajax="true" data-loading="false" data-redirect="{{ route('students.index') }}" data-redirect-delay="0">
                        @csrf

                        <div class="mb-3">
                            <label for="fname" class="form-label">First Name *</label>
                            <input type="text" class="form-control @error('fname') is-invalid @enderror" id="fname" name="fname" required value="{{ old('fname') }}">
                            @error('fname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mname" class="form-label">Middle Name</label>
                            <input type="text" class="form-control @error('mname') is-invalid @enderror" id="mname" name="mname" value="{{ old('mname') }}">
                            @error('mname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control @error('lname') is-invalid @enderror" id="lname" name="lname" required value="{{ old('lname') }}">
                            @error('lname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_no" class="form-label">Contact Number *</label>
                            <input type="text" class="form-control @error('contact_no') is-invalid @enderror" id="contact_no" name="contact_no" required value="{{ old('contact_no') }}">
                            @error('contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="degree_id" class="form-label">Degree *</label>
                            <select class="form-control @error('degree_id') is-invalid @enderror" id="degree_id" name="degree_id" required>
                                <option value="">-- Select Degree --</option>
                                @forelse(\App\Models\Degree::all() as $degree)
                                    <option value="{{ $degree->id }}" {{ old('degree_id') == $degree->id ? 'selected' : '' }}>
                                        {{ $degree->title }}
                                    </option>
                                @empty
                                    <option disabled>No degrees available</option>
                                @endforelse
                            </select>
                            @error('degree_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn" style="background-color: var(--primary); color: white;">Add Student</button>
                            <a href="{{ route('admin.dashboard') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
