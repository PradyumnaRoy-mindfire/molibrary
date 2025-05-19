<div class="card shadow-lg border-0 rounded-4 p-5 mt-4 mx-auto" style="width: 50%; height: 500px; font-size: 1.25rem;">
    <div class="card-body d-flex flex-column justify-content-between h-100">
        <div>
            <h3 class="fw-bold text-primary mb-4">
                <i class="bi bi-person-badge-fill me-2"></i>Membership Details
            </h3>
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                    <strong>Plan:</strong> <span class="text-dark">{{ucfirst($membership->plan->type)}}</span>
                </li>
                <li class="mb-3">
                    <strong>Status:</strong> <span class="badge bg-success fs-6">Active</span>
                </li>
                <li class="mb-3">
                    <strong>Start Date:</strong> <span class="text-dark">{{$membership->start_date}}</span>
                </li>
                <li class="mb-3">
                    <strong>Expiry Date:</strong> <span class="text-dark">{{ $membership->end_date}}</span>
                </li>
                <li>
                    <strong>Renewal Available:</strong> <span class="text-dark">Yes</span>
                </li>
            </ul>
        </div>
        <div class="text-end">
            <a href="{{ route('memberships') }}" class="btn btn-primary px-4 py-2">
                <i class="bi bi-arrow-right-circle me-1"></i> Upgrade Memberships
            </a>
        </div>
    </div>
</div>
