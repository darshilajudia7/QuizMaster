<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Logo --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('image/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Lora:wght@600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    {{-- Link CSS file  --}}
    <link rel="stylesheet" href="{{ asset('css/student/navbar.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-4">

            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('image/logo.png') }}" width="35" height="35" alt="Logo">
                <span class="brand-text fw-bold text-dark fs-5">Quiz<span class="text-coral">Master</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMenu">
                <i class='bx bx-menu fs-2'></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') || request()->is('student') ? 'active' : '' }}"
                            href="{{ url('/student') }}">
                            <i class='bx bxs-dashboard'></i>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('quiz*') ? 'active' : '' }}" href="{{ url('quiz') }}">
                            <i class='bx bx-book-content'></i>
                            Quizzes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('history*') ? 'active' : '' }}" href="{{ url('history') }}">
                            <i class='bx bx-history'></i>
                            History
                        </a>
                    </li>

                </ul>

                {{-- Logout  --}}
                <form id="logout-form" action="logout" method="POST" style="display: none;">
                    @csrf
                </form>

                <button class="btn btn-logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out me-1'></i>
                    Logout
                </button>

            </div>

        </div>
    </nav>
</body>

</html>
