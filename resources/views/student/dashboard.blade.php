<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard — QuizMaster</title>

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
    <link rel="stylesheet" href="{{ asset('css/student/dashboard.css') }}">
</head>

<body>

    @include('student.navbar')

    <main class="container-fluid py-4 px-3 px-md-5">

        {{-- Heading --}}
        <div class="mb-5">
            <h1 class="dashboard-title h2 mb-2">Student Overview</h1>
            <p class="dashboard-subtitle fs-6">
                Welcome back! Track your quiz activity, monitor your performance,
                and stay updated with your recent results.
            </p>
        </div>

        {{-- Overview Stats Widgets --}}
        <div class="row g-4 mb-5">

            {{-- Total Available Quizzes --}}
            <div class="col-12 col-sm-6">
                <div class="glass-card p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="icon-box icon-primary">
                            <i class='bx bx-book-open fs-3'></i>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-semibold tracking-wider">
                                Total Quizzes
                            </small>
                            <h2 class="fw-bold mb-0 mt-1">{{ $totalquizzes }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Quizzes Count --}}
            <div class="col-12 col-sm-6">
                <div class="glass-card p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="icon-box icon-success">
                            <i class='bx bx-edit-alt fs-3'></i>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-semibold tracking-wider">
                                Attempted Quizzes
                            </small>
                            <h2 class="fw-bold mb-0 mt-1">{{ $attemptedCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Header --}}
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <h4 class="h4 fw-bold mb-0">Recent Quiz Results</h4>
            <a href="{{ url('history') }}" class="btn btn-sm btn-outline-primary px-3 py-2 fw-semibold">
                View All History
            </a>
        </div>

        {{-- Results Table Matrix Card --}}
        <div class="glass-card">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3-5">QUIZ TITLE</th>
                            <th class="px-4 py-3-5">DATE</th>
                            <th class="px-4 py-3-5 text-end text-md-start">SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttempts as $attempt)
                            @php
                                $score = round($attempt->score_percentage, 1);
                                $scoreColorClass =
                                    $score >= 85 ? 'text-success' : ($score < 75 ? 'text-danger' : 'text-primary');
                                $dateObj = $attempt->submitted_at ?? $attempt->created_at;
                            @endphp
                            <tr>
                                <td data-label="Quiz Title" class="px-4 py-4 fw-semibold text-dark-emphasis">
                                    {{ $attempt->quiz->title ?? 'Untitled Assessment' }}
                                </td>
                                <td data-label="Date" class="px-4 py-4 text-secondary">
                                    {{ $dateObj ? $dateObj->format('M d, Y') : 'N/A' }}
                                </td>
                                <td data-label="Score"
                                    class="px-4 py-4 fw-bold {{ $scoreColorClass }} text-end text-md-start">
                                    {{ $score }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="bx bx-folder-open fs-2"></i></div>
                                    No recent quiz attempts found. Ready to take your first test?
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>

</html>
