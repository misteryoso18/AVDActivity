@extends('format.layout')

@section('title', 'Admin Dashboard')

@section('Content')
<style>
    /* ── STAT CARDS ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.4rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        border-top: 3px solid var(--primary);
        transition: transform 0.18s, box-shadow 0.18s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.10); }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        background: var(--primary-pale, #fff3e0);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        color: var(--primary);
        flex-shrink: 0;
    }
    .stat-info .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.9rem;
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1;
        margin-bottom: 0.2rem;
    }
    .stat-info .stat-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #888;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    /* ── SECTION GRID ── */
    .section-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    .section-grid.full { grid-template-columns: 1fr; }

    /* ── QUICK ACTION CARD ── */
    .qaction-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    .qaction-card .section-label {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #bbb;
        margin-bottom: 1rem;
    }
    .qaction-list { display: flex; flex-direction: column; gap: 0.55rem; }
    .qaction-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.7rem 0.85rem;
        border-radius: 10px;
        background: #fdf8f3;
        text-decoration: none;
        color: #1a1a1a;
        font-size: 0.88rem;
        font-weight: 600;
        transition: background 0.15s, transform 0.15s;
        border: 1px solid transparent;
    }
    .qaction-item:hover {
        background: #fff3e0;
        border-color: #f57c00;
        color: var(--primary-dark, #e65100);
        transform: translateX(3px);
    }
    .qaction-item .qicon {
        width: 34px; height: 34px;
        border-radius: 8px;
        background: var(--primary-pale, #fff3e0);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        color: var(--primary);
        flex-shrink: 0;
    }
    .qaction-item .qarrow {
        margin-left: auto;
        color: #ddd;
        font-size: 0.75rem;
    }
    .qaction-item:hover .qarrow { color: var(--primary); }

    /* ── RECENT STUDENTS TABLE ── */
    .recent-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .recent-card .rc-header {
        padding: 1.1rem 1.4rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f5ece3;
    }
    .recent-card .rc-header h6 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }
    .recent-card .rc-header a {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
    }
    .recent-card .rc-header a:hover { text-decoration: underline; }

    .recent-table { width: 100%; border-collapse: collapse; }
    .recent-table thead tr th {
        padding: 0.55rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #aaa;
        text-align: left;
        background: #fdf9f6;
        border-bottom: 1px solid #f5ece3;
    }
    .recent-table tbody tr td {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        color: #333;
        border-bottom: 1px solid #faf5f0;
        vertical-align: middle;
    }
    .recent-table tbody tr:last-child td { border-bottom: none; }
    .recent-table tbody tr:hover td { background: #fdf9f6; }

    .student-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f57c00, #e65100);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .degree-pill {
        display: inline-block;
        padding: 0.18rem 0.6rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        background: #fff3e0;
        color: var(--primary-dark, #e65100);
    }
    .empty-row td {
        text-align: center;
        padding: 2rem !important;
        color: #bbb;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .stat-grid { grid-template-columns: 1fr 1fr; }
        .section-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- ── HERO ── --}}
<div class="dash-hero" style="margin-bottom:1.5rem;">
    <div style="position:relative;z-index:2;">
        <h1 style="color:white;margin:0;font-size:1.75rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;">
            <i class="fas fa-shield-alt me-2" style="opacity:.85;"></i>Admin Dashboard
        </h1>
        <p style="color:rgba(255,255,255,0.82);margin:0.35rem 0 0;font-size:0.92rem;">
            Welcome back, <strong>{{ session('username') }}</strong> — here's your system overview.
        </p>
    </div>
</div>

{{-- ── STAT CARDS ── --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
            <div class="stat-label">Total Students</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalDegrees ?? 0 }}</div>
            <div class="stat-label">Degree Programs</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalTeachers ?? 0 }}</div>
            <div class="stat-label">Teachers</div>
        </div>
    </div>
</div>

{{-- ── LOWER GRID ── --}}
<div class="section-grid">

    {{-- Quick Actions --}}
    <div class="qaction-card">
        <div class="section-label">Quick Actions</div>
        <div class="qaction-list">
            <a href="{{ route('students.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="qaction-item">
                <span class="qicon"><i class="fas fa-users"></i></span>
                <span>Manage Students</span>
                <i class="fas fa-chevron-right qarrow"></i>
            </a>
            <a href="{{ route('admin.add.teacher') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="qaction-item">
                <span class="qicon"><i class="fas fa-chalkboard-user"></i></span>
                <span>Add Teacher</span>
                <i class="fas fa-chevron-right qarrow"></i>
            </a>
            <a href="{{ route('degrees.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="qaction-item">
                <span class="qicon"><i class="fas fa-graduation-cap"></i></span>
                <span>Manage Degrees</span>
                <i class="fas fa-chevron-right qarrow"></i>
            </a>
            <a href="{{ route('logs') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="qaction-item">
                <span class="qicon"><i class="fas fa-file-alt"></i></span>
                <span>System Logs</span>
                <i class="fas fa-chevron-right qarrow"></i>
            </a>
        </div>
    </div>

    {{-- Recent Students --}}
    <div class="recent-card">
        <div class="rc-header">
            <h6><i class="fas fa-clock me-1" style="color:var(--primary);"></i> Recent Students</h6>
            <a href="{{ route('students.index') }}" data-ajax-link="true" data-target="#ajaxPageContent">View all →</a>
        </div>
        <table class="recent-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Degree</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentStudents ?? [] as $s)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.65rem;">
                                <div class="student-avatar">{{ strtoupper(substr($s->fname, 0, 1)) }}{{ strtoupper(substr($s->lname, 0, 1)) }}</div>
                                <div>
                                    <div style="font-weight:700;font-size:0.85rem;">{{ $s->lname }}, {{ $s->fname }}</div>
                                    <div style="font-size:0.72rem;color:#aaa;">{{ $s->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($s->degree)
                                <span class="degree-pill">{{ $s->degree->title }}</span>
                            @else
                                <span style="color:#ccc;font-size:0.78rem;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="2"><i class="fas fa-user-slash me-1"></i> No students yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
