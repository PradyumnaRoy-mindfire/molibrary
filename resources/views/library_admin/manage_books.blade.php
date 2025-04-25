@extends('layout.app')

@section('title' , 'Manage Books')

@section('content')
<div class="container py-4">

    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h3 class="mb-3 mb-md-0 text-white">{{$books[0]->library->name}} Book Collection</h3>
        <a href="{{ route('add.book',$books[0]->library_id) }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Add Book
        </a>
    </div>

    {{-- Search Bar --}}
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
    <div class="row" id="bookGrid">
        @foreach($books as $book)
        <div class="col-md-3 mb-4 book-card" data-category="{{ Str::slug($book->category->name) }}">
            <div class="card shadow-sm h-100">
                @if($book->image)
                    <img src="{{ asset('storage/'.$book->image) }}" class="card-img-top" alt="Book Image" style="height: 250px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary">{{ $book->title }}</h5>
                    <p class="card-text mb-1"><strong>Author:</strong> {{ $book->author->name }}</p>
                    <p class="card-text mb-1"><strong>Available:</strong> {{ $book->total_copies}}</p>
                    <div class="mb-1 d-flex justify-content-between">
                        <span class="card-text"><strong>Category:</strong> {{ $book->category->name}}</span>
                        <span class="card-text"><strong>Edition:</strong> {{ $book->edition }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span class="card-text mb-1"><strong>Year:</strong> {{ $book->published_year }}</span>
                        <span class="card-text mb-3"><strong>ISBN:</strong> {{ $book->isbn }}</span>
                    </div>

                    <div class="mt-auto d-flex justify-content-between">
                        <a href="{{ route('edit.book', $book->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <button  data-url="{{ route('delete.book', $book->id) }}" class="btn btn-sm btn-outline-danger btnDelete">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection


@push('scripts')
<script src=" {{ url('js/library_admin/manage_books.js') }} "></script>
@endpush