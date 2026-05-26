@extends('format.layout')

@section('title', 'User Profile')

@section('Content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">User Profile</h1>

            @if (!$user)
                <div class="alert alert-warning mb-0">
                    No user record found yet.
                </div>
            @else
                <div class="mb-3">
                    <strong>Name:</strong> {{ $user->name }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $user->email }}
                </div>
                <div class="mb-3">
                    <strong>Bio:</strong>
                    {{ $user->profile?->bio ?? 'No bio yet.' }}
                </div>
                <div class="mb-0">
                    <strong>Avatar:</strong>
                    {{ $user->profile?->avatar ?? 'No avatar yet.' }}
                </div>
            @endif
        </div>
    </div>
@endsection
