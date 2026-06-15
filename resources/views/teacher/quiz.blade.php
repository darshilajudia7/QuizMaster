<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create & Manage Quiz</title>

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
    <link rel="stylesheet" href="{{ asset('css/teacher/quiz.css') }}">

</head>

<body>

    @include('teacher.slider')

    {{-- Main Content Viewport --}}
    <main class="main-viewport p-3 p-md-4 p-lg-5 w-100-override">

        {{-- Header Actions --}}
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="dashboard-title m-0">Quiz Management</h1>
            </div>
            <button class="btn-create-quiz" id="openNewQuizBtn">
                <i class='bx bx-plus fs-5'></i> New Quiz
            </button>
        </div>

        {{-- Filter Container --}}
        <div class="pipeline-filter-container p-4 mb-4">
            <h6 class="form-section-banner m-0 pb-3 border-bottom border-light mb-3">
                <i class='bx bx-filter-alt me-2 text-primary'></i> Search & Filters
            </h6>

            {{-- Form --}}
            <form method="GET" action="{{ route('quizzes.view') }}" id="filterControlForm">
                <div class="row g-3">

                    {{-- Search bar --}}
                    <div class="col-12 col-md-6">
                        <label for="tableSearchInput" class="form-label modal-custom-label">Search Quizzes</label>
                        <div class="search-container">
                            <i class='bx bx-search'></i>
                            <input type="text" name="search" class="form-control search-input" id="tableSearchInput"
                                placeholder="Search quizzes by title..." value="{{ $search }}" autocomplete="off">
                        </div>
                    </div>

                    {{-- Category Filter Dropdown --}}
                    <div class="col-12 col-md-6">
                        <label for="filterCategorySelect" class="form-label modal-custom-label">Subject Category
                            Filter</label>
                        <div class="input-group-custom" style="max-width: 320px;">
                            <span class="input-icon-left"><i class='bx bx-bookmark'></i></span>
                            <select name="category" class="form-select modal-input-field" id="filterCategorySelect">
                                <option value="all" {{ $categoryFilter == 'all' ? 'selected' : '' }}>All Categories
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}"
                                        {{ $categoryFilter == $category ? 'selected' : '' }}>{{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Top View  --}}
        <div id="metrics-container">
            <div class="row g-3 mb-4">

                {{-- Total Quizzes --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-icon-wrapper"
                            style="background-color: var(--indigo-pale); color: var(--indigo);"><i
                                class='bx bx-layer'></i></div>
                        <div>
                            <div class="metric-value">{{ $totalCount }}</div>
                            <div class="metric-label">Total Quizzes</div>
                        </div>
                    </div>
                </div>

                {{-- Active Quizzes --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-icon-wrapper"
                            style="background-color: var(--mint-pale); color: var(--mint);"><i
                                class='bx bx-play-circle'></i></div>
                        <div>
                            <div class="metric-value">{{ $activeCount }}</div>
                            <div class="metric-label">Active Quizzes</div>
                        </div>
                    </div>
                </div>

                {{-- Upcoming Quizzes --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-icon-wrapper"
                            style="background-color: var(--warning-bg); color: var(--warning);"><i
                                class='bx bx-calendar'></i></div>
                        <div>
                            <div class="metric-value">{{ $upcomingCount }}</div>
                            <div class="metric-label">Upcoming Quizzes</div>
                        </div>
                    </div>
                </div>

                {{-- Cloased Quizzes --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-icon-wrapper"
                            style="background-color: var(--coral-pale); color: var(--coral-dark);"><i
                                class='bx bx-x-circle'></i></div>
                        <div>
                            <div class="metric-value">{{ $closedCount }}</div>
                            <div class="metric-label">Closed Quizzes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table  --}}
        <div id="table-workspace-container" class="table-container d-flex flex-column w-100">
            <div class="p-3 bg-light border-bottom">
                <span class="small text-uppercase fw-bold text-muted tracking-wider">
                    <i class='bx bx-grid-alt me-1'></i> Quiz Workspace
                </span>
            </div>

            @if ($quizzes->count() > 0)
                <div class="table-responsive w-100">
                    <table class="table custom-table w-100 align-middle m-0" id="dashboardTable">

                        {{-- Table Heading  --}}
                        <thead>
                            <tr>
                                <th style="width: 30%">Quiz Title</th>
                                <th style="width: 20%">Category</th>
                                <th style="width: 15%">Questions</th>
                                <th style="width: 15%">Quiz Window</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 10%" class="text-center">Actions</th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody id="tableBodyPipeline">
                            @foreach ($quizzes as $quiz)
                                @php
                                    // Date & Time
                                    $nowDate = \Carbon\Carbon::now()->startOfDay();
                                    $start = $quiz->start_date
                                        ? \Carbon\Carbon::parse($quiz->start_date)->startOfDay()
                                        : null;
                                    $end = $quiz->end_date ? \Carbon\Carbon::parse($quiz->end_date)->endOfDay() : null;

                                    // Stauts
                                    if ($end && $nowDate->gt($end)) {
                                        $status = 'closed';
                                        $badgeClass = 'status-closed';
                                        $statusText = 'Closed';
                                    } elseif ($start && $nowDate->lt($start)) {
                                        $status = 'upcoming';
                                        $badgeClass = 'status-upcoming';
                                        $statusText = 'Upcoming';
                                    } else {
                                        $status = 'active';
                                        $badgeClass = 'status-active';
                                        $statusText = 'Active';
                                    }

                                    // Category
                                    $categorySlug = strtolower(str_replace(' ', '-', $quiz->category));

                                    $categoryClasses = [
                                        'Mathematics' => 'badge-mathematics',
                                        'Science' => 'badge-science',
                                        'History' => 'badge-history',
                                        'General Knowledge' => 'badge-gk',
                                        'Programming' => 'badge-programming',
                                    ];

                                    $categoryClass = $categoryClasses[$quiz->category] ?? 'badge-default';

                                @endphp

                                <tr data-id="{{ $quiz->id }}" data-start-date="{{ $quiz->start_date }}"
                                    data-end-date="{{ $quiz->end_date }}" data-category="{{ $quiz->category }}"
                                    data-status="{{ $status }}">

                                    {{-- Title --}}
                                    <td>
                                        <div class="quiz-name">{{ $quiz->title }}</div>
                                    </td>

                                    {{-- Category --}}
                                    <td>
                                        <span class="subject-txt {{ $categoryClass }}">
                                            {{ $quiz->category }}
                                        </span>
                                    </td>

                                    {{-- Tptal Question --}}
                                    <td class="question-count-cell">{{ $quiz->total_questions }}</td>

                                    {{-- Dates --}}
                                    <td>
                                        <span class="d-block fw-semibold text-dark">
                                            {{ $quiz->start_date ? \Carbon\Carbon::parse($quiz->start_date)->format('M d, Y') : 'N/A' }}
                                        </span>
                                        <span class="small text-muted">→
                                            {{ $quiz->end_date ? \Carbon\Carbon::parse($quiz->end_date)->format('M d, Y') : 'N/A' }}</span>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        <span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span>
                                    </td>

                                    {{-- Buttons --}}
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="action-icon-btn btn-edit-row" title="Edit Quiz">
                                                <i class='bx bx-edit-alt text-primary'></i>
                                            </button>
                                            <button class="action-icon-btn btn-delete" title="Delete Quiz">
                                                <i class='bx bx-trash text-danger'></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="placeholder-empty-state text-center py-5 m-4" id="resultsEmptyPlaceholder">
                    <div class="empty-icon-wrapper mb-3"><i class='bx bx-filter-trigger fs-3 text-muted'></i></div>
                    <h6 class="text-dark fw-semibold mb-1">No Matching Quizzes Found</h6>
                    <p class="text-muted small max-w-xs mx-auto m-0">Adjust your search parameters or chosen category
                        filter selection.</p>
                </div>
            @endif

            {{-- Pagiantion --}}
            <div class="pagination-container border-top">

                {{-- Pagination Summary Text --}}
                <div class="text-muted small" id="paginationSummaryText">
                    Showing {{ $quizzes->firstItem() ?? 0 }} to {{ $quizzes->lastItem() ?? 0 }} of
                    {{ $quizzes->total() }} workspace matches
                </div>

                {{-- Pagination Navigation --}}
                @if ($quizzes->hasPages())
                    <nav aria-label="Table navigation">
                        <ul class="pagination m-0" id="paginationPagesWrapper">

                            {{-- Previous Page Button --}}
                            @if ($quizzes->onFirstPage())
                                <li class="page-item disabled"><span class="page-link"><i
                                            class='bx bx-chevron-left'></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link ajax-page-link"
                                        href="{{ $quizzes->previousPageUrl() }}"><i
                                            class='bx bx-chevron-left'></i></a></li>
                            @endif

                            {{-- Page Number Links --}}
                            @foreach ($quizzes->getUrlRange(1, $quizzes->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $quizzes->currentPage() ? 'active' : '' }}">
                                    <a class="page-link ajax-page-link"
                                        href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Next Page Button --}}
                            @if ($quizzes->hasMorePages())
                                <li class="page-item"><a class="page-link ajax-page-link"
                                        href="{{ $quizzes->nextPageUrl() }}"><i class='bx bx-chevron-right'></i></a>
                                </li>
                            @else
                                <li class="page-item disabled"><span class="page-link"><i
                                            class='bx bx-chevron-right'></i></span></li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </main>

    {{-- Create & Edit Modal --}}
    <div class="modal fade" id="createQuizModal" tabindex="-1" aria-labelledby="modalHeaderTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content portfolio-modal-card">
                <div class="modal-header border-0 pb-0 pt-4 px-4">

                    {{-- Heading --}}
                    <div>
                        <h5 class="modal-title framework-modal-title" id="modalHeaderTitle">Create New Quiz</h5>
                        <p class="text-muted small mb-0 mt-1">Configure core quiz details and structured dynamic option
                            inputs.</p>
                    </div>

                    {{-- Buttons --}}
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <i class='bx bx-x'></i>
                    </button>
                </div>

                {{-- Form --}}
                <div class="modal-body px-4 pt-4 pb-2">
                    <form id="createQuizForm" action="/quizzes" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" id="editQuizId" name="quiz_id" value="{{ old('quiz_id') }}">

                        {{-- Title Input --}}
                        <div class="mb-4">
                            <label for="quizTitle" class="form-label modal-custom-label">Quiz Title</label>
                            <div class="input-group-custom">
                                <span class="input-icon-left"><i class='bx bx-heading'></i></span>
                                <input type="text"
                                    class="form-control modal-input-field @error('title') is-invalid @enderror"
                                    id="quizTitle" name="title" value="{{ old('title') }}"
                                    placeholder="Enter a Quiz Title" required>
                            </div>
                            @error('title')
                                <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Title Input --}}
                        <div class="mb-4">
                            <label for="quizDesc" class="form-label modal-custom-label">Quiz Describe(option)</label>
                            <div class="input-group-custom">
                                <span class="input-icon-left"><i class='bx bx-heading'></i></span>
                                <input type="text"
                                    class="form-control modal-input-field @error('desc') is-invalid @enderror"
                                    id="quizDesc" name="desc" value="{{ old('desc') }}"
                                    placeholder="Enter a Quiz desc" required>
                            </div>
                            @error('desc')
                                <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category and Total Question --}}
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="quizSubject" class="form-label modal-custom-label">Subject
                                    Category</label>
                                <div class="input-group-custom">
                                    <span class="input-icon-left"><i class='bx bx-bookmark'></i></span>
                                    <select
                                        class="form-select modal-input-field @error('category') is-invalid @enderror"
                                        id="quizSubject" name="category">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ old('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category')
                                    <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="totalQuestions" class="form-label modal-custom-label">Total Questions
                                    Count</label>
                                <div class="input-group-custom">
                                    <span class="input-icon-left"><i class='bx bx-list-ol'></i></span>
                                    <input type="number"
                                        class="form-control modal-input-field @error('total_questions') is-invalid @enderror"
                                        id="totalQuestions" name="total_questions"
                                        value="{{ old('total_questions', 0) }}" min="0" required>
                                </div>
                                @error('total_questions')
                                    <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Dates --}}
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="quizStartDate" class="form-label modal-custom-label">Start Date</label>
                                <div class="input-group-custom">
                                    <span class="input-icon-left"><i class='bx bx-calendar-event'></i></span>
                                    <input type="date"
                                        class="form-control modal-input-field @error('start_date') is-invalid @enderror"
                                        id="quizStartDate" name="start_date" value="{{ old('start_date') }}">
                                </div>
                                @error('start_date')
                                    <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="quizEndDate" class="form-label modal-custom-label">End Date</label>
                                <div class="input-group-custom">
                                    <span class="input-icon-left"><i class='bx bx-calendar-check'></i></span>
                                    <input type="date"
                                        class="form-control modal-input-field @error('end_date') is-invalid @enderror"
                                        id="quizEndDate" name="end_date" value="{{ old('end_date') }}">
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Question Bank --}}
                        <div
                            class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-light">
                            <div class="form-section-banner m-0">
                                <i class='bx bx-layer me-2'></i> Question Set Blocks <span class="counter-badge"
                                    id="uiQuestionCounter">({{ old('questions') ? count(old('questions')) : 0 }})</span>
                            </div>
                            <button type="button" class="btn-add-block-dynamic" id="incrementQuestionsBtn">
                                <i class='bx bx-plus-circle'></i> Add Question Block
                            </button>
                        </div>
                        @error('questions')
                            <div class="alert alert-danger p-2 small">{{ $message }}</div>
                        @enderror

                        {{-- Placeholder Empty State Container --}}
                        <div class="placeholder-empty-state text-center py-5 mb-3" id="placeholderBlock"
                            style="{{ old('questions') ? 'display: none;' : 'display: block;' }}">
                            <div class="empty-icon-wrapper mb-3"><i class='bx bx-data fs-3 text-muted'></i></div>
                            <h6 class="text-dark fw-semibold mb-1">No Questions Added</h6>
                            <p class="text-muted small max-w-xs mx-auto m-0">Click the add button above to map your
                                elements structural layout.</p>
                        </div>

                        {{-- Question Deatils --}}
                        <div id="dynamicQuestionsWrapper" class="w-100 d-flex flex-column gap-4 mb-3">


                            @if (old('questions'))
                                @foreach (old('questions') as $index => $qBlock)
                                    <div class="dynamic-question-item" data-index="{{ $index }}">

                                        {{-- Questions Heading --}}
                                        <div class="question-card-header">
                                            <i class='bx bx-help-circle text-primary'></i> Question
                                            {{ $index + 1 }}
                                        </div>

                                        {{-- Questions --}}
                                        <div class="mb-4">
                                            <label class="form-label small fw-semibold text-dark mb-2">Question
                                                Statement</label>
                                            <input type="text"
                                                name="questions[{{ $index }}][question_text]"
                                                class="form-control @error("questions.$index.question_text") is-invalid @enderror"
                                                value="{{ $qBlock['question_text'] ?? '' }}"
                                                placeholder="Enter question description string...">
                                            @error("questions.$index.question_text")
                                                <div class="invalid-feedback d-block text-danger small mt-1">
                                                    {{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Options --}}
                                        <div class="mb-4">
                                            <label class="form-label small fw-semibold text-dark mb-2">Options</label>
                                            <div class="row g-3">
                                                @foreach (['option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D'] as $key => $opt)
                                                    <div class="col-12 col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">{{ $opt }}</span>
                                                            <input type="text"
                                                                name="questions[{{ $index }}][{{ $key }}]"
                                                                class="form-control @error("questions.$index.$key") is-invalid @enderror"
                                                                value="{{ $qBlock[$key] ?? '' }}"
                                                                placeholder="Option {{ $opt }} Value">
                                                        </div>
                                                        @error("questions.$index.$key")
                                                            <div class="invalid-feedback d-block text-danger small mt-1">
                                                                {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Correct Option --}}
                                        <div class="p-3 correct-answer-select-wrapper">
                                            <div class="row align-items-center g-2">
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label class="form-label small fw-bold text-primary mb-0">Correct
                                                        Option:</label>
                                                </div>
                                                <div class="col-12 col-sm-8 col-md-9">
                                                    <select name="questions[{{ $index }}][correct_option]"
                                                        class="form-select form-select-sm fw-semibold @error("questions.$index.correct_option") is-invalid @enderror">
                                                        <option value="">Choose correct option</option>
                                                        @foreach (['A', 'B', 'C', 'D'] as $opt)
                                                            <option value="{{ $opt }}"
                                                                {{ ($qBlock['correct_option'] ?? '') == $opt ? 'selected' : '' }}>
                                                                Option {{ $opt }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error("questions.$index.correct_option")
                                                        <div class="invalid-feedback d-block text-danger small mt-1">
                                                            {{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Buttons  --}}
                        <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                            <button type="button" class="btn btn-modal-cancel"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-modal-submit" id="submitModalBtn">Create
                                Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Model --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Heading --}}
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-danger" id="deleteModalTitle">Delete Quiz Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Contents --}}
                <div class="modal-body px-4 py-2">
                    <p class="text-muted m-0">Are you sure you want to remove this quiz permanently?</p>
                </div>

                {{-- Buttons --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteRowBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Laravel Validation & Old Input Bridge --}}
    <div id="laravel-validation-bridge" data-has-errors="{{ $errors->any() ? 'true' : 'false' }}"
        data-old-id="{{ old('quiz_id') }}"
        data-old-questions-count="{{ old('questions') ? count(old('questions')) : 0 }}">
    </div>


    {{-- Pop up --}}
    <script>
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    </script>


    {{-- JS link --}}
    <script src="{{ asset('js/Teacher/quiz.js') }}"></script>
    <script src="{{ asset('js/pop-up.js') }}"></script>
</body>

</html>
