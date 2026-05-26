@extends('format.layout')

@section('title', $title)

@section('Content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-3" style="font-size: 3rem; color: var(--primary);">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <h2 class="mb-3">{{ $heading }}</h2>
                    <p class="text-muted mb-0">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection