<link rel="stylesheet" href="{{ asset('css/theme.css') }}">

<style>
    .footer-dark {
        background: var(--text-dark);
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .foot-logo-mark {
        width: 28px;
        height: 28px;
        background: var(--coral);
        border-radius: 7px;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .footer-link {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
        text-decoration: none;
        transition: 0.2s;
    }

    .footer-link:hover {
        color: #fff;
    }
</style>

<footer class="py-4 mt-5 footer-dark">
    <div class="container-fluid px-md-5">

        <div class="row align-items-center g-3">

            {{-- Logo  --}}
            <div class="col-md-6 text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                    <div class="foot-logo-mark">
                        <i class='bx bxs-graduation text-white'></i>
                    </div>
                    <span class="text-white fw-bold">
                        QuizMaster
                    </span>
                </div>
            </div>

            {{-- Links  --}}
            <div class="col-md-6">
                <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-3">
                    <a href="#" class="footer-link">
                        Privacy Policy
                    </a>
                    <a href="#" class="footer-link">
                        Terms of Service
                    </a>
                    <a href="#" class="footer-link">
                        Help Center
                    </a>
                    <a href="#" class="footer-link">
                        Contact Us
                    </a>
                </div>
            </div>

        </div>

    </div>
</footer>
