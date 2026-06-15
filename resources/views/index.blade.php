<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QuizMaster</title>

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

    {{-- Link CSS file --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    {{-- Header --}}
    <header>
        <nav class="navbar navbar-expand-lg fixed-top custom-nav py-3">
            <div class="container-fluid px-md-5">

                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold m-0" href="#">
                    <img src="{{ asset('image/logo.png') }}" alt="QuizMaster Logo" style="width: 30px; height: 30px;">
                    <span class="logo-name">
                        Quiz<span class="text-coral">Master</span>
                    </span>
                </a>

                <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#quizmasterNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="quizmasterNav">
                    <ul class="navbar-nav mx-auto gap-lg-3">
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="#how">How it Works</a>
                        </li>
                    </ul>

                    <div class="d-sm-flex gap-2 mt-3 mt-lg-0">
                        <a href="{{ url('/login') }}"
                            class="btn btn-outline-primary px-4 py-2 d-block d-sm-inline-block mb-2 mb-sm-0">
                            Log In
                        </a>
                        <a href="{{ url('/register') }}" class="btn btn-coral px-4 py-2 d-block d-sm-inline-block">
                            Get Started
                        </a>
                    </div>
                </div>

            </div>
        </nav>
    </header>

    {{-- Hero section --}}
    <section class="hero-section py-5 d-flex align-items-center">
        <div class="container-fluid px-lg-5">
            <div class="row align-items-center min-vh-100 g-4 g-lg-5">

                <div class="col-lg-6 text-center text-lg-start px-3 px-sm-4 ps-lg-0 pe-lg-5" id="left">

                    <h1 class="hero-title mb-3">
                        Create, Manage, Deliver <br class="d-none d-sm-inline">
                        <span class="hl-coral">Quizzes</span> with <span class="hl-mint">Confidence</span>
                    </h1>
                    <p class="hero-desc mx-auto mx-lg-0 mb-4 px-1 px-sm-0">
                        The all-in-one assessment platform for educators and trainers.
                        Build quizzes, track learner progress, and get instant insights.
                    </p>

                    <div class="d-grid d-sm-flex gap-3 justify-content-center justify-content-lg-start px-3 px-sm-0">
                        <a href="{{ url('/register') }}"
                            class="btn btn-coral btn-lg px-4 d-inline-flex align-items-center justify-content-center">
                            <i class='bx bx-user-plus me-2'></i> Create Account
                        </a>
                        <a href="{{ url('/login') }}"
                            class="btn btn-outline-light btn-lg px-4 d-inline-flex align-items-center justify-content-center">
                            <i class='bx bx-log-in-circle me-2'></i> Log In
                        </a>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="qcard">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="qcard-cat">
                                    <i class='bx bx-code-alt me-1'></i>Programming • Q3 of 5
                                </div>
                                <div class="qcard-timer">
                                    <i class='bx bx-time-five me-1'></i>00:42
                                </div>
                            </div>

                            <div class="qprog-wrap mb-4">
                                <div class="qprog-fill"></div>
                            </div>

                            <h5 class="qtitle mb-3">
                                <i class='bx bx-help-circle me-1'></i>Quiz Question
                            </h5>

                            <p class="qtext mb-4">
                                Which language is primarily used for server-side development in Laravel?
                            </p>

                            <div class="d-flex flex-column gap-3">
                                <div class="qopt correct d-flex justify-content-between align-items-center">
                                    <span><i class='bx bx-code-alt me-2'></i>PHP</span>
                                    <i class='bx bxs-check-circle option-icon'></i>
                                </div>

                                <div class="qopt picked d-flex justify-content-between align-items-center">
                                    <span><i class='bx bx-code-curly me-2'></i>HTML</span>
                                    <i class='bx bxs-x-circle option-icon'></i>
                                </div>

                                <div class="qopt"><i class='bx bx-data me-2'></i>MySQL</div>
                                <div class="qopt"><i class='bx bx-terminal me-2'></i>JavaScript</div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Feature section --}}
    <section class="py-5 my-5 bg-white" id="features">
        <div class="container px-md-5">
            <div class="text-center mb-5">
                <h2 class="sec-title mt-2 mb-3">Powerful tools for every educator</h2>
                <p class="sec-sub text-muted mx-auto">
                    From drag-and-drop quiz builders to real-time analytics — QuizMaster gives you the full toolkit.
                </p>
            </div>

            <div class="row g-4">

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feat-card h-100">
                        <div class="ficon-box indigo"><i class='bx bx-edit-alt'></i></div>
                        <h5 class="fw-bold fs-6 mb-2">Smart Quiz Builder</h5>
                        <p class="text-muted small mb-0">Create MCQ, true/false, fill blanks and more in minutes.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feat-card h-100">
                        <div class="ficon-box coral"><i class='bx bx-time-five'></i></div>
                        <h5 class="fw-bold fs-6 mb-2">Timed Assessments</h5>
                        <p class="text-muted small mb-0">Set timers with auto submit and rules.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feat-card h-100">
                        <div class="ficon-box mint"><i class='bx bx-line-chart'></i></div>
                        <h5 class="fw-bold fs-6 mb-2">Real-Time Analytics</h5>
                        <p class="text-muted small mb-0">Track scores and performance instantly.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feat-card h-100">
                        <div class="ficon-box amber"><i class='bx bx-group'></i></div>
                        <h5 class="fw-bold fs-6 mb-2">Group Management</h5>
                        <p class="text-muted small mb-0">Manage batches and compare results easily.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feat-card h-100">
                        <div class="ficon-box purple"><i class='bx bx-lock-alt'></i></div>
                        <h5 class="fw-bold fs-6 mb-2">Anti-Cheat Controls</h5>
                        <p class="text-muted small mb-0">Secure exams with strict control options.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feat-card h-100">
                        <div class="ficon-box blue"><i class='bx bx-cloud-download'></i></div>
                        <h5 class="fw-bold fs-6 mb-2">Export & Reports</h5>
                        <p class="text-muted small mb-0">Download PDF and Excel reports instantly.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- How it works section --}}
    <section class="py-5 bg-light" id="how">
        <div class="container px-md-5 py-4">
            <div class="text-center mb-5">
                <h2 class="sec-title mt-2 mb-3">Up and running in 4 steps</h2>
                <p class="sec-sub text-muted mx-auto">
                    Simple for teachers to host. Effortless for students to play.
                </p>
            </div>

            <div class="row g-4 position-relative steps-container">

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-circle indigo">1</div>
                        <h6 class="fw-bold mb-2">Create Account</h6>
                        <p class="text-muted small mb-0">Sign up as a teacher or student in seconds.</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-circle coral">2</div>
                        <h6 class="fw-bold mb-2">Setup or Join</h6>
                        <p class="text-muted small mb-0">Teachers build quizzes; students get ready to enter.</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-circle mint">3</div>
                        <h6 class="fw-bold mb-2">Connect Instantly</h6>
                        <p class="text-muted small mb-0">Teachers share the code; students enter the room code.</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-circle amber">4</div>
                        <h6 class="fw-bold mb-2">Track & Learn</h6>
                        <p class="text-muted small mb-0">Get instant reports for teaching and learning.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('footer')

</body>

</html>
