@extends('format.layout')

@section('title', $degree->title . ' - Degree Details')

@section('Content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $degree->title }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('degrees.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Degree Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Degree Title:</strong> {{ $degree->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Students:</strong> <span class="badge bg-info">{{ $degree->students->count() }}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Created:</strong> {{ $degree->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Last Updated:</strong> {{ $degree->updated_at->format('M d, Y H:i A') }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="{{ route('degrees.edit', $degree->id) }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-warning">Edit Degree</a>
                            <form method="POST" action="{{ route('degrees.destroy', $degree->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Degree</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Students Enrolled in this Degree</h5>
        </div>
        <div class="card-body">
            @if($degree->students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Age</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($degree->students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $student->lname }}, {{ $student->fname }} {{ $student->mname }}</strong>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->contact_no }}</td>
                                    <td>{{ $student->age }}</td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle"></i> No students are currently enrolled in this degree.
                </div>
            @endif
        </div>
    </div>
@endsection
