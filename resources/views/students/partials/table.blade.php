{{--
    students/partials/table.blade.php
    Returned by StudentController::index() for AJAX requests.
    jQuery replaces #studentTable with this HTML.
--}}
<style>
    .stu-table-wrap {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .stu-table { width: 100%; border-collapse: collapse; }
    .stu-table thead tr th {
        padding: 0.7rem 1rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #fff;
        background: linear-gradient(90deg, var(--primary-dark, #e65100), var(--primary, #f57c00));
        border: none;
    }
    .stu-table thead tr th:first-child { border-radius: 0; }
    .stu-table tbody tr td {
        padding: 0.85rem 1rem;
        font-size: 0.87rem;
        color: #333;
        border-bottom: 1px solid #faf5f0;
        vertical-align: middle;
    }
    .stu-table tbody tr:last-child td { border-bottom: none; }
    .stu-table tbody tr:hover td { background: #fdf9f6; }

    .stu-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f57c00, #e65100);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 700; color: #fff;
        flex-shrink: 0;
    }
    .stu-name { font-weight: 700; font-size: 0.88rem; color: #1a1a1a; }
    .stu-username { font-size: 0.72rem; color: #aaa; margin-top: 1px; }

    .degree-badge {
        display: inline-block;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        background: #fff3e0;
        color: #e65100;
    }
    .degree-badge.none { background: #f5f5f5; color: #bbb; }

    .action-btn {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.78rem;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.15s, transform 0.12s;
    }
    .action-btn:hover { opacity: 0.85; transform: translateY(-1px); }
    .action-btn.view   { background: #e3f2fd; color: #1565c0; }
    .action-btn.edit   { background: #fff8e1; color: #f57f17; }
    .action-btn.delete { background: #ffebee; color: #c62828; }

    .stu-empty {
        padding: 3rem 1rem;
        text-align: center;
        color: #bbb;
    }
    .stu-empty i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
    .stu-empty a { color: var(--primary); font-weight: 600; text-decoration: none; }

    .stu-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 1rem;
        border-top: 1px solid #faf5f0;
        font-size: 0.78rem;
        color: #aaa;
        background: #fdf9f6;
    }
    /* override Bootstrap pagination to match theme */
    .ajax-pagination .pagination .page-link {
        border-radius: 7px !important;
        margin: 0 2px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary-dark, #e65100);
        border-color: #f5ece3;
    }
    .ajax-pagination .pagination .page-item.active .page-link {
        background: var(--primary, #f57c00);
        border-color: var(--primary, #f57c00);
        color: #fff;
    }
</style>

@if ($message = Session::get('success'))
    <div style="background:#f0fdf4;border:1px solid #86efac;color:#166534;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;font-weight:500;">
        <i class="fas fa-check-circle"></i> {{ $message }}
    </div>
@endif

@if ($message = Session::get('error'))
    <div style="background:#fff5f5;border:1px solid #fca5a5;color:#b91c1c;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;font-weight:500;">
        <i class="fas fa-times-circle"></i> {{ $message }}
    </div>
@endif

<div class="stu-table-wrap">
    @forelse($students as $student)
        @if ($loop->first)
            <table class="stu-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Degree</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        @endif

        <tr>
            <td style="color:#ccc;font-weight:600;">{{ $loop->iteration + (($students->currentPage() - 1) * $students->perPage()) }}</td>
            <td>
                <div style="display:flex;align-items:center;gap:0.65rem;">
                    <div class="stu-avatar">{{ strtoupper(substr($student->fname,0,1)) }}{{ strtoupper(substr($student->lname,0,1)) }}</div>
                    <div>
                        <div class="stu-name">{{ $student->lname }}, {{ $student->fname }} {{ $student->mname }}</div>
                        <div class="stu-username"><i class="fas fa-user fa-xs me-1"></i>{{ $student->userAccount?->username ?? '—' }}</div>
                    </div>
                </div>
            </td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->contact_no }}</td>
            <td>
                @if($student->degree)
                    <span class="degree-badge">{{ $student->degree->title }}</span>
                @else
                    <span class="degree-badge none">No Degree</span>
                @endif
            </td>
            <td style="text-align:center;">
                <div style="display:flex;gap:0.35rem;justify-content:center;">
                    <a href="{{ route('students.show', $student->id) }}"
                       data-ajax-link="true" data-target="#ajaxPageContent"
                       class="action-btn view" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('students.edit', $student->id) }}"
                       data-ajax-link="true" data-target="#ajaxPageContent"
                       class="action-btn edit" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button type="button"
                        class="action-btn delete js-delete-student"
                        data-id="{{ $student->id }}"
                        data-name="{{ $student->lname }}, {{ $student->fname }}"
                        title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>

        @if ($loop->last)
                </tbody>
            </table>
        @endif
    @empty
        <div class="stu-empty">
            <i class="fas fa-user-slash"></i>
            No students found. <a href="{{ route('students.create') }}" data-ajax-link="true" data-target="#ajaxPageContent">Add one now</a>
        </div>
    @endforelse

    @if($students->count() > 0)
        <div class="stu-footer">
            <span>Showing {{ $students->firstItem() }}–{{ $students->lastItem() }} of {{ $students->total() }} student(s)</span>
            <div class="ajax-pagination">{{ $students->links() }}</div>
        </div>
    @endif
</div>
