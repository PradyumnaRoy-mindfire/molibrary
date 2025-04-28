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
                            <td class="action-cell text-center ">
                                @if($request->status === 'pending')
                                    <div class="">
                                        @if($isAvailable)
                                        <button class="btn btn-approve "  style=" background-color: #10b981;
            color: white;"
                                            data-url="{{ route('library.process-request', ['id' => $request->id]) }}" 
                                            data-request-id="{{ $request->id }}" 
                                            data-action="approve">Approve</button>
                                        @endif
                                        <button class="btn btn-reject " style="background-color: #ef4444;
            color: white;" 
                                            data-url="{{ route('library.process-request', ['id' => $request->id]) }}" 
                                            data-request-id="{{ $request->id }}" 
                                            data-action="reject">Reject</button>
                                    </div>
                                @else
                                    <span class="status {{ $request->status === 'borrowed' ? 'approved' : 'unavailable' }} {{$request->status}}">
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
    <script src="{{ url('js/librarian/book_management.js') }}"></script>
@endpush