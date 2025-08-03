import Alpine from 'alpinejs'

// Alpine.js ni global qilamiz
window.Alpine = Alpine

// Admin Panel Components
Alpine.data('sidebar', () => ({
    open: false,
    toggle() {
        this.open = !this.open
    },
    close() {
        this.open = false
    }
}))

Alpine.data('dropdown', () => ({
    open: false,
    toggle() {
        this.open = !this.open
    },
    close() {
        this.open = false
    }
}))

Alpine.data('modal', () => ({
    open: false,
    show() {
        this.open = true
        document.body.style.overflow = 'hidden'
    },
    hide() {
        this.open = false
        document.body.style.overflow = 'auto'
    }
}))

Alpine.data('confirmDelete', () => ({
    show: false,
    action: '',
    title: '',
    message: '',
    
    confirm(action, title = 'O\'chirish tasdiqi', message = 'Rostdan ham o\'chirmoqchimisiz?') {
        this.action = action
        this.title = title
        this.message = message
        this.show = true
    },
    
    execute() {
        if (this.action) {
            if (typeof this.action === 'string') {
                window.location.href = this.action
            } else if (typeof this.action === 'function') {
                this.action()
            }
        }
        this.cancel()
    },
    
    cancel() {
        this.show = false
        this.action = ''
        this.title = ''
        this.message = ''
    }
}))

Alpine.data('dataTable', () => ({
    search: '',
    sortBy: '',
    sortDirection: 'asc',
    perPage: 10,
    currentPage: 1,
    
    sort(column) {
        if (this.sortBy === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc'
        } else {
            this.sortBy = column
            this.sortDirection = 'asc'
        }
        this.currentPage = 1
    }
}))

Alpine.data('toast', () => ({
    messages: [],
    
    add(message, type = 'info', duration = 5000) {
        const id = Date.now()
        this.messages.push({ id, message, type })
        
        setTimeout(() => {
            this.remove(id)
        }, duration)
    },
    
    remove(id) {
        this.messages = this.messages.filter(msg => msg.id !== id)
    },
    
    success(message) {
        this.add(message, 'success')
    },
    
    error(message) {
        this.add(message, 'error')
    },
    
    warning(message) {
        this.add(message, 'warning')
    },
    
    info(message) {
        this.add(message, 'info')
    }
}))

// Language switcher
Alpine.data('languageSwitcher', () => ({
    currentLang: document.documentElement.lang || 'uz',
    
    switch(lang) {
        // AJAX request to switch language
        fetch('/admin/language/switch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ language: lang })
        }).then(() => {
            window.location.reload()
        })
    }
}))

// Initialize Alpine
Alpine.start()

// Global helper functions
window.adminHelpers = {
    // Format numbers
    formatNumber(num) {
        return new Intl.NumberFormat('uz-UZ').format(num)
    },
    
    // Format currency
    formatCurrency(amount, currency = 'UZS') {
        return new Intl.NumberFormat('uz-UZ', {
            style: 'currency',
            currency: currency
        }).format(amount)
    },
    
    // Format date
    formatDate(date) {
        return new Intl.DateTimeFormat('uz-UZ').format(new Date(date))
    },
    
    // Copy to clipboard
    copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Show toast message
            if (window.toast) {
                window.toast.success('Nusxa ko\'chirildi!')
            }
        })
    },
    
    // Debounce function
    debounce(func, wait) {
        let timeout
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout)
                func(...args)
            }
            clearTimeout(timeout)
            timeout = setTimeout(later, wait)
        }
    }
}

// Dark mode toggle (future feature)
Alpine.data('darkMode', () => ({
    dark: localStorage.getItem('darkMode') === 'true',
    
    toggle() {
        this.dark = !this.dark
        localStorage.setItem('darkMode', this.dark)
        
        if (this.dark) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    },
    
    init() {
        if (this.dark) {
            document.documentElement.classList.add('dark')
        }
    }
}))

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert[data-auto-hide]')
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0'
            setTimeout(() => {
                alert.remove()
            }, 300)
        }, 5000)
    })
    
    // Initialize tooltips (if using a tooltip library)
    // initTooltips()
    
    // Initialize any other admin functionality
    console.log('Admin panel initialized')
})
