@extends('layout.app')

@section('title', 'Reserved Books') 

@push('styles')
<link rel="stylesheet" href="{{ url('css/member/borrow_history.css')  }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endpush


@section('content')


<div class="container">
    <div class="row mb-0">
        <div class="col-md-8">
            <h2 class="text-white">My Reserved Books</h2>
            <p class="text-white">View all books you've reserved along with their details and status</p>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover table-bordered " id="reservedTable">
                <thead class="table-primary">
                    <tr class="text-center align-middle">
                        <th class=" text-center align-middle">Book Title</th>
                        <th class=" text-center align-middle">ISBN</th>
                        <th class=" text-center align-middle">Library</th>
                        <th class=" text-center align-middle">Reserved Date</th>
                        <th class=" text-center align-middle">Expected Availability</th>
                        <th class=" text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservedBooks as $borrowing)
                    
                        <tr class=" text-center align-middle">
                            <td>{{ $borrowing->book->title }}</td>
                            <td>{{ $borrowing->book->isbn }}</td>
                            <td>{{ $borrowing->library->name }}</td>
                            <td>{{ $borrowing->borrow_date ? date('M d, Y, h:iA', strtotime($borrowing->borrow_date)) : 'Not Issued' ;}}</td>
                            <td>
                                @if( $borrowing->book->total_copies == 0)
                                <span class="badge bg-secondary fs-6 fw-bold text-white">{{ $borrowing->expected_availability ? date('M d, Y, h:iA', strtotime($borrowing->expected_availability)) : 'Not Available' }}</span>
                                @else
                                <span class="status-badge status-paid fw-bold text-white">Available Now!</span>
                                @endif
                            </td>
                            <td>
                                @if( $borrowing->book->total_copies == 0)
                                    <button class="btn btn-sm btn-cancel btn-danger text-white btn-cancel" data-url="{{-- route('reserved.cancel', $borrowing->id) --}}">Cancel Reservation</button>
                                @else
                                    <button class="btn btn-sm btn-borrow btn-success text-white btn-reserve" data-url="{{ route('reserved.book.borrow', $borrowing->id) }}"> Borrow Now</button>
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
<script src="{{ url('js/member/reserved_books.js') }}"></script>
@if(session('success'))
    <script> 
    // Swal.fire({
    //             title: "Congrats!",
    //             text: '{{ session("success") }}',
    //             imageUrl: "{{ asset('storage/borrowPopup.png') }}",
    //             imageWidth: 400,
    //             imageHeight: 200,
    //             imageAlt: "Custom image"
    //         });
        
    // </script>

@endif
@endpush



