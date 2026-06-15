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
    <link rel="stylesheet" href="{{ asset('css/teacher/slider.css') }}">
</head>

<body>

    <!-- Sidebar -->
    <button class="btn btn-primary d-md-none position-fixed rounded-3 menu-toggle-btn" id="menuBtn"
        aria-label="Toggle navigation">
        <i class='bx bx-menu fs-4'></i>
    </button>

    {{-- sliderbar  --}}
    <aside class="sidebar bg-surface border-end p-4 d-flex flex-column position-fixed top-0 vh-100" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-header mb-4">
            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('image/logo.png') }}" width="35" height="35" alt="Logo">
                <span class="brand-text fw-bold text-dark fs-5">Quiz<span class="text-coral">Master</span></span>
            </a>
        </div>

        {{-- Links  --}}
        <ul class="nav flex-column gap-1 list-unstyled m-0 p-0 nav-list">

            <li class="nav-item">
                <a href="{{ url('teacher') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class='bx bxs-dashboard fs-5'></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('quizzes') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                    <i class='bx bx-edit-alt fs-5'></i>
                    <span>Quizzes</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('resultpage') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 {{ request()->routeIs('resultpage') ? 'active' : '' }}">
                    <i class='bx bx-bar-chart-alt-2 fs-5'></i>
                    <span>Results</span>
                </a>
            </li>
        </ul>

        {{-- Logout  --}}
        <form id="logout-form" action="logout" method="POST" style="display: none;">
            @csrf
        </form>

        <button type="button"
            class="btn btn-danger d-flex align-items-center gap-2 rounded-3 mt-auto w-100 justify-content-center justify-content-md-start p-2"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='bx bx-log-out fs-5'></i>
            <span class="logout-text">Logout</span>
        </button>

    </aside>

    {{-- Script --}}
    <script>
        const sidebar = document.getElementById("sidebar");
        const menuBtn = document.getElementById("menuBtn");
        const menuIcon = menuBtn.querySelector("i");

        menuBtn.addEventListener("click", () => {

            sidebar.classList.toggle("show");

            if (sidebar.classList.contains("show")) {
                menuIcon.classList.replace("bx-menu", "bx-x");
            } else {
                menuIcon.classList.replace("bx-x", "bx-menu");
            }

        });
    </script>

</body>

</html>
