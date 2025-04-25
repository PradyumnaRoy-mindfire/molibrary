@extends('layout.app')

@section('title' ,'Borrow history')

@push('styles')



<link rel="stylesheet" href="{{ url('css/member/borrow_history.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
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
            <table class="table table-hover table-bordered " id="borrowingTable">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">Book Title</th>
                        <th class="text-center">ISBN</th>
                        <th class="text-center">Library</th>
                        <th class="text-center">Issued Date</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-center">Total Fine</th>
                        <th class="text-center">Fine Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $borrowing)
                    @php
                    $isOverdue = strtotime($borrowing->due_date) < strtotime('now') && !$borrowing->returned;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $borrowing->title }}</td>
                            <td class="text-center">{{ $borrowing->isbn }}</td>
                            <td class="text-center">{{ $borrowing->library }}</td>
                            <td class="text-center">{{ $borrowing->issued_date ? date('M d, Y, h:iA', strtotime($borrowing->issued_date)) : 'Not Issued' ;}}</td>
                            <td class="{{ $isOverdue ? 'due-date-overdue' : 'due-date-ok' }} text-center" >
                                {{ $borrowing->due_date ? date('M d, Y, h:iA', strtotime($borrowing->due_date)) : 'Not Issued' ;}}
                            </td>
                            <td class="text-center">Rs.{{ number_format($borrowing->fine, 2) }}</td>
                            <td class="text-center">
                                @if( $borrowing->fine > 0)
                                @if($borrowing->fine_status == 'paid')
                                <span class="status-badge status-paid fw-bold text-white">Paid</span>
                                @elseif($borrowing->fine_status == 'pending')
                                <span class="status-badge status-pending fw-bold text-white">Pending</span>
                                @endif
                                @else
                                <span class="status-badge status-na fw-bold text-white">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($borrowing->returned)
                                <span class="badge bg-secondary fs-6">Returned</span>
                                @if( $borrowing->fine > 0 && $borrowing->fine_status == 'pending')
                                <button class="btn btn-sm btn-pay btn-warning" data-url="{{ route('pay.fine', $borrowing->id) }}">Pay Now</button>
                                @endif
                                @else
                                <div class="action-buttons">
                                    @if($borrowing->borrow_date == null) 
                                    <span class="badge bg-info fs-6"> Borrow Requested</span>

                                    @elseif($borrowing->type == 'return' )
                                    <span class="badge bg-info fs-6"> Return Requested</span>
                                    @else
                                    <button class="btn btn-sm btn-return me-1 btn-primary " data-url="{{ route('return.book', $borrowing->id) }}">Return</button>
                                    @endif
                                    @if( $borrowing->fine > 0 && $borrowing->fine_status == 'pending')
                                    <button class="btn btn-sm btn-pay btn-warning" data-url="{{ route('pay.fine', $borrowing->id) }}">Pay Now</button>
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
<script src="{{ url('js/member/borrow_history.js')}}"></script>
@if(session('success'))
    <script> 
    Swal.fire({
                title: "Congrats!",
                text: "Borrow request sent successfully!!!",
                imageUrl: "{{ asset('storage/borrowPopup.png') }}",
                imageWidth: 400,
                imageHeight: 200,
                imageAlt: "Custom image"
            });
        
    </script>

@endif
@endpush