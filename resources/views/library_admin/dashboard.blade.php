@extends('layout.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href=" {{ url('css/super_admin_dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 text-center">
    <h2 class=" text-white">Hello, {{ Auth::user()->name }}</h2>
    <h4 class=" text-white mb-4"> {{ now()->format('M d, Y | l, h:iA')}} , {{$library->name}}</h4>

    <div class="row g-4">
        <div class="col-12 d-flex justify-content-center flex-wrap gap-5">
            <!-- Top Choices Books -->
            <div class="col-12 col-md-6 col-xl-3 overdue-books" data-type="top-books" data-route="{{ route('overdue.books') }}">
                <div class="member-card bg-purple p-4 shadow" style="background:rgb(5, 165, 45);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{$overDueBookCount}}</h5>
                            <p class="mb-0">Overdue Books</p>
                        </div>
                        <i class="bi bi-book card-icon"></i>
                    </div>
                </div>
            </div>


            <!-- Popular Libraries -->
            <div class="col-12 col-md-6 col-xl-3 popular-libraries" data-type="popular-libraries" data-route="{{ route('low.stock') }}">
                <div class="member-card bg-orange p-4 shadow" style="background:rgb(134, 6, 173);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{$lowStockBookCount}}</h5>
                            <p class="mb-0">Low Stock Books</p>
                        </div>
                        <i class="bi bi-building-gear card-icon"></i>
                    </div>
                </div>
            </div>



            <!-- Total Revenue -->
            <div class="col-12 col-md-6 col-xl-3 totalRevenue" data-type="total-revenue" data-route="{{ route('total.fine') }}">
                <div class="member-card bg-blue p-4 shadow" style="background:rgb(170, 159, 3);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">₹{{$totalFine}}</h5>
                            <p class="mb-0 fs-6">Total Revenue</p>
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

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Responsive DataTables JS -->
<!-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<script src="{{ url('js/library_admin/dashboard.js') }}"></script>
@endpush