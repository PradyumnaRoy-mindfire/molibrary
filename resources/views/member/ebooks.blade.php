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
            <div class="card h-100 border-0 shadow-sm">
                @if($book->image)
                <img src="{{ asset('storage/'.$book->image) }}" class="card-img-top " alt="Book Image" style="height:250px; object-fit: cover; object-position: center;width: 100%;">
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
                        <span><i class="bi bi-upc-scan me-1"></i><strong>ISBN:</strong> {{ $book->isbn }}</span>
                    </div>

                    <marquee behavior="scroll" direction="left" scrollamount="3" class="text-danger mt-auto mb-0">
                        🔥 <strong>{{ rand(1, 10) }}</strong> readers checked out this book in the last hour!
                    </marquee>

                    <div class="availability-section mb-2">

                        <div class="d-flex justify-content-between align-items-center small text-secondary mb-1">
                            <span>Reading Progress</span>
                            <span>{{$book->ebook->reading_progress ?? 0}}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar"
                                style="width: {{$book->ebook->reading_progress ?? 0}}%"
                                aria-valuenow="{{$book->ebook->reading_progress ?? 0}}"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        @if(($book->ebook->reading_progress ?? 0) == 100)
                        <a href="{{ route('read.ebook', ['book' => $book->id]) }}" class="btn btn-sm btn-outline-info px-3 fw-bold">
                            <i class="bi bi-book-half me-1 fs-6"></i> Completed Reading
                        </a>
                        @elseif(($book->ebook->reading_progress ?? 0) == 0)
                        <a href="{{ route('read.ebook',['book' => $book->id]) }}" class="btn btn-sm btn-outline-success px-3 fw-bold">
                            <i class="bi bi-book-half me-1 fs-6"></i> Start Reading
                        </a>
                        @else
                        <a href="{{ route('read.ebook', ['book' => $book->id]) }}" class="btn btn-sm btn-outline-warning px-3 fw-bold">
                            <i class="bi bi-book-half me-1 fs-6"></i> Continue Reading
                        </a>
                        @endif

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
        title: 'No E-Book Membership Found',
        text: 'You must have an active membership to read e-books.',
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





<script src=" {{ url('js/library_admin/manage_books.js') }} "></script>
<script>
    let csrf = '{{ csrf_token() }}';
</script>

<script src=" {{ url('js/member/ebooks.js') }} "></script>
@endpush