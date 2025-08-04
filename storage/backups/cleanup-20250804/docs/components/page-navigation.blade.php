<!-- Page Navigation Component -->
<div class="page-navigation">
    <div class="nav-container">
        @if(isset($previousPage))
        <a href="{{ $previousPage['url'] }}" class="nav-btn prev-btn">
            <i class="fas fa-arrow-left"></i>
            <div class="nav-content">
                <span class="nav-label" data-translate="previous-page">Previous</span>
                <span class="nav-title">{{ $previousPage['title'] }}</span>
            </div>
        </a>
        @else
        <div class="nav-btn prev-btn disabled">
            <i class="fas fa-arrow-left"></i>
            <div class="nav-content">
                <span class="nav-label" data-translate="no-previous">No Previous Page</span>
            </div>
        </div>
        @endif

        @if(isset($nextPage))
        <a href="{{ $nextPage['url'] }}" class="nav-btn next-btn">
            <div class="nav-content">
                <span class="nav-label" data-translate="next-page">Next</span>
                <span class="nav-title">{{ $nextPage['title'] }}</span>
            </div>
            <i class="fas fa-arrow-right"></i>
        </a>
        @else
        <div class="nav-btn next-btn disabled">
            <div class="nav-content">
                <span class="nav-label" data-translate="no-next">No Next Page</span>
            </div>
            <i class="fas fa-arrow-right"></i>
        </div>
        @endif
    </div>

    <!-- Back to Top Button -->
    <div class="back-to-top">
        <button onclick="scrollToTop()" class="back-to-top-btn" title="Back to Top">
            <i class="fas fa-chevron-up"></i>
            <span data-translate="back-to-top">Back to Top</span>
        </button>
    </div>
</div>

<style>
    .page-navigation {
        margin: 4rem 0 2rem;
        padding: 2rem 0;
        border-top: 2px solid var(--docs-border);
        background: linear-gradient(135deg, rgba(8, 124, 54, 0.02) 0%, rgba(8, 124, 54, 0.01) 100%);
    }

    .nav-container {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .nav-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem 2rem;
        background: var(--docs-content-bg);
        border: 2px solid var(--docs-border);
        border-radius: var(--radius-lg);
        text-decoration: none;
        color: var(--text-primary);
        transition: var(--transition-medium);
        flex: 1;
        max-width: 400px;
        position: relative;
        overflow: hidden;
    }

    .nav-btn:not(.disabled):hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(8, 124, 54, 0.15);
    }

    .nav-btn.disabled {
        background: var(--docs-bg);
        color: var(--text-muted);
        border-color: var(--docs-border);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .prev-btn {
        justify-content: flex-start;
    }

    .next-btn {
        justify-content: flex-end;
        text-align: right;
    }

    .nav-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .nav-label {
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
    }

    .nav-title {
        font-size: 1.125rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .nav-btn i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Back to Top */
    .back-to-top {
        display: flex;
        justify-content: center;
        padding-top: 1rem;
        border-top: 1px solid var(--docs-border);
    }

    .back-to-top-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition-fast);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .back-to-top-btn:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .back-to-top-btn i {
        font-size: 0.875rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-navigation {
            margin: 2rem 0 1rem;
            padding: 1rem 0;
        }

        .nav-container {
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .nav-btn {
            padding: 1rem 1.5rem;
            max-width: none;
        }

        .nav-title {
            font-size: 1rem;
        }

        .nav-label {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .nav-btn {
            padding: 1rem;
            gap: 0.75rem;
        }

        .nav-btn i {
            font-size: 1rem;
        }
    }
</style>

<script>
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Show/hide back to top button based on scroll
    window.addEventListener('scroll', function() {
        const backToTop = document.querySelector('.back-to-top');
        if (backToTop) {
            if (window.scrollY > 300) {
                backToTop.style.opacity = '1';
                backToTop.style.visibility = 'visible';
            } else {
                backToTop.style.opacity = '0';
                backToTop.style.visibility = 'hidden';
            }
        }
    });

    // Add navigation translations
    const navTranslations = {
        uz: {
            'previous-page': 'Oldingi',
            'next-page': 'Keyingi',
            'no-previous': 'Oldingi sahifa yo\'q',
            'no-next': 'Keyingi sahifa yo\'q',
            'back-to-top': 'Yuqoriga qaytish'
        },
        en: {
            'previous-page': 'Previous',
            'next-page': 'Next',
            'no-previous': 'No Previous Page',
            'no-next': 'No Next Page',
            'back-to-top': 'Back to Top'
        },
        ru: {
            'previous-page': 'Предыдущая',
            'next-page': 'Следующая',
            'no-previous': 'Нет предыдущей страницы',
            'no-next': 'Нет следующей страницы',
            'back-to-top': 'Наверх'
        }
    };

    // Merge with existing translations if they exist
    if (typeof translations !== 'undefined') {
        Object.keys(navTranslations).forEach(lang => {
            if (translations[lang]) {
                Object.assign(translations[lang], navTranslations[lang]);
            }
        });
    }
</script>
