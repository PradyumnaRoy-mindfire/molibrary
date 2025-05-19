<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MoLibrary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ url('css/navbar.css') }}">

</head>

<body>

    <!-- Top Navbar -->
    <nav class="navbar horizontal-nav p-3" style="position: sticky;">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn text-white me-2" id="sidebarToggle">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
                <div class="logo-bar d-flex align-items-center">
                    <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="rounded">
                    <span class="logo-title">MoLibrary</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">

                <div class="position-relative">
                    <button class="btn text-white position-relative pe-0" id="notificationBtn">
                        <i class="bi bi-bell-fill" style="font-size: 1.5rem;"></i>
                        @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp
                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>
                    <div id="notificationBox" class="card shadow-sm position-absolute end-0 mt-2" style="width: 300px; display: none; z-index: 1050;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Notifications</span>
                            @if($unreadCount > 0)
                            <button type="submit" class="btn btn-sm btn-outline-secondary" id="markAllAsReadBtn">Mark all read</button>
                            @else 
                            <!-- this button is for -->
                            <button type="submit" class="btn btn-sm btn-outline-secondary" id="markAllAsReadBtn" style="display: none;">Mark all read</button>
                            @endif
                        </div>
                        <ul class="list-group list-group-flush" id="notificationList">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                            <li class="list-group-item notification-item {{ $notification->read_at ? '' : 'unread' }}"
                                data-notification-id="{{ $notification->id }}">
                                <span class="notification-title">{{ $notification->data['title'] }}</span>
                                <span class="notification-time fs-6 mt-1 float-start ">{{ $notification->created_at->diffForHumans() }}</span>
                                <button class="mt-1 badge-customm float-end mark-as-read-btn"
                                    data-mark-as-read="{{ $notification->id }}"
                                   >Mark as Read</button>
                            </li>
                            @empty
                            <li class="list-group-item text-center text-muted">No notifications</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Modal for notification details -->
                <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body" id="notificationModalBody"></div>

                            <div class="modal-footer" id="notificationModalFooter">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="text-white fw-bold">Welcome, {{ Auth::user()->name }}</div>

                <div class="dropdown">
                    <button class="btn text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <ul class="nav flex-column px-3 pt-4" id="sideNavLinks">
            <!-- Super addmin nav item -->
            <li class="nav-item mb-2">
                <a class="nav-link " href="{{ route('dashboard') }}"><i class="bi bi-house-door me-2 fs-4 fw-bold "></i><span>Dashboard</span></a>
            </li>
            @if(Auth::user()->role === 'super_admin')
            <!-- <li class="nav-item">
                <a class="nav-link" href="{{-- route('add.library') --}}"><i class="bi bi-building-add me-2 fs-4 fw-bold"></i><span>Add Library</span></a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.library') }}"><i class="bi bi-building-add me-2 fs-4 fw-bold"></i><span>Manage Library</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('library.admins') }}"><i class="bi bi-person-badge me-2 fs-4 fw-bold"></i><span>Library Admins</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('all.members') }}"><i class="bi bi-people me-2 fs-4 fw-bold"></i><span>All Members</span></a>
            </li>

            <!-- library admin nav item -->
            @elseif(Auth::user()->role === 'library_admin')


            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.books') }}"><i class="bi bi-book-half me-2 fs-4 fw-bold"></i><span>Book Management</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.genres') }}"><i class="bi bi-tags me-2 fs-4 fw-bold"></i><span>Manage Genres</span> </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('approve.librarians') }}"><i class="bi bi-person-check me-2 fs-4 fw-bold"></i><span>Librarians</span></a>
            </li>

            <!-- Librarian nav -->
            @elseif(Auth::user()->role === 'librarian')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('book.management') }}"><i class="bi bi-book me-2 fs-4 fw-bold"></i><span>Book Management</span></a>
            </li>

            @elseif(Auth::user()->role === 'member')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('browse.books') }}"><i class="bi bi-search me-3 fs-3 fw-bold"></i><span>Browse Books</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('borrowing.history') }}"><i class="bi bi-clock-history me-3 fs-3 fw-bold"></i><span>Borrowing History</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('show.reserve.books') }}"><i class="bi bi-book-half me-3 fs-3 fw-bold"></i></i><span>Reserved Books</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('memberships') }}"><i class="bi bi-people me-3 fs-3 fw-bold"></i><span>Membership</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('e-books') }}"><i class="bi bi-file-earmark-pdf me-3 fs-3 fw-bold"></i><span>e-Books</span></a>
            </li>
            @endif



            <button class="nav-link text-danger w-100 text-start logout">
                <i class="bi bi-box-arrow-left me-2 fs-5"></i><span>Logout</span>
            </button>
        </ul>
    </div>



    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        let logoutUrl = "{{ route('logout') }}";

        //for realtime notification
        Pusher.logToConsole = false;
        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}"
        });
    </script>

    <script src="{{ url('js/navbar.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JavaScript Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if(session('logged_in_success'))
    <script>
        showLoginToast();
    </script>
    @endif

    @if(auth()->user()->role === 'member')
    <script>
        bookAdded();
    </script>
    @endif

    @if(auth()->user()->role === 'librarian')
    <script>
        newBorrowRequest();
        newReturnRequest();
    </script>
    @endif

    @if(auth()->user()->role === 'library_admin')
    <script>
        newLibrarianRegistered();
    </script>
    @endif

</body>

</html>