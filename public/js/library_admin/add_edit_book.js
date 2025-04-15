document.addEventListener('DOMContentLoaded', function() {
    // toggling new author input
    const authorSelect = document.getElementById('author_id');
    const newAuthorInput = document.getElementById('new-author-input');

    authorSelect.addEventListener('change', function() {
        if (this.value === 'new-author') {
            newAuthorInput.classList.remove('d-none');
        } else {
            newAuthorInput.classList.add('d-none');
        }
    });

    // toggling ebook path based on radio button
    const hasEbookYes = document.getElementById('has_ebook_yes');
    const hasEbookNo = document.getElementById('has_ebook_no');
    const ebookPathContainer = document.getElementById('ebook-path-container');

    hasEbookYes.addEventListener('change', function() {
        if (this.checked) {
            ebookPathContainer.style.display = 'block';
        }
    });

    hasEbookNo.addEventListener('change', function() {
        if (this.checked) {
            ebookPathContainer.style.display = 'none';
        }
    });

    // Initializing the display state
    ebookPathContainer.style.display = hasEbookYes.checked ? 'block' : 'none';
});