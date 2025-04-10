@extends('layout.app')

@section('title', 'Member Dashboard')

@push('styles')
<style>
    .member-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        color: white;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .member-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1));
        transform: rotate(45deg);
    }

    .member-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .card-icon {
        font-size: 2rem;
        opacity: 0.9;
        transition: transform 0.3s ease;
    }

    .member-card:hover .card-icon {
        transform: scale(1.1);
    }

    .membership-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.2);
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
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
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Welcome Back, {{ Auth::user()->name }}</h2>
    
    <div class="row g-4">
        <!-- Account Status Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="status" data-route="">
            <div class="member-card bg-success p-4 shadow">
                <div class="membership-badge">
                    Base
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Active</h5>
                        <p class="mb-0">Account Status</p>
                    </div>
                    <i class="bi bi-shield-check card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Remaining Limits Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="limits" data-route="">
            <div class="member-card bg-info p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">25</h5>
                        <p class="mb-0">Remaining Books</p>
                    </div>
                    <i class="bi bi-journal-check card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Active Libraries Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="libraries" data-route="">
            <div class="member-card bg-primary p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">28</h5>
                        <p class="mb-0">Active Libraries</p>
                    </div>
                    <i class="bi bi-building card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Outstanding Fines Card -->
        <div class="col-12 col-md-6 col-xl-3" data-type="fines" data-route="">
            <div class="member-card bg-danger p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">₹2000.50</h5>
                        <p class="mb-0">Outstanding Fines</p>
                    </div>
                    <i class="bi bi-cash-coin card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Content Section -->
    <div id="dynamic-content" class="dynamic-content"></div>

   
@endsection

@push('scripts')
<!-- <script>
document.querySelectorAll('.member-card').forEach(card => {
    card.addEventListener('click', async function() {
        // Remove active class from all cards
        document.querySelectorAll('.member-card').forEach(c => {
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
            
            // Generate content based on data type
            let contentHtml = '';
            switch(data.type) {
                case 'fines':
                    contentHtml = `
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-cash-stack me-2"></i>Fine Details</h5>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.items.map(item => `
                                                <tr>
                                                    <td>${item.date}</td>
                                                    <td>${item.description}</td>
                                                    <td>$${item.amount}</td>
                                                    <td><span class="badge ${item.status === 'Paid' ? 'bg-success' : 'bg-danger'}">${item.status}</span></td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'libraries':
                    contentHtml = `
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-building me-2"></i>Active Libraries</h5>
                                <div class="row">
                                    ${data.items.map(library => `
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title">${library.name}</h6>
                                                    <p class="text-muted small mb-0">${library.location}</p>
                                                    <p class="text-muted small">Access until: ${library.access_expiry}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                // Add other cases for different data types
            }

            dynamicContent.innerHTML = contentHtml;
            
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