@extends('layout.app')

@push('styles')
<title>Read Ebbok</title>
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="{{ url('css/member/read_ebook.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div id="flipbook-container">

    <div class="nav-btn nav-prev">
        <i class="fas fa-chevron-left"></i>
    </div>
    
    <div class="nav-btn nav-next">
        <i class="fas fa-chevron-right"></i>
    </div>
    
    <!-- Loading indicator -->
    <div id="loading">
        <div class="loading-spinner"></div>
        <p>Loading your book...</p>
    </div>
    
    <!-- The flipbook itself -->
    <div id="flipbook" ></div>
    
    <div id="bottom-controls">
        <a href=" {{ route('e-books') }}" style="text-decoration: none;"><i class="fas fa-arrow-left me-2"></i>Back</a>
        <span id="page-display">
            Page <span id="page-num">1</span> / <span id="total-pages">0</span>
        </span>
        
        <button class="control-btn" onclick="toggleFullscreen()">
            <i id="fullscreen-icon" class="fas fa-expand"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<!-- Turn.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/3/turn.min.js"></script>
<!-- PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

<script>
    const url = "{{ asset('storage/'.$book->ebook_path) }}";
    const turn_page = '{{ asset("/storage/sounds/turn-page.mp3") }}';
    const progress_url = "{{ route('e-book.reading.progress', $book->id) }}";
    const csrf = "{{ csrf_token() }}";
    let startPage = "{{ $startPage }}";
    
</script>

<script src="{{ url('js/member/read_ebook.js') }}"></script>
@endpush