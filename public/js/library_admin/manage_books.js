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
    clearFilterBtn?.addEventListener("click", function() {
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