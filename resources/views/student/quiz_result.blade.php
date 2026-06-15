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
    <link rel="stylesheet" href="{{ asset('css/student/student_result.css') }}">
</head>


<body>

    @include('student.navbar')

    <main class="container mx-auto px-3 py-5 d-flex flex-column align-items-center" style="max-width: 1024px;">
        <div class="w-100 result-card mb-4" style="max-width: 896px;">
            <div class="row g-0">

                {{-- Score Circle --}}
                <div
                    class="col-12 col-md-5 score-display-pane p-5 d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="position-relative d-flex align-items-center justify-content-center"
                        style="width: 176px; height: 176px;">
                        <svg class="position-absolute progress-svg"
                            style="transform: rotate(-90deg); width: 176px; height: 176px;">
                            <circle cx="88" cy="88" r="80" fill="transparent" stroke="currentColor"
                                stroke-width="10" style="opacity: 0.15; color: var(--text-muted);"></circle>
                            <circle cx="88" cy="88" r="80" fill="transparent" stroke="var(--indigo)"
                                stroke-width="10" stroke-dasharray="502.65" stroke-dashoffset="{{ $strokeDashoffset }}"
                                stroke-linecap="round" style="transition: stroke-dashoffset 1s ease-in-out;"></circle>
                        </svg>
                        <div class="d-flex flex-column z-1">
                            <span class="display-5 lh-1" style="font-weight: 800;">{{ round($percentage) }}%</span>
                            <span class="text-uppercase mt-1 opacity-75"
                                style="font-size: 11px; font-weight: 700;">Score</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="h2 fw-bold mb-0">{{ $correctAnswers }}/{{ $totalQuestions }}</span>
                        <p class="small opacity-85 mt-1 mb-0 fw-medium">Questions Correct</p>
                    </div>
                </div>

                {{-- Analytics --}}
                <div class="col-12 col-md-7 p-5 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bx bxs-star" style="color: {{ $performance['icon_color'] }}; font-size: 28px;"></i>
                        <h2 class="h3 fw-bold mb-0" style="color: var(--text-dark);">{{ $performance['heading'] }}</h2>
                    </div>
                    <p class="mb-4 lh-base" style="color: var(--text-mid); font-size: 16px;">
                        {{ $performance['message'] }}
                    </p>

                    <div class="row g-3 pt-4 border-top" style="border-color: var(--border) !important;">
                        @foreach ([['label' => 'Correct', 'value' => $correctAnswers, 'color' => 'var(--mint,#10b981)'], ['label' => 'Incorrect', 'value' => $incorrectAnswers, 'color' => 'var(--coral,#ff6b4a)'], ['label' => 'Total', 'value' => $totalQuestions, 'color' => 'var(--text-dark)'], ['label' => 'Time Taken', 'value' => $timeTakenFormatted, 'color' => 'var(--indigo)']] as $stat)
                            <div class="col-6 col-sm-3 d-flex flex-column">
                                <span class="text-uppercase fw-bold" style="color: var(--text-muted); font-size: 11px;">
                                    {{ $stat['label'] }}
                                </span>
                                <span class="h4 fw-bold mb-0" style="color: {{ $stat['color'] }};">
                                    {{ $stat['value'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="row g-3 w-100 mt-2 justify-content-center" style="max-width: 480px;">
            <div class="col-12 col-sm-6">
                <a href="{{ route('student.quiz.available') }}"
                    class="btn btn-primary-gradient w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none text-white py-2">
                    <i class="bx bx-history" style="font-size: 18px;"></i>
                    View Available
                </a>
            </div>
            <div class="col-12 col-sm-6">
                <a href="{{ url('/student') }}"
                    class="btn btn-outline-indigo w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none py-2">
                    <i class="bx bx-grid-alt" style="font-size: 18px;"></i>
                    Dashboard
                </a>
            </div>
        </div>
    </main>

    <canvas id="confetti" class="confetti-canvas"></canvas>

    <script src="{{ asset('js/quiz-result.js') }}" data-percentage="{{ $percentage }}"></script>
</body>

</html>
