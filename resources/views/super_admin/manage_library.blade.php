@extends('layout.app')

@section('title','Manage Library')

@push('styles')
<link rel="stylesheet" href="{{ url('css/manage_library.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@endpush

@section('content')
<div class="container my-2">
    <h2 class="text-center mb-4 text-white">Library Overview</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($libraries as $library)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center">
                        {{ $library->name }}
                        <span class="badge {{ $library->status == 'open' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($library->status == 'open' ? 'active' : 'inactive') }}
                        </span>
                    </h5>
                    <p class="card-text mb-2">
                        <i class="bi bi-geo-alt text-primary me-1"></i>
                        <strong>Location:</strong> {{ $library->location }}
                    </p>
                    <p class="card-text mb-3">
                        <i class="bi bi-person-check text-secondary me-1"></i>
                        <strong>Admin:</strong>
                        @if ($library->admin)
                        {{ $library->admin->name }}
                        <small class="text-muted">({{ $library->admin->email }})</small>
                        @else
                            <a class="btn btn-sm btn-outline-primary mt-1 mt-md-0" href="{{ route('assign.admin', $library->id) }}">Assign Admin</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


@endsection

@push('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
@endpush