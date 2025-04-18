<!DOCTYPE html>
<html lang="en">
@extends('layout.app')

@section('title', 'Manage Genres')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ url('css/library_admin/manage_genres.css') }}">

@endpush
@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h3 class="mb-3 mb-md-0 text-white"> Book Categories</h3>
        <a href="{{ route('add.genre') }}" class="btn" style="background-color:rgb(116, 184, 8);">
            <i class="bi bi-plus-circle me-1"></i> Add New Genre
        </a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($categories as $index => $category)
        @php
        $catId = $category['category_id'];
        @endphp
        <div class="col">
            <div class="category-card h-100" data-category-id="{{ $category['category_id'] }}">
                <div class="category-header card-color-{{ ($index % 6) + 1 }}">
                    <div class="header-content">
                        <h3 id="categoryName{{ $catId }}" class="mb-0 fs-5">{{ $category['name'] }}</h3>
                        <div id="editNameForm{{ $catId }}" class="edit-name-form">
                            <input type="text" id="nameInput{{ $catId }}" class="category-name-input" value="{{ $category['name'] }}">
                        </div>
                    </div>
                    <button class="edit-btn" onclick="toggleEdit('{{ $catId }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                        </svg>
                    </button>
                </div>
                <div class="category-body">
                    <!-- Edit Form -->
                    <div id="editForm{{ $catId }}" class="edit-form">
                        <textarea class="form-control" id="descriptionInput{{ $catId }}" rows="4">{{ $category['description'] }}</textarea>
                        <div class="error-message" id="error{{ $catId }}">Name and description cannot be empty</div>
                        <div class="edit-actions">
                            <button class="btn btn-sm btn-cancel bg-danger fw-bold text-white" onclick="cancelEdit('{{ $catId }}' )">Cancel</button>
                            <button class="btn btn-sm btn-save bg-success fw-bold text-white" onclick="saveCategory('{{ $catId }}')">Save</button>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div id="description{{ $catId }}" class="description-container">
                        <p class="mb-0 text-secondary">{{ $category['description'] }}</p>
                    </div>
                    <button id="seeMoreBtn{{ $catId }}" class="see-more-btn" onclick="toggleDescription( '{{ $catId }}' )">See more</button>

                    <!-- Stats Section -->
                    <div class="stats-container">
                        <div class="stat-box available-box">
                            <div class="stat-value available-value">{{ $category['available_books'] }}</div>
                            <div class="stat-label">Available Books</div>
                        </div>
                        <div class="stat-box borrowed-box">
                            <div class="stat-value borrowed-value">{{ $category['borrowed_books'] }}</div>
                            <div class="stat-label">Borrowed Books</div>
                        </div>
                    </div>

                    <!-- Availability Bar -->
                    <div class="availability-section">

                        <div class="d-flex justify-content-between align-items-center small text-secondary mb-1">
                            <span>Preference</span>
                            <span>{{ $category['preferencePercentage']  }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar"
                                style="width: {{ $category['preferencePercentage'] }}%"
                                aria-valuenow="{{ $category['preferencePercentage'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function urlForUpdate(id) {
        let updateUrl = "{{ route('update.genre', ':id') }}";
        updateUrl = updateUrl.replace(':id', id);
        return updateUrl;
    }
</script>
<script src="{{ url('js/library_admin/manage_genres.js') }}"></script>
@endpush