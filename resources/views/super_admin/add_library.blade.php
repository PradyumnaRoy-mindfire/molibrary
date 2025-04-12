@extends('layout.app')

@section('title','Add Library')

@push('styles')
    <link rel="stylesheet" href="{{ url('css/add_library.css') }}">
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-center mt-5">
    <div class="container py-4 mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="library-form-container p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-building library-icon mb-3"></i>
                        <h2 class="form-header">Add New Library</h2>
                    </div>
                    @if(session('libraryStored'))
                        <x-alert type="success">
                            {{ session('libraryStored') }}
                        </x-alert>
                    @endif
                    <form method="POST" id="libraryForm" action="{{ route('store.library') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Library Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Enter library name" value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label">Location</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" id="location" name="location"
                                       placeholder="Enter library location" value="{{ old('location') }}">
                            </div>
                            @error('location')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Select status</option>
                                <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center btn-actions mt-4 gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="bi bi-save me-2"></i>Add Library
                            </button>

                            <a href="{{ route('manage.library') }}" class="btn-view">
                                <i class="bi bi-eye me-2"></i>View Libraries
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        // Remove error span when user starts typing or selecting
        document.querySelectorAll('#libraryForm input, #libraryForm select').forEach(el => {
            el.addEventListener('input', () => {
                let err = el.closest('.mb-4').querySelector('.form-error');
                if (err) err.remove();
            });
        });
    </script>
@endpush
