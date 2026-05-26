@extends('format.layout')

@section('title', 'Students')

@section('Content')
<style>
    .page-topbar {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.4rem;
        flex-wrap: wrap;
    }
    .page-topbar h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--primary-dark, #e65100);
        margin: 0;
        flex-shrink: 0;
    }
    .search-wrap {
        flex: 1;
        min-width: 220px;
        max-width: 420px;
        position: relative;
    }
    .search-wrap .search-icon {
        position: absolute;
        left: 13px; top: 50%; transform: translateY(-50%);
        color: #bbb; font-size: 0.85rem; pointer-events: none;
    }
    .search-wrap input {
        width: 100%;
        padding: 0.62rem 1rem 0.62rem 2.5rem;
        border: 1.5px solid #e8e0d8;
        border-radius: 10px;
        font-size: 0.88rem;
        outline: none;
        background: #fff;
        transition: border-color 0.18s, box-shadow 0.18s;
        font-family: 'DM Sans', sans-serif;
    }
    .search-wrap input:focus {
        border-color: var(--primary, #f57c00);
        box-shadow: 0 0 0 3px rgba(245,124,0,0.10);
    }
    .search-hint { font-size: 0.73rem; color: #bbb; margin-top: 0.25rem; }

    .btn-add-student {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.62rem 1.2rem;
        background: linear-gradient(135deg, var(--primary, #f57c00) 0%, var(--primary-dark, #e65100) 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(245,124,0,0.3);
        transition: opacity 0.15s, transform 0.15s;
        white-space: nowrap;
        margin-left: auto;
    }
    .btn-add-student:hover { opacity: 0.9; transform: translateY(-1px); color: #fff; }
</style>

<div class="page-topbar">
    <h1>Students</h1>

    <div class="search-wrap">
        <i class="fas fa-search search-icon"></i>
        <input
            type="text"
            id="studentSearch"
            data-ajax-search-for="studentTable"
            placeholder="Search by name, email or degree…"
            autocomplete="off">
        <div class="search-hint">Results update as you type</div>
    </div>

    <a href="{{ route('students.create') }}"
       data-ajax-link="true"
       data-target="#ajaxPageContent"
       class="btn-add-student">
        <i class="fas fa-user-plus"></i> Add New Student
    </a>
</div>

<div id="studentTable" data-ajax-list-url="/students">
    @include('students.partials.table')
</div>
@endsection
