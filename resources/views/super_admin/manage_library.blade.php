@extends('layout.app')

@section('title','Manage Library')

@push('styles')
<link rel="stylesheet" href="{{ url('css/manage_library.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@endpush

@section('content')
<div class="container my-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center mb-4 text-white">Library Overview</h2>
        <a href="{{ route('add.library') }}" class="btn btn-primary">Add New Library</a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($libraries as $library)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center">
                        <span class="text-primary fw-semibold">{{ $library->name }} </span>
                        <span class="badge {{ $library->status == 'open' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($library->status == 'open' ? 'active' : 'inactive') }}
                        </span>
                    </h5>
                    <p class="card-text mb-2">
                        <i class="bi bi-geo-alt text-primary me-1"></i>
                        <strong>Location:</strong> {{ $library->location }}
                    </p>
                    <p class="card-text mb-2">
                        <i class="bi bi-person-check text-secondary me-1"></i>
                        <strong>Admin:</strong>
                        @if ($library->admin)
                        {{ $library->admin->name }}
                        <small class="text-muted">({{ $library->admin->email }})</small>
                        @else
                        <a class="btn btn-sm btn-outline-primary mt-1 mt-md-0" href="{{ route('assign.admin', $library->id) }}">Assign Admin</a>
                        @endif
                    </p>
                    <p class="card-text mb-2">
                        <i class="bi bi-book text-primary me-1"></i>
                        <strong>No. of Books:</strong> {{ $library->books->count() }}
                    </p>
                    <p class="card-text mb-2">
                        <i class="bi bi-people text-primary me-1"></i>
                        <strong>No. of Librarians:</strong> {{ $library->librarians->count() }}
                    </p>
                    <p class="card-text mb-2">
                        <i class="bi bi-cash text-primary me-1"></i>
                        <strong>Revenue:</strong> ₹{{ $library->fines->sum('amount') }}
                    </p>
                    <p class="card-text mt-2 text-center">
                        <a class="btn btn-sm btn-outline-warning me-1 fw-bold"
                            href="{{ route('edit.library', $library->id) }}">
                            <i class="bi bi-pencil fw-bold"></i> Edit
                        </a>
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



@if(session('libraryToast'))
<script>
    let Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: 'success',
        title: "{{ session('libraryToast') }}"
    })
</script>
@endif
@endpush