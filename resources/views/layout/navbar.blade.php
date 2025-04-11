<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MoLibrary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .horizontal-nav {
            background-color: #4fc3f7;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1001;
        }

        .horizontal-nav .logo-title {
            font-weight: bold;
            font-size: 1.3rem;
            margin-left: 10px;
            color: white;
        }

        .sidebar {
            background-color: #ffffff;
            border-right: 2px solid #e0e0e0;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 70px;
            transition: width 0.3s;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 87px;
        }

        .sidebar .nav-link {
            font-weight: bold;
            font-size: 1.05rem;
            color: #2c3e50;
            border-radius: 8px;
            margin: 4px 0;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar .nav-link:hover {
            background-color: #f1f1f1;
            color: #007bff;
        }

        .sidebar .nav-link.active {
            background-color: #007bff !important;
            color: white !important;
        }

        .main-content {
            margin-left: 250px;
            padding: 80px 20px 20px;
            transition: margin-left 0.3s;
        }

        .main-content.full {
            margin-left: 70px;
        }

        .logout-btn {
            margin-top: auto;
            width: 100%;
        }

        .sidebar ul {
            padding-left: 0;
            margin-top: 10px;
        }

        .logo-bar {
            display: flex;
            align-items: center;
        }

        .logo-bar img {
            width: 35px;
            height: 35px;
        }

        .sidebar.collapsed .logout-text {
            display: none;
        }

        .sidebar .nav-link {
            font-weight: bold;
            font-size: 1.05rem;
            color: #2c3e50;
            border-radius: 8px;
            margin: 4px 0;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .sidebar.collapsed .nav-link.active {
            width: 80%;
            justify-content: center;
            text-align: center;
            padding-right: 0.75rem;
        }

        .sidebar .nav-link i {
            font-size: 1.5rem !important;
        }
    </style>
</head>

<body>

    <!-- Top Navbar -->
    <nav class="navbar horizontal-nav p-3">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn text-white me-2" id="sidebarToggle">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
                <div class="logo-bar d-flex align-items-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2892/2892206.png" alt="Logo">
                    <span class="logo-title">MoLibrary</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <button class="btn text-white position-relative pe-0" id="notificationBtn">
                        <i class="bi bi-bell-fill" style="font-size: 1.5rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"> 3 </span>
                    </button>
                    <div id="notificationBox" class="card shadow-sm position-absolute end-0 mt-2" style="width: 300px; display: none; z-index: 1050;">
                        <div class="card-header fw-bold">Notifications</div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">New book added: "AI in 2025"</li>
                            <li class="list-group-item">Your membership is expiring soon</li>
                            <li class="list-group-item">Reminder: Return "Clean Code"</li>
                        </ul>
                        <div class="card-footer text-center">
                            <a href="#" class="text-primary">View all notifications</a>
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
            @if(Auth::user()->role === 'super_admin')
            <li class="nav-item mb-2">
                <a class="nav-link " href="{{ route('superadmin-dashboard') }}"><i class="bi bi-house-door me-2 fs-4 fw-bold "></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('add.library') }}">
                    <i class="bi bi-building-add me-2 fs-4 fw-bold"></i>
                    <span>Add Library</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.library') }}">
                    <i class="bi bi-gear-wide-connected me-2 fs-4 fw-bold"></i>
                    <span>Manage Library</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('restricted.libraries') }}">
                    <i class="bi bi-shield-lock me-2 fs-4 fw-bold"></i>
                    <span>Restricted Libraries</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('library.admins') }}">
                    <i class="bi bi-person-badge me-2 fs-4 fw-bold"></i>
                    <span>Library Admins</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('all.members') }}">
                    <i class="bi bi-people me-2 fs-4 fw-bold"></i>
                    <span>All Members</span>
                </a>
            </li>

            <!-- library admin nav item -->
            @elseif(Auth::user()->role === 'library_admin')
            <li class="nav-item mb-2">
                <a class="nav-link " href="{{ route('superadmin-dashboard') }}"><i class="bi bi-house-door me-2 fs-4 fw-bold "></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bi bi-building me-2 fs-4 fw-bold"></i><span>Library Management</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bi bi-file-bar-graph me-2 fs-4 fw-bold"></i><span>Reports</span></a>
            </li>

            <!-- Librarian nav -->
            @elseif(Auth::user()->role === 'librarian')
            <li class="nav-item mb-2">
                <a class="nav-link " href="{{ route('superadmin-dashboard') }}"><i class="bi bi-house-door me-2 fs-4 fw-bold "></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bi bi-book me-2 fs-4 fw-bold"></i><span>Book Management</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bi bi-person-lines-fill me-2 fs-4 fw-bold"></i><span>Member Manage</span></a>
            </li>
            @elseif(Auth::user()->role === 'member')
            <li class="nav-item mb-2">
                <a class="nav-link " href="{{ route('member-dashboard') }}"><i class="bi bi-house-door me-2 fs-4 fw-bold "></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('browse-books') }}"><i class="bi bi-search me-3 fs-3 fw-bold"></i><span>Browse Books</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('borrowing-history') }}"><i class="bi bi-clock-history me-3 fs-3 fw-bold"></i><span>Borrowing History</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reserved-books') }}"><i class="bi bi-book-half me-3 fs-3 fw-bold"></i></i><span>Reserved Books</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('books') }}"><i class="bi bi-file-earmark-pdf me-3 fs-3 fw-bold"></i><span>e-Books</span></a>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ route('settings') }}"><i class="bi bi-gear me-2 fs-4 fw-bold"></i><span>Settings</span></a>
            </li>

            <form method="get" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-danger w-100 text-start">
                    <i class="bi bi-box-arrow-left me-2 fs-5"></i><span>Logout</span>
                </button>
            </form>
        </ul>
    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('js/navbar.js') }}"></script>


</body>

</html>