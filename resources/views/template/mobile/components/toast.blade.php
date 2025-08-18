<!-- Toast Notifications Component -->
<div class="toast-container" id="toastContainer">
    <!-- Toasts will be dynamically inserted here -->
</div>

<script>
// Toast notification system
class ToastManager {
    constructor() {
        this.container = document.getElementById('toastContainer');
        this.toasts = new Map();
    }

    show(type = 'info', message, title = null, duration = 5000) {
        const toast = this.createElement(type, message, title);
        const id = Date.now() + Math.random();
        
        this.toasts.set(id, toast);
        this.container.appendChild(toast);

        // Show animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        // Auto hide
        if (duration > 0) {
            setTimeout(() => {
                this.hide(id);
            }, duration);
        }

        return id;
    }

    hide(id) {
        const toast = this.toasts.get(id);
        if (toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
                this.toasts.delete(id);
            }, 300);
        }
    }

    createElement(type, message, title) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const titles = {
            success: '{{ __("Muvaffaqiyat") }}',
            error: '{{ __("Xatolik") }}',
            warning: '{{ __("Ogohlantirish") }}',
            info: '{{ __("Ma\'lumot") }}'
        };

        const toastTitle = title || titles[type] || titles.info;
        const icon = icons[type] || icons.info;

        toast.innerHTML = `
            <div class="toast-header">
                <div class="toast-title">
                    <i class="${icon}"></i>
                    ${toastTitle}
                </div>
                <button class="toast-close" onclick="toastManager.hide(${Date.now()})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-body">${message}</div>
        `;

        return toast;
    }

    success(message, title = null) {
        return this.show('success', message, title);
    }

    error(message, title = null) {
        return this.show('error', message, title);
    }

    warning(message, title = null) {
        return this.show('warning', message, title);
    }

    info(message, title = null) {
        return this.show('info', message, title);
    }
}

// Global toast manager instance
const toastManager = new ToastManager();

// Global functions for easy access
function showToast(type, message, title = null) {
    return toastManager.show(type, message, title);
}

function showSuccess(message, title = null) {
    return toastManager.success(message, title);
}

function showError(message, title = null) {
    return toastManager.error(message, title);
}

function showWarning(message, title = null) {
    return toastManager.warning(message, title);
}

function showInfo(message, title = null) {
    return toastManager.info(message, title);
}
</script>
