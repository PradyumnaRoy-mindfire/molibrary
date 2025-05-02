let currentPage = 1;
    let searchTimeout = null; 

    document.getElementById('bookSearch').addEventListener('input', function() {
        const searchQuery = this.value.trim();

        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        searchTimeout = setTimeout(() => {
            if (searchQuery.length > 2) { // at least 3 character should be entered to search
                fetch('/books/search', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            query: searchQuery
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const contentContainer = document.getElementById('bookGrid');
                        contentContainer.innerHTML = '';

                        // appending the new card of results
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
            
            const clearButton = document.createElement('div');
            clearButton.className = 'col-12 mb-3 text-end mt-0';
            clearButton.innerHTML = '<button class="btn btn-sm btn-secondary text-white" style="float: right;" id="clearSearch">Clear Search</button>';
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

        
        const paginationElement = document.querySelector('.pagination');
        if (paginationElement) {
            paginationElement.style.display = 'none';
        }
    }

    function resetToNormalView() {
        window.location.href = '/e-books?page=' + currentPage;
    }
            //creating a card to show the result
    function createBookElement(book) {
        const div = document.createElement('div');
        div.className = 'col-md-3 mb-4 book-card';
        div.setAttribute('data-category', book.category_slug);

        div.innerHTML = `
    <div class="card h-100 border-0 shadow-sm">
      ${book.image ? `<img src="${book.image_url}" class="card-img-top" alt="Book Image" style="height:250px; object-fit: cover; object-position: center;width: 100%;">` : ''}
      <div class="card-body d-flex flex-column">
        <h5 class="card-title text-primary fw-semibold">${book.title}</h5>
        <p class="card-text mb-1">
          <i class="bi bi-person-fill me-1 text-muted"></i><strong>Author:</strong> ${book.author_name}
        </p>
        <p class="card-text mb-1">
          <i class="bi bi-box-seam me-1 text-muted"></i><strong>Library:</strong> ${book.library}
        </p>
        <div class="mb-2 d-flex justify-content-between">
          <span class="badge bg-info text-dark"><i class="bi bi-tags-fill me-1"></i>${book.category_name}</span>
          <span class="badge bg-secondary"><i class="bi bi-journal-code me-1"></i>${book.edition} Ed.</span>
        </div>
        <div class="mb-2 d-flex justify-content-between small text-muted">
          <span><i class="bi bi-upc-scan me-1"></i><strong>ISBN:</strong> ${book.isbn}</span>
        </div>
        <marquee behavior="scroll" direction="left" scrollamount="3" class="text-danger mt-auto mb-2">
          🔥 <strong>${Math.floor(Math.random() * 10) + 1}</strong> readers checked out this book in the last hour!
        </marquee>
        <div class="d-flex justify-content-center">
          <a href="#" class="btn btn-sm btn-outline-success px-3">
            <i class="bi bi-book-half me-1"></i> Start Reading
          </a>
          
        </div>
      </div>
    </div>
  `;

        return div;
    }