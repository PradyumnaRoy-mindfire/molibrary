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
<div class="container-fluid py-4">
    <h2 class="mb-4">Welcome Back, {{ Auth::user()->name }}</h2>

    <div class="row g-4">
        <!-- Account Status Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="status" data-route="">
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

        <!-- Remaining Limits Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="limits" data-route="">
            <div class="member-card bg-dark p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">25</h5>
                        <p class="mb-0">Remaining Books Limit</p>
                    </div>
                    <i class="bi bi-journal-check card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Active Libraries Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="libraries" data-route="">
            <div class="member-card bg-primary p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">28</h5>
                        <p class="mb-0">Active Libraries</p>
                    </div>
                    <i class="bi bi-building card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Outstanding Fines Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="fines" data-route="">
            <div class="member-card bg-danger p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">₹2000.50</h5>
                        <p class="mb-0">Outstanding Fines</p>
                    </div>
                    <i class="bi bi-cash-coin card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Content Section -->
    <div id="dynamic-content" class="dynamic-content"></div>


    @endsection

    @push('scripts')
    <!-- <script>

    </script> -->
    @endpush