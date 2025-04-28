@extends('layout.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ url('css/member/borrow_confirmation.css') }}">
@endpush

@section('content')
<div class="container  ">
    <div class="book-confirm-container row d-flex align-items-center">

        <div class="col-md-4 book-image-container bg-light">
            <img src="{{ asset('storage/'.$book->image) }}" alt="Book Cover" class="book-image img-fluid">
        </div>

        <div class="col-md-8 book-details ">
            <h1 class="book-title">{{$book->title}}</h1>
            <p class="library-name fw-bold">{{ucwords($library->name,' ')}},📍{{ ucwords($library->location,' ')}}</p>

            <div class="book-metadata">
                <div class="row metadata-columns">
                    <div class="col-6">
                        <div class="metadata-item">
                            <i class="fas fa-user-edit metadata-icon"></i>
                            <span class="metadata-label">Author:</span>
                            <span class="metadata-value">{{$book->author->name}}</span>
                        </div>

                        <div class="metadata-item">
                            <i class="fas fa-barcode metadata-icon"></i>
                            <span class="metadata-label">ISBN:</span>
                            <span class="metadata-value">{{$book->isbn}}</span>
                        </div>

                        <div class="metadata-item">
                            <i class="fas fa-bookmark metadata-icon"></i>
                            <span class="metadata-label">Edition:</span>
                            <span class="metadata-value">{{$book->edition}} </span>
                        </div>

                        <div class="metadata-item">
                            <i class="fas fa-tags metadata-icon"></i>
                            <span class="metadata-label">Genre:</span>
                            <span class="metadata-value">{{$book->category->name}}</span>
                        </div>

                    </div>

                    <div class="col-6">
                        <div class="metadata-item">
                            <i class="fas fa-book metadata-icon"></i>
                            <span class="metadata-label">Available:</span>
                            @if($book->total_copies == 0)
                            <span class="metadata-value">Out of Stock</span>
                            <span class="badge rounded-pill" style="background-color:rgb(243, 114, 114); color:rgb(248, 12, 12);">Coming Soon</span>
                            @elseif($book->total_copies < 6)
                                @if($book->total_copies == 1)Last copy
                                @else <span class="metadata-value">{{$book->total_copies}} copies
                                    @endif left,Hurry Up!!
                                    <span class="availability-tag low-stock">Low Stock</span>
                                    @else
                                    <span class="metadata-value">{{$book->total_copies}} copies left</span>
                                    <span class="badge rounded-pill" style="background-color:rgb(119, 247, 68); color:rgb(1, 151, 26);">In Stock</span>
                                    @endif
                        </div>

                        <div class="metadata-item">
                            <i class="fas fa-tablet-alt metadata-icon"></i>
                            <span class="metadata-label">E-Book:</span>
                            <span class="metadata-value">@if($book->has_ebook === 1)
                                Available
                                @else Not Available
                                @endif</span>
                            <span class="ebook-badge">
                                <i class="fas fa-tablet-alt"></i> E-Book
                            </span>

                        </div>

                        <div class="metadata-item">
                            <i class="fas fa-chart-line metadata-icon"></i>
                            <span class="metadata-label">Borrowed:</span>
                            <span class="metadata-value">{{$borrowCount}}</span>
                            @if($borrowCount > 0)
                            <span class="availability-tag reserved-badge">Popular</span>
                            @endif
                        </div>

                        <div class="metadata-item">
                            <i class="fas fa-calendar-alt metadata-icon"></i>
                            <span class="metadata-label">Published:</span>
                            <span class="metadata-value">{{$book->published_year}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="book-description-container">
                <h5 class="fw-bold">Description</h5>
                <div id="description-text" class="book-description collapsed-description">
                    <p>{!! nl2br($book->description) !!}</p>
                </div>
                <span id="read-more-btn" class="read-more-btn">See more</span>
            </div>

            <div class="action-buttons">
                <a href="{{ route('browse.books') }}" class="btn btn-outline-secondary btn-back ms-2">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>

                <a class="btn btn-preview ms-2 btn-outline-primary">
                    <i class="fas fa-book-open me-2"></i>Preview
                </a>

                @if($action == 'borrow')
                <a href=" {{ route('borrow.books',$book->id) }}" class="btn btn-success btn-borrow ms-2">
                    <i class="fas fa-check-circle me-2"></i>Confirm Borrow
                </a>
                @else 
                <a href=" {{ route('reserve.books',$book->id) }}" class="btn btn-warning btn-borrow ms-2">
                    <i class="fas fa-check-circle me-2"></i>Confirm Reserve
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
//     $(document).ready(function() {
//     $('.btn-borrow').click(function() {
//         Swal.fire({
//             title: "Congrats!",
//             text: "You have successfully borrowed the book.",
//             imageUrl: "{{ asset('storage/borrowPopup.png') }}",
//             imageWidth: 400,
//             imageHeight: 200,
//             imageAlt: "Custom image"
//           });
//     });
// });
</script>
<script src="{{ url('js/member/borrow_confirmation.js') }}"> </script>
@endpush