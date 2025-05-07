@extends('layout.app')

@section('title','Library Admins')

@section('content')
<div class="container mt-5">
    <h3 class="text-center text-white mb-4">Library Admin Overview</h3>

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-primary text-center">
                <tr class="text-center">
                    <th>Admin Name</th>
                    <th>Phone</th>
                    <th>Registered Library</th>
                    <th>No. of Librarians</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                <tr class="text-center">
                    <td>{{ $admin->admin_name }}</td>
                    <td>{{ $admin->admin_phone }}</td>
                    <td>{{ $admin->library_name }}</td>
                    <td>{{ $admin->librarian_count }}</td>
                    <td>
                        <a href="{{ route('library.admin.edit', $admin->admin_id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square me-1"></i>Edit Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No admin records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $admins->links('pagination::bootstrap-5') }}
    </div>
</div>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

@if(session('libraryAdminUpdated'))
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
        title: 'Library Admin updated successfully..'
    })
</script>
@endif
@endsection