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
    <link rel="stylesheet" href="{{ asset('css/student/history.css') }}">
</head>

@include('student.navbar')

<body>

    {{-- Main --}}
    <main class="p-3 p-md-4">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="mb-4">
                <h2 class="fw-bold m-0 text-dark-custom">Your Performance Metrics</h2>
                <p class="mt-2 mb-0 text-light-custom text-max-w">
                    Analyze your personal assessment reports, verified scores, processing timelines, and cumulative
                    progress tracking totals.
                </p>
            </div>

            {{-- Metrics Overview Header --}}
            <div class="row g-3 mb-4">
                @php
                    $statItems = [
                        [
                            'title' => 'Your Total Attempts',
                            'value' => $totalAttempts,
                            'icon' => 'bx-history',
                            'class' => 'stat-indigo',
                        ],
                        [
                            'title' => 'Your Avg. Score',
                            'value' => "$avgScore%",
                            'icon' => 'bx-calculator',
                            'class' => 'stat-blue',
                        ],
                        [
                            'title' => 'Your Best Score',
                            'value' => "$highestScore%",
                            'icon' => 'bx-trending-up',
                            'class' => 'stat-mint',
                        ],
                        [
                            'title' => 'Lowest Personal Score',
                            'value' => "$lowestScore%",
                            'icon' => 'bx-trending-down',
                            'class' => 'stat-red',
                        ],
                    ];
                @endphp

                @foreach ($statItems as $item)
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card d-flex align-items-center justify-content-between">
                            <div>
                                <span
                                    class="small fw-semibold text-uppercase text-muted label-spacing">{{ $item['title'] }}</span>
                                <h3 class="fw-bold m-0 mt-1 {{ $item['class'] }}">{{ $item['value'] }}</h3>
                            </div>
                            <div class="stat-icon icon-{{ $item['class'] }}">
                                <i class="bx {{ $item['icon'] }} fs-4"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Filter --}}
            <div class="p-3 mb-4 rounded-3 border filter-bar">
                <div class="row g-3">
                    <div class="col-12 col-sm">
                        <div class="custom-input-group">
                            <i class="bx bx-search fs-5 text-light-custom"></i>
                            <input id="search-input" type="text" class="form-control custom-control"
                                placeholder="Filter your quiz entries by title..." />
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto min-w-select">
                        <div class="custom-input-group position-relative">
                            <i class="bx bx-category fs-5 text-light-custom"></i>
                            <select id="category-filter" class="form-select custom-control pe-5 fw-semibold">
                                <option value="all">All Categories</option>
                                <option value="programming">Programming</option>
                                <option value="mathematics">Mathematics</option>
                                <option value="science">Science</option>
                                <option value="history">History</option>
                                <option value="general knowledge">General Knowledge</option>
                            </select>
                            <i class="bx bx-chevron-down position-absolute me-3 pe-none fs-4 text-light-custom"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quiz Performance Outputs --}}
            <div class="row g-4" id="quiz-cards-grid">
                @forelse($attempts as $attempt)
                    @php
                        $scorePercentage = round($attempt->score_percentage, 1);
                        $scoreTheme =
                            $scorePercentage >= 85
                                ? 'theme-high'
                                : ($scorePercentage < 75
                                    ? 'theme-low'
                                    : 'theme-normal');

                        $categoryClean = strtolower($attempt->quiz->category ?? '');
                        $badgeClass = match ($categoryClean) {
                            'mathematics' => 'badge-mathematics',
                            'science' => 'badge-science',
                            'history' => 'badge-history',
                            'general knowledge' => 'badge-gk',
                            'programming' => 'badge-programming',
                            default => 'badge-default',
                        };
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4 quiz-card-wrapper" data-category="{{ $categoryClean }}">
                        <div class="result-report-card d-flex flex-column justify-content-between {{ $scoreTheme }}">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span
                                        class="subject-txt {{ $badgeClass }}">{{ $attempt->quiz->category ?? 'General' }}</span>
                                </div>

                                <h3 class="h5 fw-bold mb-3 row-title text-dark-custom">
                                    {{ $attempt->quiz->title ?? 'Untitled Assessment' }}
                                </h3>

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="progress progress-container">
                                        <div class="progress-bar custom-bar" role="progressbar"
                                            style="width: {{ $scorePercentage }}%;"
                                            aria-valuenow="{{ $scorePercentage }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <span class="small fw-bold score-badge">{{ $scorePercentage }}%</span>
                                </div>

                                <div class="metric-accent-indicator d-flex flex-column gap-2 mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="small text-light-custom"><i
                                                class="bx bx-badge-check me-1 align-middle"></i> Score obtained:</span>
                                        <span class="small fw-bold text-dark-custom">
                                            {{ $attempt->correct_answers }} / {{ $attempt->total_marks }} Correct
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="small text-light-custom"><i
                                                class="bx bx-time-five me-1 align-middle"></i> Time Elapsed:</span>
                                        <span class="small fw-semibold text-mid-custom">
                                            {{ $attempt->time_taken_seconds ? gmdate('i:s', $attempt->time_taken_seconds) . ' mins' : '00:00' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-top d-flex align-items-center justify-content-between sub-footer">
                                <span class="small text-uppercase fw-semibold sub-label">Submitted On</span>
                                <span class="small fw-bold text-mid-custom">
                                    {{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y') : $attempt->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="text-muted mb-2"><i class="bx bx-folder-open fs-1"></i></div>
                        <p class="text-muted">You haven't completed any quiz assessments yet.</p>
                    </div>
                @endforelse

                {{-- Client-Side Filter Empty State Placeholder --}}
                <div id="no-filter-results" class="col-12 text-center py-5 d-none">
                    <div class="text-muted mb-2">
                        <i class="bx bx-search-alt fs-1 animate-bounce"></i>
                    </div>
                    <h5 class="fw-bold text-dark-custom mb-1">No matching results found</h5>
                    <p class="text-muted small">Try refining your search queries or category filters.</p>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer
        class="p-3 border-top w-100 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 mt-4 footer-container">
        <span class="small text-light-custom">
            Showing <b>{{ $attempts->firstItem() ?? 0 }}</b>-<b>{{ $attempts->lastItem() ?? 0 }}</b> of
            <b>{{ $attempts->total() }}</b> personal items
        </span>
        <div>
            {{ $attempts->links('pagination::bootstrap-5') }}
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const cardWrappers = document.querySelectorAll('.quiz-card-wrapper');
            const noResultsNode = document.getElementById('no-filter-results');

            function executeClientFiltering() {
                const searchQuery = searchInput.value.toLowerCase().trim();
                const selectedCategory = categoryFilter.value.toLowerCase();
                let visibleCount = 0;

                cardWrappers.forEach(wrapper => {
                    const titleText = wrapper.querySelector('.row-title').textContent.toLowerCase();
                    const elementCategory = wrapper.getAttribute('data-category').toLowerCase();

                    const matchedSearch = titleText.includes(searchQuery);
                    const matchedCategory = selectedCategory === 'all' || elementCategory ===
                        selectedCategory;
                    const isVisible = matchedSearch && matchedCategory;

                    // Toggle visibility
                    wrapper.classList.toggle('d-none', !isVisible);

                    if (isVisible) {
                        visibleCount++;
                    }
                });

                // Handle the display state of the empty notification banner container
                if (noResultsNode) {
                    noResultsNode.classList.toggle('d-none', visibleCount > 0);
                }
            }

            searchInput.addEventListener('input', executeClientFiltering);
            categoryFilter.addEventListener('change', executeClientFiltering);

            // Entrance Cascading View Animations
            cardWrappers.forEach((wrapper, index) => {
                setTimeout(() => {
                    const cardNode = wrapper.querySelector('.result-report-card');
                    if (cardNode) {
                        cardNode.style.opacity = '1';
                        cardNode.style.transform = 'translateY(0)';
                    }
                }, index * 60);
            });
        });
    </script>

</body>

</html>
