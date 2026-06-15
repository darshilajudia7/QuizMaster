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
    <link rel="stylesheet" href="{{ asset('css/teacher/dashboard.css') }}">
</head>

<body>

    @include('teacher.slider')

    <main class="main-viewport p-3 p-md-4 p-lg-5">

        {{-- Heading --}}
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="dashboard-title mb-1">Teacher Dashboard</h1>
                <p class="dashboard-subtitle mb-0">
                    Welcome Teacher! Here is your current academic classroom overview.
                </p>
            </div>

            <button class="btn btn-primary btn-create-quiz" onclick="window.location.href = 'quizzes'">
                <i class='bx bx-plus'></i>
                New Quiz Setup
            </button>
        </div>

        <div class="row g-3 mb-4">

            <div class="col-12 col-md-6">
                <div class="stat-card">
                    <div>
                        <small class="stat-label">Total Quizzes</small>
                        <h3 class="stat-number mb-0">{{ $totalQuizzes }}</h3>
                    </div>

                    <div class="stat-icon bg-indigo-pale">
                        <i class='bx bx-file'></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="stat-card">
                    <div>
                        <small class="stat-label">Total Submissions</small>
                        <h3 class="stat-number mb-0">148</h3>
                    </div>

                    <div class="stat-icon bg-amber-pale">
                        <i class='bx bx-user-check'></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-table">
                    <thead>
                        <tr>
                            <th>Quiz Title</th>
                            <th>Category</th>
                            <th>Questions</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($quizzes as $quiz)
                            @php
                                $now = now()->startOfDay();
                                $start = $quiz->start_date
                                    ? \Carbon\Carbon::parse($quiz->start_date)->startOfDay()
                                    : null;
                                $end = $quiz->end_date ? \Carbon\Carbon::parse($quiz->end_date)->endOfDay() : null;

                                if ($end && $now->gt($end)) {
                                    $status = 'closed';
                                    $badgeClass = 'status-closed';
                                    $statusText = 'Closed';
                                } elseif ($start && $now->lt($start)) {
                                    $status = 'upcoming';
                                    $badgeClass = 'status-upcoming';
                                    $statusText = 'Upcoming';
                                } else {
                                    $status = 'active';
                                    $badgeClass = 'status-active';
                                    $statusText = 'Active';
                                }
                                $categorySlug = strtolower(str_replace(' ', '-', $quiz->category));
                            @endphp

                            <tr>
                                <td>
                                    <div class="quiz-name">{{ $quiz->title }}</div>
                                </td>
                                <td>
                                    <span class="subject-badge badge-math">
                                        {{ $quiz->category }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ $quiz->total_questions }}</td>
                                <td>
                                    <span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class='bx bx-book-open display-4 d-block mb-2'></i>
                                    <p class="mb-0">No quizzes found.</p>
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
