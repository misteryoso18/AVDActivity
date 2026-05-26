@extends('format.layout')

@section('title', 'Edit Student - Student Management')

@section('Content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('students.show', $student->id) }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="mb-0">Edit Student</h1>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">

                    {{--
                        data-ajax="true"
                            jQuery intercepts submit → $.ajax PUT
                            (FormData carries the hidden @method('PUT')).

                        data-redirect="/students"
                            After a successful response app.js navigates
                            back to the students list without a full page reload
                            during the AJAX call itself.

                        Note: Laravel redirect() inside a controller
                        is ignored by AJAX — we return JSON instead
                        and handle navigation client-side.
                    --}}
                    <form
                        method="POST"
                        action="{{ route('students.update', $student->id) }}"
                        novalidate
                        data-ajax="true"
                        data-loading="false"
                        data-redirect="{{ route('students.index') }}"
                        data-redirect-delay="1000">
                        @csrf
                        @method('PUT')

                        {{-- Non-AJAX server-side error fallback --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fname" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control @error('fname') is-invalid @enderror"
                                        id="fname"
                                        name="fname"
                                        placeholder="Enter first name"
                                        minlength="2"
                                        value="{{ old('fname', $student->fname) }}"
                                        required>
                                    @error('fname')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mname" class="form-label fw-semibold">Middle Initial <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control @error('mname') is-invalid @enderror"
                                        id="mname"
                                        name="mname"
                                        placeholder="e.g. D"
                                        maxlength="1"
                                        value="{{ old('mname', $student->mname) }}"
                                        required>
                                    @error('mname')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="lname" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('lname') is-invalid @enderror"
                                id="lname"
                                name="lname"
                                placeholder="Enter last name"
                                minlength="2"
                                value="{{ old('lname', $student->lname) }}"
                                required>
                            @error('lname')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                placeholder="Enter email address"
                                value="{{ old('email', $student->email) }}"
                                required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_no" class="form-label fw-semibold">Contact Number <span class="text-danger">*</span> <small class="text-muted">(11 digits)</small></label>
                            <input
                                type="text"
                                class="form-control @error('contact_no') is-invalid @enderror"
                                id="contact_no"
                                name="contact_no"
                                placeholder="09212345678"
                                pattern="[0-9]{11}"
                                maxlength="11"
                                inputmode="numeric"
                                value="{{ old('contact_no', $student->contact_no) }}"
                                required>
                            @error('contact_no')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="degree_id" class="form-label fw-semibold">Degree <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('degree_id') is-invalid @enderror"
                                id="degree_id"
                                name="degree_id"
                                required>
                                <option value="">-- Select Degree --</option>
                                @foreach($degrees as $degree)
                                    <option value="{{ $degree->id }}" @if(old('degree_id', $student->degree_id) == $degree->id) selected @endif>
                                        {{ $degree->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('degree_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i> Update Student
                            </button>
                            <a href="{{ route('students.show', $student->id) }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
