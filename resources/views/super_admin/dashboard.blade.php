@extends('layout.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href=" {{ url('css/super_admin_dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 text-center">
    <h2 class=" text-white">
        @php
        $hour = now()->hour;
        $greeting = "Good Morning";

        if ($hour >= 12 && $hour < 18) {
            $greeting="Good Afternoon" ;
        } elseif ($hour>= 18) {
            $greeting = "Good Evening";
        }
        @endphp
            {{$greeting}} , {{ Auth::user()->name }}
    </h2>
    <h4 class=" text-white mb-4"> {{ now()->format('M d, Y | l, h:iA') }}</h4>

    <div class="row g-4">
        <div class="col-12 d-flex justify-content-center flex-wrap gap-5">
            <!-- Top Choices Books -->
            <div class="col-12 col-md-6 col-xl-3 choices-card" data-type="top-books" data-route="{{ route('top.books') }}">
                <div class="member-card bg-purple p-4 shadow" style="background: #6f42c1;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{$topBookCount}}</h5>
                            <p class="mb-0">Top Choices Books</p>
                        </div>
                        <i class="bi bi-fire card-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Popular Libraries -->
            <div class="col-12 col-md-6 col-xl-3 popular-libraries" data-type="popular-libraries" data-route="{{ route('popular.libraries') }}">
                <div class="member-card bg-orange p-4 shadow" style="background: #fd7e14;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{$popularLibraryCount}}</h5>
                            <p class="mb-0">Popular Libraries</p>
                        </div>
                        <i class="bi bi-building-gear card-icon"></i>
                    </div>
                </div>
            </div>



            <!-- Total Revenue -->
            <div class="col-12 col-md-6 col-xl-3 totalRevenue" data-type="total-revenue" data-route="{{ route('total.revenue') }}">
                <div class="member-card bg-blue p-4 shadow" style="background: #0d6efd;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">₹{{$fines}}</h5>
                            <p class="mb-0">Total Revenue</p>
                        </div>
                        <i class="bi bi-currency-rupee card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dynamic Content Section -->
    <div id="dynamic-content"></div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/super_admin/dashboard.js') }}"></script>
@endpush