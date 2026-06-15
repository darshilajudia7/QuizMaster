<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — QuizMaster</title>

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

    {{-- Link CSS file --}}
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
                        <h3 class="brand-heading">Secure Access</h3>
                        <p class="brand-text">Create a strong, unique password to complete your account recovery setup.
                        </p>
                    </div>
                </div>

                {{-- Right side - Form Container --}}
                <div class="col-lg-7 form-container d-flex align-items-center" x-data="{ showPass: false, showConfirmPass: false }">
                    <div class="w-100">

                        {{-- Header --}}
                        <div class="view-header mb-4">
                            <h2 class="view-title">Reset Password</h2>
                            <p class="view-subtitle">Set your new password below to regain full dashboard access.</p>
                        </div>

                        {{-- Form --}}
                        <form action="{{ url('/reset_password') }}" method="POST">
                            @csrf

                            <input type="hidden" name="token" value="{{ request('token') }}">

                            {{-- Email Input --}}
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Enter your email" value="{{ request('email') ?? old('email') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- New Password Input --}}
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-lock-open'></i></span>
                                    <input :type="showPass ? 'text' : 'password'" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter your password" required>
                                    <button class="input-group-text" type="button" @click="showPass = !showPass">
                                        <i class="bx" :class="showPass ? 'bx-show' : 'bx-hide'"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Confirm Password Input --}}
                            <div class="mb-4">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-lock'></i></span>
                                    <input :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Enter confirm password" required>
                                    <button class="input-group-text" type="button"
                                        @click="showConfirmPass = !showConfirmPass">
                                        <i class="bx" :class="showConfirmPass ? 'bx-show' : 'bx-hide'"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-submit w-100" type="submit">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Pop up --}}
    <script>
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    </script>

    <script src="{{ asset('js/pop-up.js') }}"></script>

</body>

</html>
