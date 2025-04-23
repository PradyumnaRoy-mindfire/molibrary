@extends('layout.app')

@section('title', 'Book Management')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ url('css/librarian/book_management.css') }}">

@endpush

@section('content')
    <div class="container">
        <h1>Library Request Management</h1>
        <div class="search">
            <input type="text" id="searchInput" placeholder="Search by name, title, or ISBN...">
            <select class="filter-select" id="filterSelect">
                <option value="all">All Requests</option>
                <option value="borrow">Borrow Requests</option>
                <option value="return">Return Requests</option>
            </select>
        </div>
        
        <div style="overflow-x: auto;">
            <table id="requestsTable">
                <thead>
                    <tr>
                        <th class="text-center">Member Name</th>
                        <th class="member-email text-center">Email</th>
                        <th class="text-center">Book Title</th>
                        <th class="isbn text-center">ISBN</th>
                        <th class="text-center">Availability</th>
                        <th class="text-center">Request For</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                    
                    
                    @foreach($borrowRequests as $request)
                        @php
                            $user = $request->user;
                          
                            $book = $request->book;
                            $isAvailable = $book->total_copies > 0 || $request->type === 'return';
                            $requestType = $request->type; 
                        @endphp
                        
                        <tr data-request-id="{{ $request->id }}" class="request-row">
                            <td class="text-center">{{ $user->name }}</td>
                            <td class="member-email text-center">{{ $user->email }}</td>
                            <td class="text-center">{{ $book->title }}</td>
                            <td class="isbn text-center">{{ $book->isbn }}</td>
                            <td class="text-center">
                                <span class="status {{ $isAvailable ? 'available' : 'unavailable' }}">
                                    {{ $isAvailable ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td class="text-center">{{ ucfirst($requestType) }}</td>
                            <td class="action-cell text-center">
                                @if($request->status === 'pending')
                                    <div class="">
                                        <button class="btn btn-approve "  style=" background-color: #10b981;
            color: white;"
                                            data-url="{{ route('library.process-request', ['id' => $request->id]) }}" 
                                            data-request-id="{{ $request->id }}" 
                                            data-action="approve">Approve</button>
                                        <button class="btn btn-reject " style="background-color: #ef4444;
            color: white;" 
                                            data-url="{{ route('library.process-request', ['id' => $request->id]) }}" 
                                            data-request-id="{{ $request->id }}" 
                                            data-action="reject">Reject</button>
                                    </div>
                                @else
                                    <span class="status {{ $request->status === 'borrowed' ? 'approved' : 'unavailable' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').addEventListener('keyup', function() {
                applyFilters();
            });

            document.getElementById('filterSelect').addEventListener('change', function() {
                applyFilters();
            });

            function applyFilters() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const filterValue = document.getElementById('filterSelect').value;
                
                const rows = document.querySelectorAll('#requestsTable tbody tr');
                
                rows.forEach(row => {
                    const memberName = row.cells[0].textContent.toLowerCase();
                    const memberEmail = row.cells[1].textContent.toLowerCase();
                    const bookTitle = row.cells[2].textContent.toLowerCase();
                    const isbn = row.cells[3].textContent.toLowerCase();
                    const requestType = row.cells[5].textContent.toLowerCase();
                    
                    const matchesSearch = 
                        memberName.includes(searchTerm) || 
                        memberEmail.includes(searchTerm) || 
                        bookTitle.includes(searchTerm) || 
                        isbn.includes(searchTerm);
                    
                    const matchesFilter = 
                        filterValue === 'all' || 
                        (filterValue === 'borrow' && requestType.toLowerCase() === 'borrow') || 
                        (filterValue === 'return' && requestType.toLowerCase() === 'return');
                    
                    if (matchesSearch && matchesFilter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            const approveButtons = document.querySelectorAll('.btn-approve');
            const rejectButtons = document.querySelectorAll('.btn-reject');
            
            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-request-id');
                    const url = this.getAttribute('data-url');
                    const row = this.closest('tr');
                    const memberName = row.cells[0].textContent;
                    const bookTitle = row.cells[2].textContent;
                    
                    // Show SweetAlert confirmation
                    Swal.fire({
                        title: 'Approve Request?',
                        html: `Do you want to approve the request for <strong>${bookTitle}</strong> by <strong>${memberName}</strong>?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, approve it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processRequest(requestId, url, 'approve', this);
                        }
                    });
                });
            });
            
            rejectButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-request-id');
                    const url = this.getAttribute('data-url');
                    const row = this.closest('tr');
                    const memberName = row.cells[0].textContent;
                    const bookTitle = row.cells[2].textContent;
                    
                    // Show SweetAlert confirmation
                    Swal.fire({
                        title: 'Reject Request?',
                        html: `Do you want to reject the request for <strong>${bookTitle}</strong> by <strong>${memberName}</strong>?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, reject it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processRequest(requestId, url, 'reject', this);
                        }
                    });
                });
            });
            
            function processRequest(requestId, url, action, buttonElement) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ request_id: requestId, action: action })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || `Request ${action}d successfully!`,
                            icon: 'success',
                            confirmButtonColor: '#10b981'
                        });
                        
                        // Update UI
                        const actionCell = buttonElement.closest('.action-cell');
                        const statusClass = action === 'approve' ? 'approved' : 'unavailable';
                        const statusText = action === 'approve' ? 'Approved' : 'Rejected';
                        actionCell.innerHTML = `<span class="status ${statusClass}">${statusText}</span>`;
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Something went wrong.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error processing request:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
            }
        });
    </script>
@endpush