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
    <div class="row mb-0">
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
                        <th class="text-center align-middle">Book Title</th>
                        <th class="text-center align-middle">ISBN</th>
                        <th class="text-center align-middle">Library</th>
                        <th class="text-center align-middle">Issued Date</th>
                        <th class="text-center align-middle">Due Date</th>
                        <th class="text-center align-middle">Total Fine</th>
                        <th class="text-center align-middle">Fine Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $borrowing)
                    @php
                    $isOverdue = strtotime($borrowing->due_date) < strtotime('now') && !$borrowing->returned;
                        @endphp
                        <tr class=" text-center align-middle">
                            <td>{{ $borrowing->book->title }}</td>
                            <td>{{ $borrowing->book->isbn }}</td>
                            <td>{{ $borrowing->library->name }}</td>
                            <td>{{ $borrowing->borrow_date ? date('M d, Y, h:iA', strtotime($borrowing->borrow_date)) : 'Not Issued' ;}}</td>
                            <td class="{{ $isOverdue ? 'due-date-overdue' : 'due-date-ok' }} ">
                                {{ $borrowing->due_date ? date('M d, Y, h:iA', strtotime($borrowing->due_date)) : 'Not Issued' ;}}
                            </td>
                            <td>Rs.{{ number_format(($borrowing->fine->amount ??0), 2) }}</td>
                            <td>
                                @if( ($borrowing->fine->amount ??0) > 0)
                                @if($borrowing->fine->status == 'paid')
                                <span class="status-badge status-paid fw-bold text-white">Paid</span>
                                @elseif($borrowing->fine->status == 'pending')
                                <span class="status-badge status-pending fw-bold text-white">Pending</span>
                                @endif
                                @else
                                <span class="status-badge status-na fw-bold text-white">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($borrowing->status === 'returned')
                                    <span class="badge bg-secondary fs-6 mb-1">Returned</span>
                                    
                                    @if( ($borrowing->fine->amount ?? 0) > 0 && $borrowing->fine->status == 'pending')
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
                                    @if( ($borrowing->fine->amount ??0) > 0 && $borrowing->fine->status == 'pending')
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
        text: '{{ session("success") }}',
        imageUrl: "{{ asset('storage/borrowPopup.png') }}",
        imageWidth: 400,
        imageHeight: 200,
        imageAlt: "Custom image"
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "{{ session('error') }}",
    });
</script>
@endif

@endpush