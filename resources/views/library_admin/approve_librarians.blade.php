@extends('layout.app')

@section('title', 'Approve Librarians')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />


@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Librarian Management</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">Librarian Name</th>
                            <th scope="col" class="text-center">Email</th>
                            <th scope="col" class="text-center">Registered on</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($librarians as $key => $librarian)
                        <tr>
                            <td class="text-center">{{ $librarian['name'] }}</td> <!-- Access as array -->
                            <td class="text-center"><a href="mailto:{{ $librarian['email'] }}">{{ $librarian['email'] }}</a></td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $librarian['created_at'] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Accept or reject">
                                    <button data-url="{{ route('librarians.accept_or_reject', ['id' => $librarian->librarian->id, 'action' => 'accept']) }}"
                                        class="btn btn-sm btn-success rounded-start"
                                        title="Accept">
                                        <i class="fas fa-check-circle me-1"></i> Accept
                                    </button>
                                    <button data-url="{{ route('librarians.accept_or_reject', ['id' => $librarian->librarian->id, 'action' => 'reject']) }}"
                                        class="btn btn-sm btn-danger rounded-end"
                                        title="Reject">
                                        <i class="fas fa-times-circle me-1"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No pending librarians found.</td>
                        </tr>
                        @endforelse


                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }

        .table thead {
            display: none;
        }

        .table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        .table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dotted #ddd;
        }

        .table td::before {
            content: attr(data-label);
            font-weight: bold;
            padding-right: 1rem;
        }

        .table td.text-center {
            justify-content: space-between;
        }
    }
</style>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

<script src="{{ url('js/library_admin/approve_librarian.js') }}"></script>
@endpush