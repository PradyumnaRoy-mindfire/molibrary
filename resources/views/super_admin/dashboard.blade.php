@extends('layout.app')

@section('title', 'Super Admin Dashboard')
<style>
    body{
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