document.addEventListener('DOMContentLoaded', () => {

    // Bootstrap Modal Instances
    const modalElement = document.getElementById('createQuizModal');
    const bootstrapModalInstance = modalElement ? new bootstrap.Modal(modalElement) : null;
    const deleteModalEl = document.getElementById('deleteConfirmModal');
    const deleteModalInstance = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

    // Quiz Form Elements
    const totalQuestionsInput = document.getElementById('totalQuestions');
    const dynamicQuestionsWrapper = document.getElementById('dynamicQuestionsWrapper');
    const uiQuestionCounter = document.getElementById('uiQuestionCounter');
    const createQuizForm = document.getElementById('createQuizForm');
    const quizTitleInput = document.getElementById('quizTitle');
    const quizDescInput = document.getElementById('quizDesc');
    const quizSubjectSelect = document.getElementById('quizSubject');
    const quizStartDateInput = document.getElementById('quizStartDate');
    const quizEndDateInput = document.getElementById('quizEndDate');
    const modalHeaderTitle = document.getElementById('modalHeaderTitle');
    const editQuizIdInput = document.getElementById('editQuizId');
    const submitModalBtn = document.getElementById('submitModalBtn');

    // Filter Selectors
    const filterForm = document.getElementById('filterControlForm');
    const searchInputField = document.getElementById('tableSearchInput');
    const categorySelectField = document.getElementById('filterCategorySelect');

    let selectedRowForDeletion = null;

    // AJAX Filter
    function submitFiltersAjax(url = null) {

        // search and category filter
        const searchQuery = searchInputField ? searchInputField.value.trim() : '';
        const selectedCategory = categorySelectField ? categorySelectField.value : 'all';

        // Determine request URL
        let requestUrl = url || (filterForm ? filterForm.action : '/quizzes');
        let target = new URL(requestUrl, window.location.origin);

        if (!url) {
            target.searchParams.set('search', searchQuery);
            target.searchParams.set('category', selectedCategory);
        }

        // Send AJAX request
        fetch(target, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => response.text())
            .then(htmlString => {
                const parser = new DOMParser();
                const responseDoc = parser.parseFromString(htmlString, 'text/html');

                const incomingTable = responseDoc.getElementById('table-workspace-container');
                const incomingMetrics = responseDoc.getElementById('metrics-container');

                if (incomingTable) {
                    document.getElementById('table-workspace-container').innerHTML = incomingTable.innerHTML;
                }
                if (incomingMetrics) {
                    document.getElementById('metrics-container').innerHTML = incomingMetrics.innerHTML;
                }

                window.history.pushState({}, '', target.toString());
            })
            .catch(error => console.error("Error executing dynamic filter pipeline:", error));
    }

    // Dropdown change filter 
    if (categorySelectField) {
        categorySelectField.addEventListener('change', () => {
            submitFiltersAjax();
        });
    }

    // Search Filter
    let searchTypingDebounceTimer;
    if (searchInputField) {
        searchInputField.addEventListener('input', () => {
            clearTimeout(searchTypingDebounceTimer);
            searchTypingDebounceTimer = setTimeout(() => {
                submitFiltersAjax();
            }, 300);
        });

        searchInputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitFiltersAjax();
            }
        });
    }

    // Pagination Handling
    document.addEventListener('click', (event) => {
        const pageLinkAnchor = event.target.closest('.ajax-page-link');
        if (pageLinkAnchor) {
            event.preventDefault();
            const targetPaginationUrl = pageLinkAnchor.getAttribute('href');
            submitFiltersAjax(targetPaginationUrl);
        }
    });

    // Generate And Manage Question Fields
    function updateQuestionFields(targetCount) {

        // Update Question
        if (targetCount < 0) targetCount = 0;
        if (totalQuestionsInput) totalQuestionsInput.value = targetCount;
        if (uiQuestionCounter) uiQuestionCounter.textContent = `(${targetCount})`;

        // Handle Empty State
        const placeholderBlock = document.getElementById('placeholderBlock');
        if (targetCount === 0) {
            if (placeholderBlock) placeholderBlock.style.display = 'block';
            if (dynamicQuestionsWrapper) dynamicQuestionsWrapper.innerHTML = '';
            return;
        }

        if (placeholderBlock) placeholderBlock.style.display = 'none';
        if (!dynamicQuestionsWrapper) return;

        const currentCount = dynamicQuestionsWrapper.children.length;

        // Add New Question
        if (targetCount > currentCount) {
            for (let i = currentCount; i < targetCount; i++) {
                const item = document.createElement('div');
                item.className = 'dynamic-question-item';
                item.setAttribute('data-index', i);
                item.innerHTML = `
                    <div class="question-card-header"><i class='bx bx-help-circle text-primary'></i> Question Position ${i + 1}</div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-dark mb-2">Question Statement</label>
                        <input type="text" class="form-control" placeholder="Enter question string description..." name="questions[${i}][question_text]" required />
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-dark mb-2">Options</label>
                        <div class="row g-3">
                            ${['A', 'B', 'C', 'D'].map(opt => `
                                <div class="col-12 col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">${opt}</span>
                                        <input type="text" class="form-control" placeholder="Option ${opt} Value" name="questions[${i}][option_${opt.toLowerCase()}]" required />
                                    </div>
                                </div>`).join('')}
                        </div>
                    </div>
                    <div class="p-3 correct-answer-select-wrapper">
                        <div class="row align-items-center g-2">
                            <div class="col-12 col-sm-4 col-md-3"><label class="form-label small fw-bold text-primary mb-0">Correct Option:</label></div>
                            <div class="col-12 col-sm-8 col-md-9">
                                <select class="form-select form-select-sm fw-semibold" name="questions[${i}][correct_option]" required>
                                    <option value="" disabled selected>Choose correct option</option>
                                    ${['A', 'B', 'C', 'D'].map(opt => `<option value="${opt}">Option ${opt}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                    </div>`;
                dynamicQuestionsWrapper.appendChild(item);
            }
        } else {
            for (let i = currentCount; i > targetCount; i--) {
                if (dynamicQuestionsWrapper.lastElementChild) {
                    dynamicQuestionsWrapper.lastElementChild.remove();
                }
            }
        }
    }

    // Count Total Question
    if (totalQuestionsInput) {
        totalQuestionsInput.addEventListener('input', (e) => {
            updateQuestionFields(parseInt(e.target.value) || 0);
        });
    }

    // Increase Button
    const incrementBtn = document.getElementById('incrementQuestionsBtn');
    if (incrementBtn) {
        incrementBtn.addEventListener('click', () => {
            const currentVal = totalQuestionsInput ? parseInt(totalQuestionsInput.value) : 0;
            updateQuestionFields((currentVal || 0) + 1);
        });
    }

    // Quiz table actions (Edit / Delete)
    const dashboardTable = document.getElementById('dashboardTable');
    if (dashboardTable) {
        dashboardTable.addEventListener('click', (e) => {
            const targetRow = e.target.closest('tr');
            if (!targetRow) return;
            const quizId = targetRow.getAttribute('data-id');

            // Edit Quiz Action
            if (e.target.closest('.btn-edit-row')) {
                if (createQuizForm) {
                    createQuizForm.classList.remove('was-validated');
                    createQuizForm.action = `/quizzes/${quizId}`;
                }
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                if (editQuizIdInput) editQuizIdInput.value = quizId;
                if (modalHeaderTitle) modalHeaderTitle.textContent = "Modify Quiz Details";
                if (submitModalBtn) submitModalBtn.textContent = "Save Changes";

                let methodInput = createQuizForm ? createQuizForm.querySelector('input[name="_method"]') : null;
                if (!methodInput && createQuizForm) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    createQuizForm.appendChild(methodInput);
                }

                // Load quiz data and populate form
                fetch(`/quizzes/${quizId}/edit`, {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            if (quizTitleInput) quizTitleInput.value = res.quiz.title;
                            if(quizDescInput) quizDescInput.value = res.quiz.desc;
                            if (quizSubjectSelect) quizSubjectSelect.value = res.quiz.category;
                            if (quizStartDateInput) quizStartDateInput.value = res.quiz.start_date || '';
                            if (quizEndDateInput) quizEndDateInput.value = res.quiz.end_date || '';

                            updateQuestionFields(res.questions.length);

                            res.questions.forEach((question, index) => {
                                const qTextEl = document.querySelector(`[name="questions[${index}][question_text]"]`);
                                const optAEl = document.querySelector(`[name="questions[${index}][option_a]"]`);
                                const optBEl = document.querySelector(`[name="questions[${index}][option_b]"]`);
                                const optCEl = document.querySelector(`[name="questions[${index}][option_c]"]`);
                                const optDEl = document.querySelector(`[name="questions[${index}][option_d]"]`);
                                const correctOptEl = document.querySelector(`[name="questions[${index}][correct_option]"]`);

                                if (qTextEl) qTextEl.value = question.question_text || '';
                                if (optAEl) optAEl.value = question.option_a || '';
                                if (optBEl) optBEl.value = question.option_b || '';
                                if (optCEl) optCEl.value = question.option_c || '';
                                if (optDEl) optDEl.value = question.option_d || '';
                                if (correctOptEl) correctOptEl.value = question.correct_option || '';
                            });

                            if (bootstrapModalInstance) bootstrapModalInstance.show();
                        } else {
                            alert(res.message || "Failed to load requested quiz properties.");
                        }
                    })
                    .catch(err => {
                        console.error("Error loading quiz data:", err);
                        alert("An error occurred while fetching quiz details.");
                    });

                // Delete quiz action
            } else if (e.target.closest('.btn-delete')) {
                selectedRowForDeletion = targetRow;
                if (deleteModalInstance) deleteModalInstance.show();
            }
        });
    }

    // Open create quiz modal
    const openNewQuizBtn = document.getElementById('openNewQuizBtn');
    if (openNewQuizBtn) {
        openNewQuizBtn.addEventListener('click', () => {
            if (createQuizForm) {
                createQuizForm.reset();
                createQuizForm.action = "/quizzes";
                const methodInput = createQuizForm.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();
            }

            if (editQuizIdInput) editQuizIdInput.value = "";
            if (modalHeaderTitle) modalHeaderTitle.textContent = "Create New Quiz";
            if (submitModalBtn) submitModalBtn.textContent = "Create Quiz";

            updateQuestionFields(0);
            if (bootstrapModalInstance) bootstrapModalInstance.show();
        });
    }

    //  Confirm quiz deletion
    const confirmDeleteBtn = document.getElementById('confirmDeleteRowBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            if (selectedRowForDeletion) {
                const quizId = selectedRowForDeletion.getAttribute('data-id');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch(`/quizzes/${quizId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            submitFiltersAjax();
                        } else {
                            alert(data.message || "Failed to delete target entry.");
                        }
                    })
                    .catch(err => console.error("Lifecycle runtime tracking error:", err));
            }
            if (deleteModalInstance) deleteModalInstance.hide();
        });
    }

    // Valisation Error 
    const validationBridge = document.getElementById('laravel-validation-bridge');
    if (validationBridge && validationBridge.getAttribute('data-has-errors') === 'true') {
        const oldId = validationBridge.getAttribute('data-old-id');
        const oldQuestionsCount = parseInt(validationBridge.getAttribute('data-old-questions-count')) || 0;

        // Reopen modal and rebuild question fields
        if (bootstrapModalInstance) bootstrapModalInstance.show();
        updateQuestionFields(oldQuestionsCount);

        // Restore edit mode configuration
        if (oldId && createQuizForm) {
            createQuizForm.action = `/quizzes/${oldId}`;
            if (!createQuizForm.querySelector('input[name="_method"]')) {
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                createQuizForm.appendChild(methodInput);
            }
            if (modalHeaderTitle) modalHeaderTitle.textContent = "Modify Quiz Details";
            if (submitModalBtn) submitModalBtn.textContent = "Save Changes";
        }
    }
});