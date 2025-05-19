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

$(document).ready(function(){
    $('#title').on('input',function(){
        $('.titleError').empty();
        $('#title').removeClass("is-invalid");
    });
    $('#author_id').on('input',function(){
        $('.authorError').empty();
        $('#author_id').removeClass("is-invalid");
    });
    $('#isbn').on('input',function(){
        $('.isbnError').empty();
        $('#isbn').removeClass("is-invalid");
    });
    $('#edition').on('input',function(){
        $('.editionError').empty();
        $('#edition').removeClass("is-invalid");
    });
    $('#category_id').on('input',function(){
        $('.categoryError').empty();
        $('#category_id').removeClass("is-invalid");
    });
    $('#published_year').on('input',function(){
        $('.publishError').empty();
        $('#published_year').removeClass("is-invalid");
    });
    $('#total_copies').on('input',function(){
        $('.totalCopyError').empty();
        $('#total_copies').removeClass("is-invalid");
    });
    $('input[type="radio"][name="has_ebook"]').on('change', function() {
        $('.hasEbookError').empty();
    });
    $('input[type="radio"][name="has_paperbook"]').on('change', function() {
        $('.hasPaperBookError').empty();
    });

    $('#image').on('change',function(){
        $('.imageError').empty();
        $('#image').removeClass("is-invalid");
    });
    $('#ebook_path').on('change',function(){
        $('.ebookError').empty();
        $('#ebook_path').removeClass("is-invalid");
    });
    $('#preview_content_path').on('change',function(){
        $('.previewError').empty();
        $('#preview_content_path').removeClass("is-invalid");
    });
    $('#description').on('input',function(){
        $('.descriptionError').empty();
        $('#description').removeClass("is-invalid");
    });
    
})