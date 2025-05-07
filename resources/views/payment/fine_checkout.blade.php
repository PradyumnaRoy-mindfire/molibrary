@extends('layout.app')

@section('title', 'Checkout')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ url('css/payment/checkout.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0 py-2 text-center">Pay - Fine For Overdue</h2>
                    
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-3 fs-4"></i>
                                <div>You're paying <strong>₹{{ $amount }}</strong> for the overdue book <strong>{{$book}}</strong>  borrowed from <strong>{{ $library }}</strong> </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('pay.fine', $borrow->id) }}"  method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="checkout" value="{{ session('checkout') }}">
                        <input type="hidden" name="amount" value="{{ $amount }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-1">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name">
                                    <label for="name"><i class="far fa-user me-2"></i>Full Name on Card</label>
                                    <div class="text-danger small mt-1 d-none" id="name-error">Name is required.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-1">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="{{ auth()->user()->email }}">
                                    <label for="email"><i class="far fa-envelope me-2"></i>Email Address</label>
                                    <div class="text-danger small mt-1 d-none" id="email-error">Valid email is required.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2"><i class="far fa-credit-card me-2"></i>Card Details</label>
                            <div id="card-element" class="form-control p-3" style="height: 45px;"></div>
                            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg w-100">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        <span>Cancel</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-lock me-2"></i>
                                        <span>Pay ₹ {{ $amount }} </span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i> Your payment is secure and encrypted
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
</script>

<script src=" {{ url('js/payment/checkout.js') }} "></script>
@if(session('payment_success'))
<script>
    Swal.fire({
        title: "Successful!!",
        text: "Your payment has been successfully processed.",
        icon: "success",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Okay!"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('borrowing.history') }}";
        }
    });
</script>
@endif
@endpush