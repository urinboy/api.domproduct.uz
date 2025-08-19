<!-- Loading Overlay Component -->
<div class="loading-container" id="loadingOverlay" style="display: none;">
    <div class="loading-spinner">
        <div class="spinner-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <p class="loading-text">{{ __('Yuklanmoqda...') }}</p>
</div>

<script>
// Loading overlay management
class LoadingManager {
    constructor() {
        this.overlay = document.getElementById('loadingOverlay');
        this.isShowing = false;
        this.queue = 0;
    }

    show(message = '{{ __("Yuklanmoqda...") }}') {
        this.queue++;
        
        if (!this.isShowing) {
            this.isShowing = true;
            const textElement = this.overlay.querySelector('.loading-text');
            if (textElement) {
                textElement.textContent = message;
            }
            
            this.overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Fade in animation
            setTimeout(() => {
                this.overlay.style.opacity = '1';
            }, 10);
        }
    }

    hide() {
        this.queue = Math.max(0, this.queue - 1);
        
        if (this.queue === 0 && this.isShowing) {
            this.isShowing = false;
            this.overlay.style.opacity = '0';
            
            setTimeout(() => {
                this.overlay.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
    }

    hideAll() {
        this.queue = 0;
        this.hide();
    }
}

// Global loading manager instance
const loadingManager = new LoadingManager();

// Global functions for easy access
function showLoading(message) {
    loadingManager.show(message);
}

function hideLoading() {
    loadingManager.hide();
}

function hideAllLoading() {
    loadingManager.hideAll();
}

// Auto-hide loading on page load
document.addEventListener('DOMContentLoaded', function() {
    hideAllLoading();
});

// Show loading on form submissions
document.addEventListener('submit', function(e) {
    if (e.target.tagName === 'FORM' && !e.target.hasAttribute('data-no-loading')) {
        showLoading('{{ __("Ma\'lumotlar yuborilmoqda...") }}');
    }
});

// Show loading on AJAX requests (if jQuery is available)
if (typeof $ !== 'undefined') {
    $(document).ajaxStart(function() {
        showLoading();
    }).ajaxStop(function() {
        hideLoading();
    });
}

// Show loading on fetch requests
if (typeof fetch !== 'undefined') {
    const originalFetch = fetch;
    fetch = function(...args) {
        showLoading();
        return originalFetch(...args).finally(() => {
            hideLoading();
        });
    };
}
</script>
