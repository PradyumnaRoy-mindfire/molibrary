@extends('layout.app')

@section('title','Membership Plans')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href=" {{ url('css/membership_plans.css') }}">


@endpush

@section('content')
<div class="container py-0">
    <!-- Membership Plans  -->
    <h2 class="section-title text-center mb-4 text-white">Choose Your Membership Plan</h2>

    <div class="row d-flex justify-content-center">
        <!-- Plan Card s-->
        @foreach ($plans as $plan)
        <div class="col-md-5">
            <div class="card plan-card basic-plan">
                <div class="card-header">
                    <h3 class="mb-0">{{ $plan->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="plan-price">₹{{ $plan->amount }}</span>
                        <span class="plan-duration">/month</span>
                    </div>

                    <ul class="plan-features">
                        <li>
                            <i class="fas fa-book feature-icon"></i>
                            <strong>Book Limit:</strong> {{ $plan->max_books_limit }} books per month
                        </li>
                        <li>
                            <i class="fas fa-tablet-alt feature-icon"></i>
                            <strong>E-Book Access:</strong>

                            @if($plan->ebook_access == 1)
                            <span class="text-success"> Yes </span>
                            @else
                            <span class="text-danger"> No </span>
                            @endif

                        </li>
                        <li>
                            <i class="fas fa-clock feature-icon"></i>
                            <strong>Duration:</strong> {{ $plan->duration }} days
                        </li>
                        <li>
                            <i class="fas fa-info-circle feature-icon"></i>
                            <strong>Description:</strong>
                            <div class="description-container">
                                <div class="description-text">
                                    {{ $plan->description }}
                                </div>
                                <span class="toggle-description">
                                    <span class="more-text">See More <i class="fas fa-chevron-down"></i></span>
                                    <span class="less-text">See Less <i class="fas fa-chevron-up"></i></span>
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent text-center border-0 pb-4">
                    <a class="btn btn-primary btn-continue btn-basic" href="{{ route('checkout', $plan->id ) }}">Continue with this</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Membership History Section -->
    <h2 class="section-title text-center mt-4 mb-4 text-white">Your Membership History</h2>

    <div class="table-responsive history-table">
        <table class="table table-hover mb-0" id="membershipTable">
            <thead>
                <tr>
                    <th class="text-center">Transaction ID</th>
                    <th class="text-center">Plan</th>
                    <th class="text-center">Start Date</th>
                    <th class="text-center">End Date</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($memberships as $membership)
                @php
                 $upcoming = false;
                 if(now()->gt($membership->end_date)){
                    $membership->has_access = 0;
                 }
                 elseif(now()->lt($membership->start_date)){
                    $upcoming = true;
                 }
                 @endphp
                <tr>
                    <td class="text-center">{{ $membership->payments_method_id }}</td>
                    <td class="text-center">{{ ucwords($membership->plan->type,' ') }}</td>
                    <td class="text-center"  data-order="{{ \Carbon\Carbon::parse($membership->start_date)->format('Y-m-d H:i:s') }}">{{ \Carbon\Carbon::parse($membership->start_date)->format('M d, Y h:i A') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($membership->end_date)->format('M d, Y h:i A')}}</td>
                    <td class="text-center">@if($upcoming == true) <span class="badge badge-warning" style="background-color:rgb(247, 189, 16);">Upcoming</span> @elseif($membership->has_access == 1) <span class="badge badge-active">Active</span> @else <span class="badge badge-expired">Expired</span> @endif</td>
                </tr>
                @empty
                
                @endforelse

            </tbody>
        </table>
    </div>
</div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-description');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.classList.toggle('active');

                const descriptionText = this.parentElement.querySelector('.description-text');

                descriptionText.classList.toggle('expanded');
            });
        });
    });
</script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.4/sorting/datetime-moment.js"></script>


<script>
    $(document).ready(function() {
        $('#membershipTable').DataTable({
            order: [[2, 'desc']], // Order by 3th column (0-indexed) descending
            language: {
                searchPlaceholder: "Search membership...",
                search: "",
                paginate: {
                    previous: "<",
                    next: ">"
                }
            }
        });
    });
</script>

@endpush