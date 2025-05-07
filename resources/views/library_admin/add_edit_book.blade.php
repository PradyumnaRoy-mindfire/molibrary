@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ isset($book) ? 'Edit Book' : 'Add New Book' }}</h4>
                </div>
                <div class="card-body">
                    @if(isset($book))
                    <form action="{{ route('edit.book', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @method('put')
                    @else
                    <form action="{{ route('book.store',$library->id) }}" method="POST" enctype="multipart/form-data">
                    @endif
                            @csrf

                            <div class="row g-3">
                                <!-- First Row -->
                                <div class="col-md-6">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                        value="{{ old('title', isset($book) ? $book->title : '') }}">
                                    @error('title')
                                    <span class="text-danger titleError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="author_id" class="form-label">Author</label>
                                    <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id">
                                        <option selected disabled>Select Author</option>
                                        @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ (isset($book) && $book->author_id == $author->id) ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                        @endforeach
                                        <option value="new-author">+ Add New Author</option>
                                    </select>
                                    <div id="new-author-input" class="mt-2 d-none">
                                        <input type="text" class="form-control" name="new_author_name" placeholder="Enter new author name">
                                    </div>
                                    @if($errors->has('author_id') || $errors->has('new_author_name'))
                                    <span class="text-danger authorError">
                                        {{ $errors->first('author_id') ?: $errors->first('new_author_name') }}
                                    </span>
                                    @endif
                                </div>

                                <!-- Second Row -->
                                <div class="col-md-3">
                                    <label for="isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn"
                                        value="{{ old('isbn', isset($book) ? $book->isbn : '') }}">
                                    @error('isbn')
                                    <span class="text-danger isbnError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="edition" class="form-label">Edition No</label>
                                    <input type="number" class="form-control @error('edition') is-invalid @enderror" id="edition" name="edition"
                                        value="{{ old('edition', isset($book) ? $book->edition : '') }}">
                                    @error('edition')
                                    <span class="text-danger editionError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option selected disabled>Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (isset($book) && $book->category_id == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <span class="text-danger categoryError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="published_year" class="form-label">Published Year</label>
                                    <input type="number" class="form-control @error('published_year') is-invalid @enderror" id="published_year"
                                        name="published_year" min="1000" max="{{ date('Y') }}"
                                        value="{{ old('published_year', isset($book) ? $book->published_year : '') }}">
                                    @error('published_year')
                                    <span class="text-danger publishError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Third Row -->
                                <div class="col-md-3">
                                    <label for="total_copies" class="form-label">Total Copies <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_copies') is-invalid @enderror" id="total_copies"
                                        name="total_copies" min="0"
                                        value="{{ old('total_copies', isset($book) ? $book->total_copies : '') }}">
                                    @error('total_copies')
                                    <span class="text-danger totalCopyError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Has E-Book</label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="has_ebook" id="has_ebook_yes" value="1"
                                                {{ (isset($book) && $book->has_ebook == 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_ebook_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="has_ebook" id="has_ebook_no" value="0"
                                                {{ (isset($book) ? ($book->has_ebook == 0 ? 'checked' : '') : 'checked') }}>
                                            <label class="form-check-label" for="has_ebook_no">No</label>
                                        </div>
                                    </div>
                                    @error('has_ebook')
                                    <span class="text-danger hasEbookError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Has Paperback</label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="has_paperbook" id="has_paperback_yes" value="1"
                                                {{ (isset($book) ? ($book->has_paperbook == 1 ? 'checked' : '') : 'checked') }}>
                                            <label class="form-check-label" for="has_paperback_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="has_paperbook" id="has_paperback_no" value="0"
                                                {{ (isset($book) && $book->has_paperbook == 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_paperback_no">No</label>
                                        </div>
                                    </div>
                                    @error('has_paperbook')
                                    <span class="text-danger hasPaperBookError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="image" class="form-label">Book Cover <span class="text-danger">{{ isset($book) ? '' : '*' }}</span></label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" {{ isset($book) ? '' : '' }}>
                                    @if(isset($book) && $book->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$book->image) }}" alt="{{ $book->title }}" class="img-thumbnail" style="height: 100px;">
                                        <input type="hidden" name="existing_image" value="{{ $book->image }}">
                                    </div>
                                    @endif
                                    @error('image')
                                    <span class="text-danger imageError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Fourth Row -->
                                <div class="col-md-6" id="ebook-path-container">
                                    <label for="ebook_path" class="form-label">E-Book File</label>
                                    <input type="file" class="form-control @error('ebook_path') is-invalid @enderror" id="ebook_path" name="ebook_path" accept=".pdf,.epub,.mobi">
                                    @if(isset($book) && $book->ebook_path)
                                    <div class="mt-2">
                                        <p class="text-success"><i class="bi bi-file-earmark-pdf"></i> Current file: {{ basename($book->ebook_path) }}</p>
                                        <input type="hidden" name="existing_ebook" value="{{ $book->ebook_path }}">
                                    </div>
                                    @endif
                                    @error('ebook_path')
                                    <span class="text-danger ebookError">{{ $message }}</span>
                                    @enderror
                                </div>

                                

                                <!-- Fifth Row -->
                                <div class="col-12">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', isset($book) ? $book->description : '') }}</textarea>
                                    @error('description')
                                    <span class="text-danger descriptionError">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-end mt-4">
                                    <a class="btn btn-secondary me-2" href="{{ route('manage.books') }}">Back</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-{{ isset($book) ? 'save' : 'plus-circle' }} me-1"></i>
                                        {{ isset($book) ? 'Update Book' : 'Add Book' }}
                                    </button>
                                </div>
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
<script src=" {{ url('js/library_admin/add_edit_book.js') }}"></script>
@endpush