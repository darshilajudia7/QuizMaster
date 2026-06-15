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
    <link rel="stylesheet" href="{{ asset('css/student/quiz_available.css') }}">
</head>


<body>

    @include('student.navbar')

    <main class="p-3 p-md-4">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="mb-4">
                <h2 class="fw-bold m-0" style="color: var(--text-dark);">Available Quizzes</h2>
                <p class="mt-2 mb-0" style="max-width: 600px; color: var(--text-light);">
                    Explore your active dashboard assessments. Filter by academic category or use search parameters to
                    jump back into testing parameters smoothly.
                </p>
            </div>

            {{-- Search & Category Filters --}}
            <div class="p-3 mb-4 rounded-3 border"
                style="background-color: var(--surface-light); border-color: var(--border);">
                <div class="row g-3">
                    <div class="col-12 col-sm">
                        <div class="custom-input-group">
                            <i class="bx bx-search fs-5" style="color: var(--text-light);"></i>
                            <input id="search-input" type="text" class="form-control custom-control"
                                placeholder="Search for titles..." />
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto" style="min-width: 240px;">
                        <div class="custom-input-group position-relative">
                            <i class="bx bx-category fs-5" style="color: var(--text-light);"></i>
                            <select id="category-filter" class="form-select custom-control pe-5 fw-semibold"
                                style="color: var(--text-mid);">
                                <option value="all">All Categories</option>
                                <option value="mathematics">Mathematics</option>
                                <option value="science">Science</option>
                                <option value="history">History</option>
                                <option value="general knowledge">General Knowledge</option>
                                <option value="programming">Programming</option>
                            </select>
                            <i class="bx bx-chevron-down position-absolute me-3 pe-none fs-4"
                                style="color: var(--text-light);"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert"
                    style="border-radius: var(--r-sm);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-error-circle fs-4"></i>
                        <div>
                            <strong class="d-block">Failed to Initialize Quiz</strong>
                            <span class="small opacity-90">{{ session('error') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Show Quiz --}}
            <div class="row g-4" id="quiz-grid">
                @forelse($quizzes as $quiz)
                    @php
                        $badgeClass = match ($quiz->category) {
                            'Mathematics' => 'badge-mathematics',
                            'Science' => 'badge-science',
                            'History' => 'badge-history',
                            'General Knowledge' => 'badge-gk',
                            'Programming' => 'badge-programming',
                            default => 'badge-default',
                        };
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4 quiz-card-wrapper"
                        data-category="{{ strtolower($quiz->category) }}">
                        <div class="quiz-card d-flex flex-column h-100">
                            <div class="mb-2">
                                <span class="subject-txt {{ $badgeClass }}">
                                    {{ $quiz->category }}
                                </span>
                            </div>

                            <h3 class="h5 fw-bold mb-3" style="color: var(--text-dark);">{{ $quiz->title }}</h3>

                            <div class="metric-stack d-flex flex-column gap-2 mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bx bx-file fs-5" style="color: var(--text-light);"></i>
                                    <span class="small fw-medium"
                                        style="color: var(--text-mid);">{{ $quiz->total_questions }} Questions</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bx bx-time-five fs-5" style="color: var(--amber);"></i>
                                    <span class="small fw-bold"
                                        style="color: var(--text-dark);">{{ $quiz->total_minutes }} Mins</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2"
                                style="border-color: var(--border) !important;">
                                <div class="d-flex flex-column small">
                                    <span class="text-uppercase fw-semibold"
                                        style="font-size: 10px; color: var(--text-muted);">Quiz Window</span>
                                    <span class="fw-bold"
                                        style="color: var(--success);">{{ \Carbon\Carbon::parse($quiz->start_date)->format('M d, Y') }}</span>
                                    <span class="fw-bold"
                                        style="color: var(--danger);">{{ \Carbon\Carbon::parse($quiz->end_date)->format('M d, Y') }}</span>
                                </div>

                                <form method="POST"
                                    action="{{ route('student.quiz.start', ['quizId' => $quiz->id]) }}">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary-custom px-4 py-2 btn-sm fw-semibold text-nowrap">
                                        Start Quiz
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @empty

                    <div class="col-12 text-center py-5 my-4" id="empty-db-state">
                        <div class="mb-3">
                            <i class="bx bx-layer-minus display-3"
                                style="color: var(--text-light); opacity: 0.6;"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--text-dark);">No Active Quizzes</h4>
                        <p class="text-muted mx-auto" style="max-width: 360px;">There are currently no dashboard
                            assessments scheduled or active within this timeframe.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <footer
        class="p-3 border-top w-100 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 mt-4"
        style="background-color: var(--surface); border-color: var(--border) !important;">

        {{-- Pagination --}}
        <span class="small" style="color: var(--text-light);">
            Showing <b id="visible-start">{{ $quizzes->firstItem() ?? 0 }}</b>-<b
                id="visible-end">{{ $quizzes->lastItem() ?? 0 }}</b> of <b
                id="total-count">{{ $quizzes->total() }}</b> items
        </span>

        {{-- Pagination navigation --}}
        @if ($quizzes->hasPages())
            <div class="d-flex align-items-center gap-2" id="pagination-controls">

                @if ($quizzes->onFirstPage())
                    <button class="btn btn-sm border d-flex align-items-center gap-1 text-secondary"
                        style="background-color: var(--surface-light); border-color: var(--border);" disabled>
                        <i class="bx bx-chevron-left fs-5"></i>
                        <span class="small d-none d-sm-inline">Previous</span>
                    </button>
                @else
                    <a href="{{ $quizzes->previousPageUrl() }}"
                        class="btn btn-sm border d-flex align-items-center gap-1 bg-white"
                        style="border-color: var(--border); color: var(--text-mid);">
                        <i class="bx bx-chevron-left fs-5"></i>
                        <span class="small d-none d-sm-inline">Previous</span>
                    </a>
                @endif

                {{-- Page number links --}}
                <div class="d-flex align-items-center gap-1">
                    @foreach ($quizzes->getUrlRange(1, $quizzes->lastPage()) as $page => $url)
                        @if ($page == $quizzes->currentPage())
                            <button
                                class="btn btn-sm btn-primary-custom d-flex align-items-center justify-content-center"
                                style="width: 2.25rem; height: 2.25rem;">{{ $page }}</button>
                        @else
                            @if ($page == 1 || $page == $quizzes->lastPage() || abs($page - $quizzes->currentPage()) <= 1)
                                <a href="{{ $url }}"
                                    class="btn btn-sm btn-light border-0 d-flex align-items-center justify-content-center"
                                    style="width: 2.25rem; height: 2.25rem; color: var(--text-mid); background: transparent;">{{ $page }}</a>
                            @elseif($page == 2 || $page == $quizzes->lastPage() - 1)
                                <span class="px-1 small" style="color: var(--text-muted);">...</span>
                            @endif
                        @endif
                    @endforeach
                </div>

                @if ($quizzes->hasMorePages())
                    <a href="{{ $quizzes->nextPageUrl() }}"
                        class="btn btn-sm border d-flex align-items-center gap-1 bg-white"
                        style="border-color: var(--border); color: var(--text-mid);">
                        <span class="small d-none d-sm-inline">Next</span>
                        <i class="bx bx-chevron-right fs-5"></i>
                    </a>
                @else
                    <button class="btn btn-sm border d-flex align-items-center gap-1 text-secondary"
                        style="background-color: var(--surface-light); border-color: var(--border);" disabled>
                        <span class="small d-none d-sm-inline">Next</span>
                        <i class="bx bx-chevron-right fs-5"></i>
                    </button>
                @endif

            </div>
        @endif
    </footer>

    <script>
        // Filter Elements
        const searchInput = document.getElementById('search-input');
        const categoryFilter = document.getElementById('category-filter');
        const cardWrappers = document.querySelectorAll('.quiz-card-wrapper');

        // Pagination Elements
        const startCountEl = document.getElementById('visible-start');
        const endCountEl = document.getElementById('visible-end');
        const paginationControls = document.getElementById('pagination-controls');

        const baseStart = parseInt(startCountEl ? startCountEl.textContent : 0);

        function filterCards() {
            const searchQuery = searchInput.value.toLowerCase().trim();
            const selectedCategory = categoryFilter.value.toLowerCase();

            let customVisibleCount = 0;

            // Filter Quiz
            cardWrappers.forEach(wrapper => {
                const title = wrapper.querySelector('h3').textContent.toLowerCase();
                const cardCategory = wrapper.getAttribute('data-category');

                const matchesSearch = title.includes(searchQuery);
                const matchesCategory = selectedCategory === 'all' || cardCategory === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    wrapper.classList.remove('d-none');
                    customVisibleCount++;
                } else {
                    wrapper.classList.add('d-none');
                }
            });

            // Update Pagination
            if (startCountEl && endCountEl) {
                if (searchQuery !== '' || selectedCategory !== 'all') {
                    startCountEl.textContent = customVisibleCount > 0 ? baseStart : 0;
                    endCountEl.textContent = customVisibleCount > 0 ? (baseStart + customVisibleCount - 1) : 0;

                    if (paginationControls) {
                        paginationControls.style.opacity = '0.3';
                        paginationControls.style.pointerEvents = 'none';
                    }
                } else {
                    startCountEl.textContent = "{{ $quizzes->firstItem() ?? 0 }}";
                    endCountEl.textContent = "{{ $quizzes->lastItem() ?? 0 }}";

                    if (paginationControls) {
                        paginationControls.style.opacity = '1';
                        paginationControls.style.pointerEvents = 'auto';
                    }
                }
            }

            // Show / Hide No Results Message
            let noResultMsg = document.getElementById('no-results-message');
            if (customVisibleCount === 0) {
                if (!noResultMsg && !document.getElementById('empty-db-state')) {
                    noResultMsg = document.createElement('div');
                    noResultMsg.id = 'no-results-message';
                    noResultMsg.className = 'col-12 text-center py-5 my-4';
                    noResultMsg.innerHTML = `
                    <div class="mb-3">
                        <i class="bx bx-search-alt display-3" style="color: var(--text-light); opacity: 0.6;"></i>
                    </div>
                    <h4 class="fw-bold" style="color: var(--text-dark);">No Matches Found</h4>
                    <p class="text-muted mx-auto" style="max-width: 360px;">We couldn't find any active quizzes matching your parameters. Try tweaking your search words.</p>
                `;
                    document.getElementById('quiz-grid').appendChild(noResultMsg);
                }
            } else if (noResultMsg) {
                noResultMsg.remove();
            }
        }

        // Filter Event Listeners
        searchInput.addEventListener('input', filterCards);
        categoryFilter.addEventListener('change', filterCards);

        document.addEventListener('DOMContentLoaded', () => {
            cardWrappers.forEach((wrapper, index) => {
                const card = wrapper.querySelector('.quiz-card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(16px)';
                    setTimeout(() => {
                        card.style.transition =
                            'all var(--transition-slow, 0.4s) cubic-bezier(0.16, 1, 0.3, 1)';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 60);
                }
            });
        });
    </script>

</body>

</html>
