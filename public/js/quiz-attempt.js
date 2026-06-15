(() => {
    const config = document.getElementById('quiz-config').dataset;
    let timeLeft = parseInt(config.timeLeft);
    const TOTAL_QUESTIONS = parseInt(config.totalQuestions);
    const SAVE_URL = config.saveUrl;
    const AVAILABLE_URL = config.availableUrl;
    const SUBMIT_FORM = document.getElementById('submit-form');
    const LOCK_URL = location.href;

    let allowExit = false;

    function trapHistory() {
        history.pushState({ locked: true }, '', LOCK_URL);
    }
    trapHistory();
    trapHistory();

    window.addEventListener('popstate', () => {
        if (!allowExit) trapHistory();
    });

    document.addEventListener('keydown', (e) => {
        if (allowExit) return;
        const isTypingField = ['INPUT', 'TEXTAREA'].includes(e.target.tagName);
        if ((e.key === 'Backspace' && !isTypingField) || (e.altKey && ['ArrowLeft', 'ArrowRight'].includes(e.key))) {
            e.preventDefault();
        }
    });

    window.addEventListener('beforeunload', (e) => {
        if (allowExit) return;
        e.preventDefault();
        e.returnValue = '';
    });

    function leavePage() {
        allowExit = true;
    }

    const countdownEl = document.getElementById('countdown');
    const timerContainer = document.getElementById('timer-container');

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        if (timeLeft < 300) {
            timerContainer.classList.add('timer-critical');
        }

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            countdownEl.textContent = '00:00';
            handleAutoSubmit();
            return;
        }
        timeLeft--;
    }

    const timerInterval = setInterval(updateTimer, 1000);

    function handleAutoSubmit() {
        leavePage();
        document.body.style.pointerEvents = 'none';
        document.body.style.opacity = '0.6';
        alert('Time is up! Your exam is being submitted automatically.');

        const checkedRadio = document.querySelector('input[name="selected_option"]:checked');
        if (checkedRadio) {
            const formData = new FormData(document.getElementById('answer-form'));
            formData.set('action', 'save');
            fetch(SAVE_URL, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(executeFinalSubmit, executeFinalSubmit);
        } else {
            executeFinalSubmit();
        }
    }

    function executeFinalSubmit() {
        const token = SUBMIT_FORM.querySelector('input[name="_token"]').value;
        fetch(SUBMIT_FORM.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ force_submit: 1 })
        })
            .then(r => r.json())
            .then(data => {
                window.location.href = (data.success && data.redirect_url) ? data.redirect_url : location.href;
            })
            .catch(() => {
                window.location.href = AVAILABLE_URL;
            });
    }

    function bindOptionCards() {
        document.querySelectorAll('.option-card').forEach(card => {
            card.addEventListener('click', function () {
                document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');

                const radio = this.querySelector('.radio-input');
                if (radio) radio.checked = true;

                const questionId = document.querySelector('input[name="question_id"]').value;
                const chip = document.getElementById('chip-' + questionId);
                if (chip) {
                    chip.className = 'btn p-0 question-chip chip-answered';
                    chip.dataset.answered = '1';
                }
            });
        });
    }

    function submitAction(action) {
        const formEl = document.getElementById('answer-form');
        const formData = new FormData(formEl);
        formData.set('action', action);

        setNavDisabled(true);

        fetch(formEl.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                if (data.redirect_url) loadQuestion(data.redirect_url);
                else setNavDisabled(false);
            })
            .catch(() => {
                leavePage();
                formEl.submit();
            });
    }

    function jumpToQuestion(num) {
        document.getElementById('form-action').value = 'jump';
        document.getElementById('target-question').value = num;
        submitAction('jump');
    }

    function loadQuestion(url) {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.text();
            })
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newMain = doc.querySelector('main');
                if (!newMain) throw new Error('No <main> found');

                document.querySelector('main').innerHTML = newMain.innerHTML;
                bindOptionCards();
                setNavDisabled(false);
                history.replaceState({}, '', url);
            })
            .catch(() => {
                leavePage();
                window.location.href = url;
            });
    }

    function setNavDisabled(disabled) {
        document.querySelectorAll('.quiz-nav-btn').forEach(btn => btn.disabled = disabled);
    }

    function confirmSubmit() {
        const answeredChips = document.querySelectorAll('button[data-answered="1"]').length;
        const checkedRadio = document.querySelector('input[name="selected_option"]:checked');
        const totalAnswered = answeredChips + (checkedRadio ? 1 : 0);

        if (totalAnswered < 1) {
            alert('Please answer at least one question before submitting.');
            return;
        }

        const unanswered = TOTAL_QUESTIONS - totalAnswered;
        const msg = unanswered > 0
            ? `You have ${unanswered} unanswered question(s). Submit anyway?`
            : 'Are you sure you want to submit? This cannot be undone.';

        if (!confirm(msg)) return;

        leavePage();

        if (checkedRadio) {
            const formData = new FormData(document.getElementById('answer-form'));
            formData.set('action', 'save');
            fetch(document.getElementById('answer-form').action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(() => SUBMIT_FORM.submit())
                .catch(() => SUBMIT_FORM.submit());
        } else {
            SUBMIT_FORM.submit();
        }
    }

    window.submitAction = submitAction;
    window.jumpToQuestion = jumpToQuestion;
    window.confirmSubmit = confirmSubmit;

    bindOptionCards();
})();