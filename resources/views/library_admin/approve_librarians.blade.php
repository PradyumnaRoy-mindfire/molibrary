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
            <h5 class="mb-0 text-center">Librarian Management</h5>
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
                                @if($librarian->librarian->status == 'pending')
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
                                @elseif($librarian->librarian->status == 'approved')
                                <span class=" text-success fs-6 fw-bold">Approved</span>
                                @else
                                <span class=" text-danger fs-6 fw-bold">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        
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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

<script src="{{ url('js/library_admin/approve_librarian.js') }}"></script>
@endpush