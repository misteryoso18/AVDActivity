@extends('format.layout')

@section('title', $student->fname . ' ' . $student->lname . ' - Student Details')

@section('Content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <div class="mb-4">
                <a href="{{ route('students.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Students
                </a>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>
                        {{ $student->lname }}, {{ $student->fname }} {{ $student->mname }}
                    </h3>
                </div>

                <div class="card-body">

                    @if($student->degree)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-muted text-uppercase small">Degree</h6>
                                <a href="{{ route('degrees.show', $student->degree->id) }}"
                                   class="badge bg-primary fs-6 text-decoration-none">
                                    {{ $student->degree->title }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small">Username</h6>
                            <p class="mb-0">
                                <i class="fas fa-user fa-sm me-1 text-muted"></i>
                                {{ $student->userAccount?->username ?? '—' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small">Email</h6>
                            <p class="mb-0">
                                <a href="mailto:{{ $student->email }}">
                                    <i class="fas fa-envelope fa-sm me-1"></i>{{ $student->email }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small">Contact Number</h6>
                            <p class="mb-0">
                                <a href="tel:{{ $student->contact_no }}">
                                    <i class="fas fa-phone fa-sm me-1"></i>{{ $student->contact_no }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small">Student ID</h6>
                            <p class="mb-0 text-muted">{{ $student->id }}</p>
                        </div>
                    </div>

                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <i class="fas fa-calendar-plus me-1"></i>
                            <strong>Created:</strong> {{ $student->created_at->format('M d, Y h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <i class="fas fa-calendar-check me-1"></i>
                            <strong>Last Updated:</strong> {{ $student->updated_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex gap-2 align-items-center">
                    <a href="{{ route('students.edit', $student->id) }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-warning">
                        <i class="fas fa-pen me-1"></i> Edit Student
                    </a>

                    {{--
                        AJAX Delete Button
                        ──────────────────
                        Replaces the old <form method="POST"> + @method('DELETE') approach.

                        jQuery (.js-delete-student click handler in app.js) intercepts
                        this button, shows the Bootstrap modal confirmation, then fires:

                            $.ajax({ url: '/students/' + id, type: 'DELETE' })

                        On success → toast notification → redirect to /students list.
                        No full page reload, no native browser confirm().

                        data-id   : passed to deleteStudent(id, name) in app.js
                        data-name : shown inside the modal body for clarity
                    --}}
                    <button
                        type="button"
                        class="btn btn-danger js-delete-student"
                        data-id="{{ $student->id }}"
                        data-name="{{ $student->lname }}, {{ $student->fname }}">
                        <i class="fas fa-trash me-1"></i> Delete Student
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection
