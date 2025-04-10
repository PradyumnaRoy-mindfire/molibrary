@extends('layout.app')

@section('title', 'Superadmin Dashboard')

@push('styles')
<style>
    .stats-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        color: white;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1));
        transform: rotate(45deg);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .card-icon {
        font-size: 2.5rem;
        opacity: 0.8;
        transition: transform 0.3s ease;
    }

    .stats-card:hover .card-icon {
        transform: scale(1.1);
    }

    .more-options {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .stats-card:hover .more-options {
        opacity: 1;
    }

    .dynamic-content {
        transition: all 0.3s ease;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
    }

    .dynamic-content.active {
        max-height: 1000px;
        opacity: 1;
        margin-top: 20px;
    }

    .data-table {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Superadmin Dashboard Overview</h2>
    
    <div class="row g-4">
        <!-- Total Users-->
        <div class="col-12 col-md-6 col-xl-3" data-type="users" data-route="">
            <div class="stats-card bg-primary p-4 shadow">
                <button class="btn btn-link more-options text-white p-0">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">25</h5>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <i class="bi bi-people-fill card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Active Member -->
        <div class="col-12 col-md-6 col-xl-3" data-type="members" data-route="">
            <div class="stats-card bg-success p-4 shadow">
                <button class="btn btn-link more-options text-white p-0">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">20</h5>
                        <p class="mb-0">Active Members</p>
                    </div>
                    <i class="bi bi-person-check-fill card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Total Libraries -->
        <div class="col-12 col-md-6 col-xl-3" data-type="libraries" data-route="">
            <div class="stats-card bg-info p-4 shadow">
                <button class="btn btn-link more-options text-white p-0">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">15</h5>
                        <p class="mb-0">Total Libraries</p>
                    </div>
                    <i class="bi bi-building-fill card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Total Books here -->
        <div class="col-12 col-md-6 col-xl-3" data-type="books" data-route="">
            <div class="stats-card bg-warning p-4 shadow">
                <button class="btn btn-link more-options text-white p-0">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">10</h5>
                        <p class="mb-0">Total Books</p>
                    </div>
                    <i class="bi bi-book-fill card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamically Content will be added here-->
    <div id="dynamic-content" class="dynamic-content"></div>
</div>
@endsection

@push('scripts')
<!-- <script>
document.querySelectorAll('.stats-card').forEach(card => {
    card.addEventListener('click', async function() {
        // Remove active class from all cards
        document.querySelectorAll('.stats-card').forEach(c => {
            c.classList.remove('active');
        });
        
        // Add active class to clicked card
        this.classList.add('active');
        
        const route = this.parentElement.dataset.route;
        const dynamicContent = document.getElementById('dynamic-content');
        
        // Show loading state
        dynamicContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        dynamicContent.classList.add('active');

        try {
            const response = await fetch(route);
            const data = await response.json();
            
            // Generate table HTML
            const tableHtml = `
                <div class="data-table p-4">
                    <h5 class="mb-3">${data.title}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    ${data.headers.map(header => `<th>${header}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${data.rows.map(row => `
                                    <tr>
                                        ${row.map(cell => `<td>${cell}</td>`).join('')}
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            dynamicContent.innerHTML = tableHtml;
            
        } catch (error) {
            dynamicContent.innerHTML = `
                <div class="alert alert-danger">
                    Error loading data: ${error.message}
                </div>
            `;
            console.error('Error:', error);
        }
    });
});
</script> -->
@endpush