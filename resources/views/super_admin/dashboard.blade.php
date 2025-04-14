@extends('layout.app')

@section('title', 'Super Admin Dashboard')

@push('styles')
<link rel="stylesheet" href=" {{ url('css/super_admin_dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Library Analytics Dashboard</h2>
    
    <div class="row g-4">
        <!-- Top Choices Books -->
        <div class="col-12 col-md-6 col-xl-3" data-type="top-books" data-route="">
            <div class="member-card bg-purple p-4 shadow" style="background: #6f42c1;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">1.2K</h5>
                        <p class="mb-0">Top Choices Books</p>
                    </div>
                    <i class="bi bi-fire card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Popular Libraries -->
        <div class="col-12 col-md-6 col-xl-3" data-type="popular-libraries" data-route="">
            <div class="member-card bg-orange p-4 shadow" style="background: #fd7e14;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">48</h5>
                        <p class="mb-0">Popular Libraries</p>
                    </div>
                    <i class="bi bi-building-gear card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Most Reserved Books -->
        <div class="col-12 col-md-6 col-xl-3" data-type="reserved-books" data-route="">
            <div class="member-card bg-teal p-4 shadow" style="background: #20c997;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">356</h5>
                        <p class="mb-0">Most Reserved Books</p>
                    </div>
                    <i class="bi bi-bookmark-star card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-12 col-md-6 col-xl-3" data-type="total-revenue" data-route="">
            <div class="member-card bg-blue p-4 shadow" style="background: #0d6efd;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">₹1.2M</h5>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                    <i class="bi bi-currency-rupee card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Content Section -->
    <div id="dynamic-content" class="dynamic-content"></div>
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush