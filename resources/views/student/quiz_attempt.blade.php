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
    <link rel="stylesheet" href="{{ asset('css/student/quiz_attempt.css') }}">
</head>

<body>

    <header class="custom-header sticky-top d-flex align-items-center justify-content-between px-4 z-3">
        <div class="d-flex align-items-center">
            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('image/logo.png') }}" width="35" height="35" alt="Logo">
                <span class="brand-text fw-bold text-dark fs-5">Quiz<span class="text-coral">Master</span></span>
            </a>
            <div class="vertical-divider mx-3"></div>
            <span class="small fw-medium text-muted-custom">{{ $quiz->title }}</span>
        </div>

        <div id="timer-container" class="timer-pill d-flex align-items-center gap-2 px-3 py-1.5 border rounded-pill">
            <i class='bx bx-time-five icon-indigo'></i>
            <span id="countdown" class="font-monospace fw-bold fs-5">
                {{ gmdate('i:s', $timeLeft) }}
            </span>
        </div>
    </header>

    <main class="container-xl py-4 my-2">
        <div class="row g-4">
            {{-- Left Side: Question Form & Main Navigation Footer --}}
            <div class="col-12 col-md-8 d-flex flex-column gap-4 order-2 order-md-1">
                <form id="answer-form" action="{{ route('student.quiz.save', ['attemptId' => $attempt->id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <input type="hidden" name="action" id="form-action" value="next">
                    <input type="hidden" name="target_question" id="target-question" value="">
                    <input type="hidden" name="current_number" value="{{ $questionNumber }}">

                    <div class="card border-0 p-4 p-md-5 bg-white shadow-card rounded-card">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill px-3 py-2 fw-semibold badge-indigo">
                                Question {{ $questionNumber }} of {{ $quiz->total_questions }}
                            </span>
                        </div>

                        <h4 class="fw-bold mb-4 lh-base">{{ $question->question_text }}</h4>

                        <div class="d-flex flex-column gap-3">
                            @foreach (['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $letter => $text)
                                @php $isSelected = $savedAnswer && $savedAnswer->selected_option === $letter; @endphp
                                @if (!empty(trim($text)))
                                    <div class="option-card d-flex align-items-center {{ $isSelected ? 'selected' : '' }}"
                                        data-option="{{ $letter }}">
                                        <input class="d-none radio-input" type="radio" name="selected_option"
                                            id="opt{{ $letter }}" value="{{ $letter }}"
                                            {{ $isSelected ? 'checked' : '' }}>

                                        <div
                                            class="custom-radio me-3 d-flex align-items-center justify-content-center rounded-circle">
                                            <span
                                                class="fw-bold small text-uppercase font-letter">{{ $letter }}</span>
                                        </div>

                                        <label class="form-check-label w-100 fw-medium m-0 label-clickable"
                                            for="opt{{ $letter }}">{{ $text }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </form>

                {{-- Navigation Footer --}}
                <div class="quiz-nav-footer">
                    @if ($questionNumber > 1)
                        <button type="button" class="quiz-nav-btn" onclick="submitAction('previous')">
                            <i class='bx bx-left-arrow-alt'></i> Previous
                        </button>
                    @else
                        <div></div>
                    @endif

                    <div class="quiz-nav-counter">
                        Question <strong>{{ $questionNumber }}</strong> of
                        <strong>{{ $quiz->total_questions }}</strong>
                    </div>

                    @if ($questionNumber < $quiz->total_questions)
                        <button type="button" class="quiz-nav-btn quiz-nav-btn--primary"
                            onclick="submitAction('next')">
                            Save &amp; Next <i class='bx bx-right-arrow-alt'></i>
                        </button>
                    @else
                        <button type="button" class="quiz-nav-btn quiz-nav-btn--submit" onclick="confirmSubmit()">
                            Submit Quiz <i class='bx bx-send'></i>
                        </button>
                    @endif
                </div>

                {{-- Mobile-Only Submit Action Block --}}
                <div class="d-block d-md-none mt-2">
                    <button type="button" onclick="confirmSubmit()"
                        class="btn w-100 py-3 text-white fw-bold bg-dark hstack justify-content-center gap-2 rounded-card">
                        Submit Attempt <i class='bx bx-send'></i>
                    </button>
                </div>
            </div>

            {{-- Right Side / Sidebar --}}
            <div class="col-12 col-md-4 d-flex flex-column gap-4 order-1 order-md-2 sidebar-sticky-container">
                {{-- Question Progress Block --}}
                <div class="card border-0 p-4 bg-white mb-0 rounded-card shadow-card">
                    <h5 class="fw-bold mb-3">Question Progress</h5>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach ([['class' => 'legend-answered', 'label' => 'Answered'], ['class' => 'legend-current', 'label' => 'Current'], ['class' => 'legend-unattempted', 'label' => 'Not attempted'], ['class' => 'legend-remaining', 'label' => 'Remaining']] as $legend)
                            <div class="d-flex align-items-center gap-1 small">
                                <div class="legend-indicator {{ $legend['class'] }}"></div>
                                <span class="text-muted-custom">{{ $legend['label'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="question-navigator-grid">
                        @foreach ($allQuestions as $index => $q)
                            @php
                                $num = $index + 1;
                                $isCurrent = $num === $questionNumber;
                                $isAnswered = in_array($q->id, $answeredQuestionIds);
                                $chipClass = match (true) {
                                    $isCurrent => 'chip-current',
                                    $isAnswered => 'chip-answered',
                                    default => 'chip-remaining',
                                };
                            @endphp
                            <button type="button" class="btn p-0 question-chip {{ $chipClass }}"
                                id="chip-{{ $q->id }}" data-answered="{{ $isAnswered ? '1' : '0' }}"
                                onclick="jumpToQuestion({{ $num }})">
                                {{ $num }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Laptop-Only Submit Action Block --}}
                <div class="d-none d-md-block">
                    <button type="button" onclick="confirmSubmit()"
                        class="btn w-100 py-3 text-white fw-bold bg-dark hstack justify-content-center gap-2 rounded-card">
                        Submit Attempt <i class='bx bx-send'></i>
                    </button>
                </div>
            </div>
        </div>
    </main>

    {{-- Hidden Form wrapper managed globally by JavaScript layout execution --}}
    <form id="submit-form" action="{{ route('student.quiz.submit', ['attemptId' => $attempt->id]) }}" method="POST"
        class="d-none">
        @csrf
    </form>

    <div id="quiz-config" data-time-left="{{ (int) $timeLeft }}"
        data-total-questions="{{ (int) $quiz->total_questions }}"
        data-save-url="{{ route('student.quiz.save', ['attemptId' => $attempt->id]) }}"
        data-available-url="{{ route('student.quiz.available') }}" class="d-none">
    </div>

    <script src="{{ asset('js/quiz-attempt.js') }}"></script>
</body>

</html>
