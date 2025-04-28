document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', function() {
        applyFilters();
    });

    document.getElementById('filterSelect').addEventListener('change', function() {
        applyFilters();
    });

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const filterValue = document.getElementById('filterSelect').value;
        
        const rows = document.querySelectorAll('#requestsTable tbody tr');
        
        rows.forEach(row => {
            const memberName = row.cells[0].textContent.toLowerCase();
            const memberEmail = row.cells[1].textContent.toLowerCase();
            const bookTitle = row.cells[2].textContent.toLowerCase();
            const isbn = row.cells[3].textContent.toLowerCase();
            const requestType = row.cells[5].textContent.toLowerCase();
            
            const matchesSearch = 
                memberName.includes(searchTerm) || 
                memberEmail.includes(searchTerm) || 
                bookTitle.includes(searchTerm) || 
                isbn.includes(searchTerm);
            
            const matchesFilter = 
                filterValue === 'all' || 
                (filterValue === 'borrow' && requestType.toLowerCase() === 'borrow') || 
                (filterValue === 'return' && requestType.toLowerCase() === 'return');
            
            if (matchesSearch && matchesFilter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    const approveButtons = document.querySelectorAll('.btn-approve');
    const rejectButtons = document.querySelectorAll('.btn-reject');
    
    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            const url = this.getAttribute('data-url');
            const row = this.closest('tr');
            const memberName = row.cells[0].textContent;
            const bookTitle = row.cells[2].textContent;
            
            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Approve Request?',
                html: `Do you want to approve the request for <strong>${bookTitle}</strong> by <strong>${memberName}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    processRequest(requestId, url, 'approve', this);
                }
            });
        });
    });
    
    rejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            const url = this.getAttribute('data-url');
            const row = this.closest('tr');
            const memberName = row.cells[0].textContent;
            const bookTitle = row.cells[2].textContent;
            
            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Reject Request?',
                html: `Do you want to reject the request for <strong>${bookTitle}</strong> by <strong>${memberName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    processRequest(requestId, url, 'reject', this);
                }
            });
        });
    });
    
    function processRequest(requestId, url, action, buttonElement) {
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ request_id: requestId, action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Showing a success message
                Swal.fire({
                    title: 'Success!',
                    text: data.message || `Request ${action}d successfully!`,
                    icon: 'success',
                    confirmButtonColor: '#10b981'
                });
                
                // Updating the status in the UI
                const actionCell = buttonElement.closest('.action-cell');
                const statusClass = action === 'approve' ? 'approved' : 'unavailable';
                const statusText = action === 'approve' ? 'Approved' : 'Rejected';
                actionCell.innerHTML = `<span class="status ${statusClass}">${statusText}</span>`;
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Something went wrong.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            console.error('Error processing request:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred. Please try again.',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        });
    }
});