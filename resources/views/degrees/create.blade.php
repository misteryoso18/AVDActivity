@extends('format.layout')

@section('title', 'Add Degree')

@section('Content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Add New Degree</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('degrees.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Error!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('degrees.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Degree Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" 
                           placeholder="e.g., Bachelor of Science in Computer Science" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary w-100">Save Degree</button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('degrees.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-secondary w-100">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
