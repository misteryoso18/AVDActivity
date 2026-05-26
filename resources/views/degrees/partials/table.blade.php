{{--
    degrees/partials/table.blade.php
    Returned by DegreeController::index() for AJAX requests.
    jQuery replaces #degreeTable with this HTML.
--}}

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        @forelse($degrees as $degree)
            @if ($loop->first)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Degree Title</th>
                                <th>Total Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            @endif

                <tr>
                    <td>{{ ($degrees->currentPage() - 1) * $degrees->perPage() + $loop->iteration }}</td>
                    <td><strong>{{ $degree->title }}</strong></td>
                    <td><span class="badge bg-info">{{ $degree->students_count }}</span></td>
                    <td>
                        <a href="{{ route('degrees.show', $degree->id) }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm btn-primary">View</a>
                        <a href="{{ route('degrees.edit', $degree->id) }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('degrees.destroy', $degree->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>

            @if ($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif
        @empty
            <div class="alert alert-warning text-center" role="alert">
                No degrees found. <a href="{{ route('degrees.create') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="alert-link">Add one now</a>
            </div>
        @endforelse
    </div>
</div>

@if($degrees->count() > 0)
    <div class="d-flex justify-content-center mt-4">
        {{ $degrees->links() }}
    </div>
@endif
