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
    <div class="row" id="bookGrid">
        @foreach($books as $book)
        <div class="col-md-4 mb-4 book-card" data-category="{{ Str::slug($book->category->name) }}">
            <div class="card h-100 border-0 shadow-sm">
                @if($book->image)
                <img src="{{ asset('storage/'.$book->image) }}" class="card-img-top" alt="Book Image" style="object-fit: cover;">
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary fw-semibold">{{ $book->title }}</h5>

                    <p class="card-text mb-1">
                        <i class="bi bi-person-fill me-1 text-muted"></i><strong>Author:</strong> {{ $book->author->name }}
                    </p>
                    <p class="card-text mb-1">
                        <i class="bi bi-box-seam me-1 text-muted"></i><strong>Available:</strong> {{ $book->total_copies }}
                    </p>

                    <div class="mb-2 d-flex justify-content-between">
                        <span class="badge bg-info text-dark"><i class="bi bi-tags-fill me-1"></i>{{ $book->category->name }}</span>
                        <span class="badge bg-secondary"><i class="bi bi-journal-code me-1"></i>{{ $book->edition }} Ed.</span>
                    </div>

                    <div class="mb-2 d-flex justify-content-between small text-muted">
                        <span><i class="bi bi-calendar2-week me-1"></i><strong>Year:</strong> {{ $book->published_year }}</span>
                        <span><i class="bi bi-upc-scan me-1"></i><strong>ISBN:</strong> {{ $book->isbn }}</span>
                    </div>

                    <marquee behavior="scroll" direction="left" scrollamount="3" class="text-danger mt-auto mb-2">
                        🔥 <strong>{{ rand(1, 10) }}</strong> readers borrowed this book in the last hour!
                    </marquee>

                    <div class="d-flex justify-content-center">

                        <a href="#" class="btn btn-sm btn-outline-success px-3">
                            <i class="bi bi-book-half me-1"></i> Borrow
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-danger px-3">
                            <i class="bi bi-calendar-check me-1"></i>
                            Reserve
                        </a>

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
<script src=" {{ url('js/library_admin/manage_books.js') }} "></script>
<script>
    let currentPage = 1;
    let searchTimeout = null; 

    document.getElementById('bookSearch').addEventListener('input', function() {
        const searchQuery = this.value.trim();

        // Clear any pending timeouts
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // timeout is used for not appending same card multiple time
        searchTimeout = setTimeout(() => {
            if (searchQuery.length > 2) { // at least 3 character should be entered to search
                fetch('/books/search', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            query: searchQuery
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // First clear the container
                        const contentContainer = document.getElementById('bookGrid');
                        contentContainer.innerHTML = '';

                        // Then append the new card of results
                        displaySearchResults(data.books);
                    })
                    .catch(error => console.error('Error performing search:', error));
            } else if (searchQuery.length === 0) {
                resetToNormalView();
            }
        }, 300); 
    });

    function displaySearchResults(books) {
        const contentContainer = document.getElementById('bookGrid');

        // if no results is found
        if (books.length === 0) {
            contentContainer.innerHTML = '<div class="col-12 text-center"><p class="alert alert-info">No books found matching your search criteria.</p></div>';
        } else {
            // Add a "clear search" button above the results
            const clearButton = document.createElement('div');
            clearButton.className = 'col-12 mb-3 text-end';
            clearButton.innerHTML = '<button class="btn btn-sm btn-outline-secondary" id="clearSearch">Clear Search</button>';
            contentContainer.appendChild(clearButton);

            books.forEach(book => {
                const bookElement = createBookElement(book);
                contentContainer.appendChild(bookElement);
            });

            document.getElementById('clearSearch').addEventListener('click', function() {
                document.getElementById('bookSearch').value = '';
                resetToNormalView();
            });
        }

        // remove pagination during search results display
        const paginationElement = document.querySelector('.pagination');
        if (paginationElement) {
            paginationElement.style.display = 'none';
        }
    }

    function resetToNormalView() {
        window.location.href = '/browse-books?page=' + currentPage;
    }
            //creating a card to show the result
    function createBookElement(book) {
        const div = document.createElement('div');
        div.className = 'col-md-4 mb-4 book-card';
        div.setAttribute('data-category', book.category_slug);

        div.innerHTML = `
    <div class="card h-100 border-0 shadow-sm">
      ${book.image ? `<img src="${book.image_url}" class="card-img-top" alt="Book Image" style="object-fit: cover;">` : ''}
      <div class="card-body d-flex flex-column">
        <h5 class="card-title text-primary fw-semibold">${book.title}</h5>
        <p class="card-text mb-1">
          <i class="bi bi-person-fill me-1 text-muted"></i><strong>Author:</strong> ${book.author_name}
        </p>
        <p class="card-text mb-1">
          <i class="bi bi-box-seam me-1 text-muted"></i><strong>Available:</strong> ${book.total_copies}
        </p>
        <div class="mb-2 d-flex justify-content-between">
          <span class="badge bg-info text-dark"><i class="bi bi-tags-fill me-1"></i>${book.category_name}</span>
          <span class="badge bg-secondary"><i class="bi bi-journal-code me-1"></i>${book.edition} Ed.</span>
        </div>
        <div class="mb-2 d-flex justify-content-between small text-muted">
          <span><i class="bi bi-calendar2-week me-1"></i><strong>Year:</strong> ${book.published_year}</span>
          <span><i class="bi bi-upc-scan me-1"></i><strong>ISBN:</strong> ${book.isbn}</span>
        </div>
        <marquee behavior="scroll" direction="left" scrollamount="3" class="text-danger mt-auto mb-2">
          🔥 <strong>${Math.floor(Math.random() * 10) + 1}</strong> readers borrowed this book in the last hour!
        </marquee>
        <div class="d-flex justify-content-center">
          <a href="#" class="btn btn-sm btn-outline-success px-3">
            <i class="bi bi-book-half me-1"></i> Borrow
          </a>
          <a href="#" class="btn btn-sm btn-outline-danger px-3">
            <i class="bi bi-calendar-check me-1"></i> Reserve
          </a>
        </div>
      </div>
    </div>
  `;

        return div;
    }
</script>
@endpush