@extends('layout.app')

@section('title','Membership Plans')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href=" {{ url('css/membership_plans.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <!-- Membership Plans Section -->
    <h2 class="section-title text-center mb-5 text-white">Choose Your Membership Plan</h2>
    
    <div class="row d-flex justify-content-center">
        <!-- Basic Plan Card -->
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
                    <a class="btn btn-primary btn-continue btn-basic" href="">Continue with this</a>
                </div>
            </div>
        </div>
        @endforeach
        <!-- Pro Plan Card -->
        <!-- <div class="col-md-6">
            <div class="card plan-card pro-plan">
                <div class="card-header">
                    <h3 class="mb-0">Pro Membership</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="plan-price">₹99</span>
                        <span class="plan-duration">/month</span>
                    </div>
                    
                    <ul class="plan-features">
                        <li>
                            <i class="fas fa-book feature-icon"></i>
                            <strong>Book Limit:</strong> Unlimited books
                        </li>
                        <li>
                            <i class="fas fa-tablet-alt feature-icon"></i>
                            <strong>E-Book Access:</strong>
                            <span class="text-success">Yes</span>
                        </li>
                        <li>
                            <i class="fas fa-clock feature-icon"></i>
                            <strong>Duration:</strong> 30 days
                        </li>
                        <li>
                            <i class="fas fa-info-circle feature-icon"></i>
                            <strong>Description:</strong> 
                            <div class="description-container">
                                <div class="description-text">
                                    For avid readers with unlimited access to our entire collection including e-books and premium titles. Enjoy exclusive early access to new releases and special member-only events. Our e-book platform allows you to read on any device, anytime, anywhere.
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
                    <button class="btn btn-primary btn-continue btn-pro">Continue with Pro</button>
                </div>
            </div>
        </div>
    </div> -->
    
    <!-- Membership History Section -->
    <h2 class="section-title text-center mt-5 mb-4 text-white">Your Membership History</h2>
    
    <div class="table-responsive history-table">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Plan</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#PAY-2023-0124</td>
                    <td>Basic Membership</td>
                    <td>Jan 24, 2023</td>
                    <td>Feb 23, 2023</td>
                    <td><span class="badge badge-expired">Expired</span></td>
                </tr>
                <tr>
                    <td>#PAY-2023-0428</td>
                    <td>Pro Membership</td>
                    <td>Apr 28, 2023</td>
                    <td>May 27, 2023</td>
                    <td><span class="badge badge-expired">Expired</span></td>
                </tr>
                <tr>
                    <td>#PAY-2023-0612</td>
                    <td>Basic Membership</td>
                    <td>Jun 12, 2023</td>
                    <td>Jul 11, 2023</td>
                    <td><span class="badge badge-expired">Expired</span></td>
                </tr>
                <tr>
                    <td>#PAY-2023-1019</td>
                    <td>Pro Membership</td>
                    <td>Oct 19, 2023</td>
                    <td>Nov 18, 2023</td>
                    <td><span class="badge badge-expired">Expired</span></td>
                </tr>
                <tr class="table-active">
                    <td>#PAY-2024-0317</td>
                    <td>Pro Membership</td>
                    <td>Mar 17, 2024</td>
                    <td>Apr 16, 2024</td>
                    <td><span class="badge badge-active">Active</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
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
@endpush