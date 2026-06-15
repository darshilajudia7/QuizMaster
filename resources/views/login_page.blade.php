<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizMaster - Sign In</title>

    {{-- Logo --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('image/logo.png') }}">

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Lora:wght@600;700&display=swap"
        rel="stylesheet">

    {{-- Boxicons icons link  --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    {{-- Bootstrap CSS and JS links --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Alpha Js  --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- sweetalert JS  --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Link CSS file  --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

    <section class="auth-wrapper d-flex align-items-center justify-content-center p-3">

        <div class="auth-card container-fluid p-0">
            <div class="row g-0">

                {{-- Left side - Brand sidebar --}}
                <div
                    class="col-lg-5 brand-sidebar d-none d-lg-flex flex-column justify-content-between p-5 text-white position-relative">
                    <div class="sidebar-dots"></div>

                    <a href="#" class="logo-area">
                        <img src="{{ asset('image/logo.png') }}" width="30">
                        <span class="logo-title">Quiz<span>Master</span></span>
                    </a>

                    <div class="brand-middle">
                        <h3 class="brand-heading">Welcome Back</h3>
                        <p class="brand-text">Login and continue your dashboard work</p>
                    </div>
                </div>

                <div class="col-lg-7 form-container d-flex align-items-center">

                    <div class="w-100">

                        {{-- Header --}}
                        <div class="view-header mb-4">
                            <h2 class="view-title">Sign In</h2>
                            <p class="view-subtitle">Access your account</p>
                        </div>

                        {{-- Google OAuth button --}}
                        <button class="btn btn-oauth w-100" onclick="window.location.href='{{ route('auth.google') }}'">
                            <i class='bx bxl-google'></i> Continue with Google
                        </button>

                        <div class="section-divider">Or</div>

                        {{-- Form --}}
                        <form action="login" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Enter your email" required>
                                </div>
                            </div>


                            <div class="mb-4" x-data="{ show: false }">
                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bx-lock'></i></span>
                                        <input :type="show ? 'text' : 'password'" name="password" class="form-control"
                                            placeholder="Enter Password" required>

                                        <button class="input-group-text" type="button" @click="show = !show">
                                            <i class="bx" :class="show ? 'bx-show' : 'bx-hide'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mb-3">
                                <a href="{{ url('/forgot_password') }}" class="small text-decoration-none  text-danger">
                                    Forgot password?
                                </a>
                            </div>

                            <button class="btn btn-submit w-100">Login</button>

                        </form>

                        <p class="text-center mt-4 small">
                            New user? <a href="{{ url('/register') }}">Create account</a>
                        </p>

                    </div>

                </div>

            </div>
        </div>

    </section>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            toggleButton.addEventListener('click', function() {
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the Boxicons class between bx-hide and bx-show
                if (type === 'text') {
                    eyeIcon.classList.remove('bx-hide');
                    eyeIcon.classList.add('bx-show');
                } else {
                    eyeIcon.classList.remove('bx-show');
                    eyeIcon.classList.add('bx-hide');
                }
            });
        });
    </script>

    {{-- Pop up --}}
    <script>
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    </script>

    <script src="{{ asset('js/pop-up.js') }}"></script>

</body>

</html>
