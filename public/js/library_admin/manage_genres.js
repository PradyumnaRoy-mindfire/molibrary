// CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Check if each description needs a "See more" button
document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');

    categoryCards.forEach(function(card) {
        const categoryId = card.getAttribute('data-category-id');

        // to check overflow for a card
        checkOverflow(categoryId);

        const textarea = document.getElementById(`descriptionInput${categoryId}`);
        const descContainer = document.getElementById(`description${categoryId}`);
        const descHeight = descContainer.scrollHeight;

        const lineHeight = parseInt(window.getComputedStyle(textarea).lineHeight);
        const minRows = Math.max(3, Math.ceil(descHeight / lineHeight));
        textarea.rows = minRows;
    });
});

function checkOverflow(id) {
    id = parseInt(id);
    const descContainer = document.getElementById(`description${id}`);
    const seeMoreBtn = document.getElementById(`seeMoreBtn${id}`);

    // If the content overflows, then show the seemore
    if (descContainer.scrollHeight > descContainer.clientHeight) {
        seeMoreBtn.style.display = 'block';
    } else {
        seeMoreBtn.style.display = 'none';
    }
}

function toggleDescription(id) {
    id = parseInt(id);
    const descContainer = document.getElementById(`description${id}`);
    const seeMoreBtn = document.getElementById(`seeMoreBtn${id}`);

    if (descContainer.classList.contains('expanded')) {
        descContainer.classList.remove('expanded');
        seeMoreBtn.textContent = 'See more';
    } else {
        descContainer.classList.add('expanded');
        seeMoreBtn.textContent = 'Show less';
    }
}

function toggleEdit(id) {
    id = parseInt(id);
    const editForm = document.getElementById(`editForm${id}`);
    const description = document.getElementById(`description${id}`);
    const seeMoreBtn = document.getElementById(`seeMoreBtn${id}`);
    const categoryNameElement = document.getElementById(`categoryName${id}`);
    const editNameForm = document.getElementById(`editNameForm${id}`);
    const errorMessage = document.getElementById(`error${id}`);

    // Hide error message
    errorMessage.style.display = 'none';

    if (editForm.classList.contains('active')) {
        cancelEdit(id); // Use cancel function to revert any unsaved changes
    } else {
        editForm.classList.add('active');
        description.style.display = 'none';
        seeMoreBtn.style.display = 'none';

        // Enable category name editing
        categoryNameElement.style.display = 'none';
        editNameForm.style.display = 'block';

        // Focus on the name input
        document.getElementById(`nameInput${id}`).focus();
    }
}

function cancelEdit(id) {
    id = parseInt(id);
    const editForm = document.getElementById(`editForm${id}`);
    const description = document.getElementById(`description${id}`);
    const seeMoreBtn = document.getElementById(`seeMoreBtn${id}`);
    const categoryNameElement = document.getElementById(`categoryName${id}`);
    const editNameForm = document.getElementById(`editNameForm${id}`);
    const errorMessage = document.getElementById(`error${id}`);

    // Hide error message
    errorMessage.style.display = 'none';

    // Close edit mode
    editForm.classList.remove('active');
    description.style.display = 'block';
    if (description.scrollHeight > description.clientHeight) {
        seeMoreBtn.style.display = 'block';
    }

    // Disable category name editing
    categoryNameElement.style.display = 'block';
    editNameForm.style.display = 'none';

    // Reset form values to original
    const nameInput = document.getElementById(`nameInput${id}`);
    nameInput.value = categoryNameElement.textContent;

    const textarea = document.getElementById(`descriptionInput${id}`);
    textarea.value = description.querySelector('p').textContent;
}

function validateCategory(id) {
    id = parseInt(id);
    const nameInput = document.getElementById(`nameInput${id}`);
    const descInput = document.getElementById(`descriptionInput${id}`);
    const errorMessage = document.getElementById(`error${id}`);

    const name = nameInput.value.trim();
    const description = descInput.value.trim();

    // Check if empty
    if (name === '' || description === '') {
        errorMessage.style.display = 'block';
        setTimeout(function() {
            errorMessage.style.display = 'none';
        }, 3000);
        return false;
    }

    errorMessage.style.display = 'none';
    return true;
}

function saveCategory(id) {
    // Validate inputs
    id = parseInt(id);
    if (!validateCategory(id)) {
        return;
    }

    const nameInput = document.getElementById(`nameInput${id}`);
    const descInput = document.getElementById(`descriptionInput${id}`);

    // Get trimmed values
    const name = nameInput.value.trim();
    const description = descInput.value.trim();
    updateUI(id, name, description);

    let updateUrl = urlForUpdate(id);

    // Send AJAX request to save data
    $.ajax({
        url: updateUrl,
        type: 'PUT',
        data: {
            name: name,
            description: description
        },
        success: function(response) {
            if (response.success) {


                // Close the edit form
                const editForm = document.getElementById(`editForm${id}`);
                const description = document.getElementById(`description${id}`);
                const seeMoreBtn = document.getElementById(`seeMoreBtn${id}`);
                const categoryNameElement = document.getElementById(`categoryName${id}`);
                const editNameForm = document.getElementById(`editNameForm${id}`);

                // Close edit mode
                editForm.classList.remove('active');
                description.style.display = 'block';
                if (description.scrollHeight > description.clientHeight) {
                    seeMoreBtn.style.display = 'block';
                }

                // Disable category name editing
                categoryNameElement.style.display = 'block';
                editNameForm.style.display = 'none';
            } else {
                alert('Failed to save changes. Please try again.');
            }
        },
        error: function(xhr) {
            console.error('Error saving category:', xhr.responseText);

            // Show error message based on server response if available
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errorMessage = document.getElementById(`error${id}`);
                errorMessage.textContent = Object.values(xhr.responseJSON.errors)[0];
                errorMessage.style.display = 'block';
            } else {
                alert('Failed to save changes. Please try again.');
            }
        }
    });
}

function updateUI(id, name, description) {
    id = parseInt(id);
    const categoryNameElement = document.getElementById(`categoryName${id}`);
    const descElement = document.getElementById(`description${id}`).querySelector('p');

    // Update displayed values
    categoryNameElement.textContent = name;
    descElement.textContent = description;

    // Check if we need the "See more" button after content update
    const descContainer = document.getElementById(`description${id}`);
    descContainer.classList.remove('expanded');
    setTimeout(() => checkOverflow(id), 10);
}