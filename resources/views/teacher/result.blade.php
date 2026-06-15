<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>

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
    <link rel="stylesheet" href="{{ asset('css/teacher/result.css') }}">

</head>

<body>

    @include('teacher.slider')

    <main class="main-viewport p-3 p-md-4 p-lg-5 w-100-override">

        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="dashboard-title m-0">Performance Analytics</h1>
                <small class="text-muted">Logged in: <strong>{{ Session::get('user_name') }}</strong> (Teacher
                    Dashboard)</small>
            </div>
        </div>

        {{-- Filter --}}
        <form action="{{ url()->current() }}" method="GET" id="filterPipelineForm">
            <div class="table-container p-4 mb-4">
                <h6 class="form-section-banner m-0 pb-3 border-bottom border-light mb-3">
                    <i class='bx bx-filter-alt me-2 text-primary'></i> Filter Segment Pipeline
                </h6>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="filterCategory" class="form-label modal-custom-label">1. Subject Category</label>
                        <div class="input-group-custom">
                            <span class="input-icon-left"><i class='bx bx-bookmark'></i></span>
                            <select class="form-select modal-input-field" id="filterCategory" name="category">
                                <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All
                                    Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}"
                                        {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-5">
                        <label for="filterQuizTitle" class="form-label modal-custom-label">2. Target Quiz Title</label>
                        <div class="input-group-custom">
                            <span class="input-icon-left"><i class='bx bx-heading'></i></span>
                            <select class="form-select modal-input-field" id="filterQuizTitle" name="quiz_id">
                                <option value="all" {{ request('quiz_id') == 'all' ? 'selected' : '' }}>All Quizzes
                                </option>
                                {{-- This loop handles fallback visibility when JS is disabled or during reload state --}}
                                @foreach ($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}" data-category="{{ $quiz->category }}"
                                        {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}>
                                        {{ $quiz->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 d-flex align-items-end">
                        <div class="search-container w-100">
                            <i class='bx bx-search'></i>
                            <input type="text" class="form-control search-input" id="studentSearchInput"
                                name="search" value="{{ request('search') }}" placeholder="Search student name...">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Metric card --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-icon-wrapper"
                        style="background-color: var(--indigo-pale); color: var(--indigo);"><i class='bx bx-group'></i>
                    </div>
                    <div>
                        <div class="metric-value">{{ $metrics['total_students'] }}</div>
                        <div class="metric-label">Total Students</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-icon-wrapper" style="background-color: var(--mint-pale); color: var(--mint);"><i
                            class='bx bx-trending-up'></i></div>
                    <div>
                        <div class="metric-value">{{ $metrics['highest_mark'] }}%</div>
                        <div class="metric-label">Highest Mark</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-icon-wrapper"
                        style="background-color: var(--coral-pale); color: var(--coral-dark);"><i
                            class='bx bx-trending-down'></i></div>
                    <div>
                        <div class="metric-value">{{ $metrics['minimum_mark'] }}%</div>
                        <div class="metric-label">Minimum Mark</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-icon-wrapper" style="background-color: var(--amber-pale); color: var(--amber);">
                        <i class='bx bx-calculator'></i>
                    </div>
                    <div>
                        <div class="metric-value">{{ $metrics['average_mark'] }}%</div>
                        <div class="metric-label">Average Mark</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-container d-flex flex-column w-100">
            @if ($attempts->count() > 0)
                <div class="table-responsive w-100">
                    <table class="table custom-table w-100 align-middle m-0" id="resultsDashboardTable">

                        {{-- Table Head  --}}
                        <thead>
                            <tr>
                                <th style="width: 25%">Student Name</th>
                                <th style="width: 25%">Quiz Title</th>
                                <th style="width: 15%">Category</th>
                                <th style="width: 15%" class="text-center">Score Obtained</th>
                                <th style="width: 10%">Performance</th>
                            </tr>
                        </thead>

                        {{-- Table body --}}
                        <tbody>
                            @foreach ($attempts as $item)
                                <tr>
                                    <td>
                                        <div class="quiz-name fw-semibold">{{ $item['student_name'] }}</div>
                                    </td>
                                    <td>{{ $item['quiz_title'] }}</td>
                                    <td><span
                                            class="subject-txt badge-{{ $item['category_slug'] }}">{{ $item['category'] }}</span>
                                    </td>
                                    <td class="text-center question-count-cell">{{ $item['obtained'] }} /
                                        {{ $item['total'] }}</td>
                                    <td><span
                                            class="badge {{ $item['badge_class'] }}">{{ $item['percentage'] }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="placeholder-empty-state text-center py-5 m-4" id="resultsEmptyPlaceholder">
                    <div class="empty-icon-wrapper mb-3">
                        <i class='bx bx-search-alt fs-3 text-muted'></i>
                    </div>
                    <h6 class="text-dark fw-semibold mb-1">No Matching Grades Found</h6>
                    <p class="text-muted small max-w-xs mx-auto m-0">Adjust your selections or search parameters.</p>
                </div>
            @endif

            {{-- Pagination --}}
            <div class="pagination-container border-top d-flex justify-content-between align-items-center p-3">
                <div class="text-muted small">
                    Showing {{ $attempts->firstItem() ?? 0 }} to {{ $attempts->lastItem() ?? 0 }} of
                    {{ $attempts->total() }} performance records
                </div>
                <nav aria-label="Results navigation">
                    {{ $attempts->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Filter from element
            const form = document.getElementById('filterPipelineForm');
            const filterCategory = document.getElementById('filterCategory');
            const filterQuizTitle = document.getElementById('filterQuizTitle');
            const studentSearchInput = document.getElementById('studentSearchInput');

            const quizDatabaseRelations = @json($quizzes);

            // Filter Category
            function syncQuizDropdownOptions(selectedCategory) {
                const currentValue = filterQuizTitle.value;

                filterQuizTitle.innerHTML = '<option value="all">All Quizzes</option>';

                const matchingQuizzes = selectedCategory === 'all' ?
                    quizDatabaseRelations :
                    quizDatabaseRelations.filter(q => q.category === selectedCategory);

                matchingQuizzes.forEach(quiz => {
                    const option = document.createElement('option');
                    option.value = quiz.id;
                    option.textContent = quiz.title;
                    if (String(quiz.id) === String(currentValue)) {
                        option.selected = true;
                    }
                    filterQuizTitle.appendChild(option);
                });
            }

            syncQuizDropdownOptions(filterCategory.value);

            // Category filter chage
            filterCategory.addEventListener('change', () => {
                syncQuizDropdownOptions(filterCategory.value);
                filterQuizTitle.value = "all";
                form.submit();
            });

            filterQuizTitle.addEventListener('change', () => {
                form.submit();
            });

            // Search filter
            let searchTimeout;
            studentSearchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });
    </script>

</body>

</html>
