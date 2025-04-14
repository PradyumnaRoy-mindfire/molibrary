@extends('layout.app')

@section('title' , 'Manage Books')

@section('content')
<div class="container py-4">

    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h3 class="mb-3 mb-md-0 text-white">Book Collection</h3>
        <a href="#" class="btn btn-success">
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
        <div class="col-md-4 mb-4 book-card" data-category="{{ Str::slug($book->category_name) }}">
            <div class="card shadow-sm h-100">
                @if($book->image)
                    <img src="{{ asset('storage/'.$book->image) }}" class="card-img-top" alt="Book Image" style="height: 250px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary">{{ $book->title }}</h5>
                    <p class="card-text mb-1"><strong>Author:</strong> {{ $book->author_name }}</p>
                    <p class="card-text mb-1"><strong>Library:</strong> {{ $book->library_name ?? 'N/A' }}</p>
                    <div class="mb-1 d-flex justify-content-between">
                        <span class="card-text"><strong>Category:</strong> {{ $book->category_name}}</span>
                        <span class="card-text"><strong>Edition:</strong> {{ $book->edition }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span class="card-text mb-1"><strong>Year:</strong> {{ $book->published_year }}</span>
                        <span class="card-text mb-3"><strong>ISBN:</strong> {{ $book->isbn }}</span>
                    </div>

                    <div class="mt-auto d-flex justify-content-between">
                        <a href="{{-- route('books.edit', $book->id) --}}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <a href="{{-- route('books.destroy', $book->id) --}}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this book?')">
                            <i class="bi bi-trash"></i> Delete
                        </a>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("bookSearch");
        const cards = document.querySelectorAll(".book-card");
        const categoryButtons = document.querySelectorAll(".category-filter");
        const clearFilterBtn = document.getElementById("clearFilter");

        // Global Search
        searchInput.addEventListener("input", function() {
            const query = this.value.toLowerCase();
            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                card.style.display = text.includes(query) ? "block" : "none";
            });
        });

        // Clear Filter
        clearFilterBtn.addEventListener("click", function() {
            searchInput.value = '';
            categoryButtons.forEach(btn => btn.classList.remove("active"));
            categoryButtons[0].classList.add("active");
            clearFilterBtn.classList.add("d-none");

            cards.forEach(card => card.style.display = "block");
        });
    });
    const selectedCategories = new Set();
    const buttons = document.querySelectorAll('.category-filter');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.getAttribute('data-category');

            // Handle "All"
            if (category === 'all') {
                selectedCategories.clear();
                buttons.forEach(btn => btn.classList.remove('active'));
                selectedCategories.add('all');
                updateButtons();
                filterBooks();
                return;
            }

            selectedCategories.delete('all');

            // Toggle category
            if (selectedCategories.has(category)) {
                selectedCategories.delete(category);
            } else {
                selectedCategories.add(category);
            }

            updateButtons();
            filterBooks();
        });
    });

    function updateButtons() {
        buttons.forEach(button => {
            const cat = button.getAttribute('data-category');

            if (cat === 'all') {
                button.classList.toggle('active', selectedCategories.size === 0 || selectedCategories.has('all'));
                button.innerHTML = `<i class="bi bi-grid-fill me-1"></i> All`;
                return;
            }

            const isActive = selectedCategories.has(cat);
            button.classList.toggle('active', isActive);

            button.innerHTML = `
                <i class="bi bi-bookmark-fill me-1"></i> ${cat.replace(/-/g, ' ')}
                ${isActive ? '<i class="bi bi-x-circle-fill ms-2 small deselect-icon" style="pointer-events:none;"></i>' : ''}
            `;
        });
    }

    function filterBooks() {
        const allBooks = document.querySelectorAll('.book-card');

        allBooks.forEach(book => {
            const bookCategories = book.getAttribute('data-category').split(' ');
            const isVisible = [...selectedCategories].some(cat => bookCategories.includes(cat)) || selectedCategories.has('all') || selectedCategories.size === 0;

            book.style.display = isVisible ? 'block' : 'none';
        });
    }

    // Initialize as "All" selected
    selectedCategories.add('all');
    updateButtons();
</script>
@endpush