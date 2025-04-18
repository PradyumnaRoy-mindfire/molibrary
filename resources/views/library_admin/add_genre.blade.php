@extends('layout.app')

@section('title', 'Add Genre')

@push('styles')
<link rel="stylesheet" href="{{ url('css/library_admin/add_genre.css') }}">
@endpush

@section('content')
<div class="gradient-background py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="genre-form p-4 p-md-5">
                    <h2 class="text-center mb-4 header-text">Add New Genre</h2>

                    <form id="genreForm" method="POST" action="{{ route('store.genre') }}">
                        @csrf
                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="genreName" name="name" placeholder="Genre Name"
                                    value="{{ old('name') }}">
                                <label for="genreName">Genre Name</label>
                            </div>
                            @error('name')
                            <span class="error-message d-block" id="nameError">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-floating">
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="genreDescription" name="description" placeholder="Description"
                                    style="height: 120px">{{ old('description') }}</textarea>
                                <label for="genreDescription">Description</label>
                            </div>
                            @error('description')
                            <span class="error-message d-block" id="descriptionError">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-gradient py-3">Add Genre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#genreName').on('input', function() {
            $('#nameError').empty();
            $('#genreName').removeClass("is-invalid");
        });

        $('#genreDescription').on('input', function() {
            $('#descriptionError').empty();
            $('#genreDescription').removeClass("is-invalid");
        });
    });
</script>
@endpush