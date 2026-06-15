<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — QuizMaster</title>

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
                        <h3 class="brand-heading">Security First</h3>
                        <p class="brand-text">We just sent a security code to protect your account integrity.</p>
                    </div>
                </div>

                {{-- Right side - Form Container --}}
                <div class="col-lg-7 form-container d-flex align-items-center">
                    <div class="w-100">

                        {{-- Header --}}
                        <div class="view-header mb-4">
                            <h2 class="view-title">Verify Your Email</h2>
                            <p class="view-subtitle">Enter the 6-digit verification code sent to your email.</p>
                        </div>

                        {{-- Form --}}
                        <form id="otpForm" action="otp-verify" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label d-block text-center mb-3 fw-semibold">Verification Code</label>

                                {{-- Unified 6-Digit input layout --}}
                                <div class="d-flex gap-2 justify-content-center otp-input-group">
                                    <input type="text" name="otp[]" class="form-control text-center otp-box"
                                        maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                                    <input type="text" name="otp[]" class="form-control text-center otp-box"
                                        maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                                    <input type="text" name="otp[]" class="form-control text-center otp-box"
                                        maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                                    <input type="text" name="otp[]" class="form-control text-center otp-box"
                                        maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                                    <input type="text" name="otp[]" class="form-control text-center otp-box"
                                        maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                                    <input type="text" name="otp[]" class="form-control text-center otp-box"
                                        maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                                </div>
                            </div>

                            <button class="btn btn-submit w-100 mb-3" type="submit">Verify & Create Account</button>
                        </form>

                        {{-- Resend Option --}}
                        <div class="text-center mt-3 small">
                            <p class="text-muted mb-1">Didn't receive code?</p>
                            <a href="{{ url('otp-resend') }}"> Resend New Code </a>
                        </div>

                        {{-- Pop up Handler --}}
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                        @if (session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: '{{ session('success') }}',
                                    confirmButtonText: 'Let\'s Go'
                                });
                            </script>
                        @endif

                        @if (session('error'))
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid Code',
                                    text: '{{ session('error') }}',
                                    confirmButtonText: 'Try Again'
                                });
                            </script>
                        @endif

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


    {{-- Auto-focus next field JavaScript --}}
    <script>
        document.querySelectorAll('.otp-box').forEach((input, index, inputs) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>
