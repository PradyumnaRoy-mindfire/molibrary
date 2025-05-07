@extends('layout.app')

@section('title' , 'Manage Books')

@push('styles')
<link rel="stylesheet" href="{{url('css/books.css')}}">
@endpush

@section('content')
<div class="container py-4">

    <!-- search bar -->
    <div class="mb-4">
        <input type="text" class="form-control form-control-lg" placeholder="Search by title, author, ISBN, etc..." id="bookSearch">
    </div>

    {{-- Category Filter Pills --}}
    <div class="mb-4">
        <div class="d-flex flex-wrap align-items-center" id="categoryFilters">
            <button class="btn btn-outline-secondary rounded-pill me-2 category-filter active" data-category="all">
                <i class="bi bi-grid-fill me-1"></i> All
            </button>
            @foreach($categories as $category)
            <button class="btn btn-outline-light rounded-pill me-2 category-filter" data-category="{{ Str::slug($category->name) }}">
                <i class="bi bi-bookmark-fill me-1"></i> {{ $category->name }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Book Cards  --}}
    <div class="row d-flex flex" id="bookGrid">
        @foreach($books as $book)
        <div class="col-md-3 mb-4 book-card" data-category="{{ Str::slug($book->category->name) }}">
            <div class="card h-100  shadow-sm">
                @if($book->image)
                <img src="{{ asset('storage/'.$book->image) }}" class="card-img-top card-img" alt="Book Image" style="height:250px; object-fit: cover; ">
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary fw-semibold">{{ $book->title }}</h5>

                    <p class="card-text mb-1">
                        <i class="bi bi-person-fill me-1 text-muted"></i><strong>Author:</strong> {{ $book->author->name ?? 'Unknown' }}
                    </p>
                    <p class="card-text mb-1">
                        <i class="bi bi-box-seam me-1 text-muted"></i><strong>Library:</strong> {{ $book->library->name }}

                    </p>

                    <div class="mb-2 d-flex justify-content-between">
                        <span class="badge bg-info text-dark"><i class="bi bi-tags-fill me-1"></i>{{ $book->category->name }}</span>
                        <span class="badge bg-secondary"><i class="bi bi-journal-code me-1"></i>{{ $book->edition }} Ed.</span>
                    </div>

                    <div class="mb-2 d-flex justify-content-between small text-muted">
                        <span><i class="bi bi-calendar2-week me-1"></i><strong>
                                @if($book->total_copies == 0)
                                <span class="text-danger fw-semibold">Out of Stock</span>
                                @elseif($book->total_copies < 6)
                                    <span class="text-warning fw-semibold">Only {{ $book->total_copies }} Left</span>
                        @else
                        <span class="text-success fw-semibold">{{ $book->total_copies }} Available</span>
                        @endif</strong> </span>
                        <span><i class="bi bi-upc-scan me-1"></i><strong>ISBN:</strong> {{ $book->isbn }}</span>
                    </div>

                    <marquee behavior="scroll" direction="left" scrollamount="3" class="text-danger mt-auto mb-2">
                        🔥 <strong>{{ rand(1, 10) }}</strong> readers borrowed this book in the last hour!
                    </marquee>

                    <div class="d-flex justify-content-center gap-2">
                        @if($book->total_copies > 0)
                        <a href="{{ route('borrow.confirmation',['action' => 'borrow', 'book' => $book->id] )}}" class="btn btn-sm btn-outline-success px-3 fw-bold">
                            <i class="bi bi-book-half me-1 fs-6"></i> Borrow
                        </a>
                        @else
                        <!-- <button class="btn btn-sm btn-outline-danger px-3 fw-bold" disabled>
                            <i class="bi bi-calendar-check me-1 fs-6"></i>
                            Coming Soon
                        </button> -->
                        <a href="{{ route('borrow.confirmation',['action' => 'reserve', 'book' => $book->id] )}}" class="btn btn-sm btn-outline-danger px-3 fw-bold">
                            <i class="bi bi-calendar-check me-1 fs-6"></i>
                            Reserve
                        </a>
                        @endif
                        <!-- <a href="#" class="btn btn-sm btn-outline-warning px-3 fw-bold  ">
                            <i class="bi bi-eye me-1 fs-6"></i> Preview
                        </a>  -->
                    </div>
                </div>
            </div>
        </div>

        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4 pagination">
        {{ $books->links() }}
    </div>
</div>
@endsection


@push('scripts')

@if(session('no_membership'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'No Active Membership',
        text: 'You must have an active membership to borrow books.',
        showCancelButton: true,
        confirmButtonText: 'Go to Membership Page',
        cancelButtonText: 'Close',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('memberships') }}";
        }
    });
</script>

@endif

@if(session('reserve_success'))
<script>
    Swal.fire({
        title: "Congrats!",
        text: '{{ session("reserve_success") }}',
        imageUrl: "{{ asset('storage/borrowPopup.png') }}",
        imageWidth: 400,
        imageHeight: 200,
        imageAlt: "Custom image"
    });
</script>

@endif



<script src=" {{ url('js/library_admin/manage_books.js') }} "></script>
<script>
    let csrf = '{{ csrf_token() }}';
</script>

<script src=" {{ url('js/member/books.js') }} "></script>
@endpush