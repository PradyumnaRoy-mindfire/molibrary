@extends('layout.app')

@section('title' ,'Borrow history')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --light-bg: #f8f9fa;
            --dark-text: #343a40;
            --danger: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .due-date-ok {
            background-color: rgba(46, 204, 113, 0.2);
            color: darkgreen;
        }
        
        .due-date-overdue {
            background-color: rgba(231, 76, 60, 0.2);
            color: darkred;
        }
        
       
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .status-paid {
            background-color: var(--success);
            color: black;
        }
        
        .status-pending {
            background-color: var(--warning);
            color: black;
        }
        
        @media (max-width: 768px) {
            .action-buttons .btn {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
@endpush


@section('content')


    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="text-white">My Borrowing History</h2>
                <p class="text-white">View all books you've borrowed along with their details and status</p>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover" id="borrowingTable">
                    <thead>
                        <tr>
                            <th class="text-center">Book Title</th>
                            <th class="text-center" >ISBN</th>
                            <th class="text-center" >Library</th>
                            <th class="text-center" >Issued Date</th>
                            <th class="text-center" >Due Date</th>
                            <th class="text-center" >Total Fine</th>
                            <th class="text-center" >Fine Status</th>
                            <th class="text-center" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowings as $borrowing)
                            @php
                                $isOverdue = strtotime($borrowing->due_date) < strtotime('now') && !$borrowing->returned;
                            @endphp
                            <tr>
                                <td class="text-center" >{{ $borrowing->title }}</td>
                                <td class="text-center" >{{ $borrowing->isbn }}</td>
                                <td class="text-center" >{{ $borrowing->library }}</td>
                                <td class="text-center" >{{ date('M d, Y', strtotime($borrowing->issued_date)) }}</td>
                                <td class="{{ $isOverdue ? 'due-date-overdue' : 'due-date-ok' }}" class="text-center" >
                                    {{ date('M d, Y', strtotime($borrowing->due_date)) }}
                                </td>
                                <td class="text-center" >Rs.{{ number_format($borrowing->fine, 2) }}</td>
                                <td class="text-center" >
                                @if( $borrowing->fine > 0)
                                    @if($borrowing->status == 'paid')
                                        <span class="status-badge status-paid">Paid</span>
                                    @elseif($borrowing->status == 'pending')
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                @else
                                    <span class="status-badge status-paid">N/A</span>
                                @endif
                                </td>
                                <td class="text-center" >
                                    @if($borrowing->returned)
                                        <span class="badge bg-secondary">Returned</span>
                                        @if( $borrowing->fine > 0)
                                                <button class="btn btn-sm btn-pay btn-warning" data-id="{{ $borrowing->id }}">Pay Now</button>
                                            @endif
                                    @else
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-return me-1 btn-primary" data-id="{{ $borrowing->id }}">Return</button>
                                            @if( $borrowing->fine > 0)
                                                <a class="btn btn-sm btn-pay btn-warning" data-id="{{ $borrowing->id }}">Pay Now</a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#borrowingTable').DataTable({
                order: [[3, 'desc']], 
                language: {
                    search: "Search records:",
                    zeroRecords: "No borrowing records found"
                },
                responsive: true
            });
            
            // Handle Return button click
            $('#borrowingTable').on('click', '.btn-return', function() {
                const id = $(this).data('id');
                
                // Send AJAX request to return the book
                $.ajax({
                    url: `/borrowing/${id}/return`,
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            // Reload the page to show updated data
                            window.location.reload();
                            alert('Book returned successfully!');
                        } else {
                            alert('Error returning book: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error processing request. Please try again.');
                    }
                });
            });
            
            // Handle Pay Now button click
            $('#borrowingTable').on('click', '.btn-pay', function() {
                const id = $(this).data('id');
                
                // Redirect to payment page
                window.location.href = `/borrowing/${id}/payment`;
            });
        });
    </script>
@endpush