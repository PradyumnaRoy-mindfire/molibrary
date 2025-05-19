@extends('layout.app')

@section('title', 'Member Dashboard')

@push('styles')
<style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    .member-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        color: white;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .member-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(196, 188, 188, 0.53));
        transform: rotate(45deg);
    }

    .member-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgb(0, 0, 0);
    }

    .card-icon {
        font-size: 2rem;
        opacity: 0.9;
        transition: transform 0.3s ease;
    }

    .member-card:hover .card-icon {
        transform: scale(1.1);
    }

    .membership-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .dynamic-content {
        transition: all 0.3s ease;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
    }

    .dynamic-content.active {
        max-height: 1000px;
        opacity: 1;
        margin-top: 20px;
    }
</style>



@endpush

@section('content')
<div class="container-fluid py-1">
    <h2 class=" text-white text-center">
    @php
        $hour = now()->hour;
        $greeting = "Good Morning";
        
        if ($hour >= 12 && $hour < 18) {
            $greeting = "Good Afternoon";
        } elseif ($hour >= 18) {
            $greeting = "Good Evening";
        }
    @endphp
         {{$greeting}} , {{ Auth::user()->name }}</h2>
    <h4 class=" text-white mb-3 text-center"> {{ now()->format('M d, Y | l, h:iA') }}</h4>
    <div class="row g-4">
        <!-- Account Status Card -->
        @if($membership !== null)
        <div class="col-12 col-md-6 col-xl-3 membership-card" data-type="status" data-route="{{ route('membership.details') }}">
            <div class="member-card bg-success p-4 shadow">
                <div class="membership-badge">
                    Base
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Active</h5>
                        <p class="mb-0">Membership Status</p>
                    </div>
                    <i class="bi bi-shield-check card-icon"></i>
                </div>
            </div>
        </div>
        @else
        <div class="col-12 col-md-6 col-xl-3 inactive-card" data-type="status" data-route="{{route('membership.features')}}">
            <div class="member-card bg-warning p-4 shadow">
                <div class="membership-badge">
                    None
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Inactive</h5>
                        <p class="mb-0">No Active Membership</p>
                    </div>
                    <i class="bi bi-exclamation-triangle card-icon"></i>
                </div>
            </div>
        </div>
        @endif
        <!-- Remaining Limits Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="limits" data-route="">
            <div class="member-card bg-dark p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $book_limit }}</h5>
                        <p class="mb-0">Remaining Books Limit</p>
                    </div>
                    <i class="bi bi-journal-check card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Active Libraries Card -->
        <div class="col-12 col-md-6 col-xl-3 activeLibrary-card" data-type="libraries" data-route="{{ route('active.library') }}">
            <div class="member-card bg-primary p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"> {{ $library ??'0'}}</h5>
                        <p class="mb-0">Active Libraries</p>
                    </div>
                    <i class="bi bi-building card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Outstanding Fines Card -->
        <div class="col-12 col-md-6 col-xl-3 fines-card" data-type="fines" data-route=" {{ route('outstanding.fines') }}">
            <div class="member-card bg-danger p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">₹{{ $fines ?? '0' }}</h5>
                        <p class="mb-0">Outstanding Fines</p>
                    </div>
                    <i class="bi bi-cash-coin card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Content Section -->
    <div id="dynamic-content" class="d-flex align-items-center justify-content-center mt-5"></div>


@endsection

@push('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Responsive DataTables JS -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<script src="{{ url('js/member/dashboard.js') }}"></script>

@endpush