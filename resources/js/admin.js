// AdminLTE Admin Panel JS - DOM Product
// jQuery va Bootstrap AdminLTE framework tomonidan yuklanadi

$(document).ready(function() {
    console.log('DOM Product Admin panel initialized');

    // Initialize admin features
    initAdminFeatures();
    initNotifications();
    initLanguageSwitcher();
    initThemeSwitcher();

    // Sidebar collapse/expand functionality
    $('[data-widget="pushmenu"]').on('click', function() {
        $('body').toggleClass('sidebar-collapse');
        localStorage.setItem('sidebar-collapsed', $('body').hasClass('sidebar-collapse'));
    });

    // Restore sidebar state
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        $('body').addClass('sidebar-collapse');
    }
});

function initAdminFeatures() {
    // Toast notifications auto-hide
    $('.toast').toast({
        autohide: true,
        delay: 5000
    });

    // Form validation enhancements
    $('form').on('submit', function() {
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    });

    // Smooth page transitions
    $('a:not([href^="#"]):not([href^="javascript:"]):not([data-toggle])').on('click', function(e) {
        const href = $(this).attr('href');
        if (href && href.includes('/admin/')) {
            e.preventDefault();
            $('body').fadeOut(200, function() {
                window.location.href = href;
            });
        }
    });

    // DataTables initialization if exists
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            responsive: true,
            language: {
                url: getDataTableLanguageUrl()
            }
        });
    }

    // Select2 initialization if exists
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    }
}

function initNotifications() {
    // Load notifications from API
    loadNotifications();

    // Mark notification as read
    $(document).on('click', '.notification-item', function() {
        const notificationId = $(this).data('id');
        if (notificationId) {
            markNotificationAsRead(notificationId);
        }
    });
}

function loadNotifications() {
    // Fetch notifications from API
    fetch('/api/admin/notifications', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        updateNotificationCount(data.unread_count);
        renderNotifications(data.notifications);
    })
    .catch(error => {
        console.error('Failed to load notifications:', error);
    });
}

function markNotificationAsRead(id) {
    fetch(`/api/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $(`.notification-item[data-id="${id}"]`).removeClass('unread');
            updateNotificationCount();
        }
    })
    .catch(error => {
        console.error('Failed to mark notification as read:', error);
    });
}

function updateNotificationCount(count) {
    const $badge = $('.notification-badge');
    if (count > 0) {
        $badge.text(count).show();
    } else {
        $badge.hide();
    }
}

function renderNotifications(notifications) {
    const $container = $('.notifications-container');
    if ($container.length === 0) return;

    $container.empty();

    if (notifications.length === 0) {
        $container.html('<div class="text-center p-3">No notifications</div>');
        return;
    }

    notifications.forEach(notification => {
        const $item = $(`
            <div class="notification-item ${notification.read ? '' : 'unread'}" data-id="${notification.id}">
                <div class="notification-content">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-text">${notification.message}</div>
                    <div class="notification-time">${notification.created_at}</div>
                </div>
            </div>
        `);
        $container.append($item);
    });
}

function initLanguageSwitcher() {
    // Language switcher smooth animations
    $('.language-switcher .dropdown').on('show.bs.dropdown', function() {
        $(this).find('.dropdown-menu').addClass('animate__fadeIn');
    });

    // Language switch handler
    $('.language-switch').on('click', function(e) {
        e.preventDefault();
        const lang = $(this).data('lang');
        switchLanguage(lang);
    });
}

function switchLanguage(code) {
    const url = new URL(window.location);
    url.searchParams.set('lang', code);

    // Show loading state
    $('.language-switcher .dropdown-toggle').html('<i class="fas fa-spinner fa-spin"></i>');

    // Redirect with language parameter
    window.location.href = url.toString();
}

function initThemeSwitcher() {
    const currentTheme = localStorage.getItem('admin-theme') || 'light';
    applyTheme(currentTheme);

    $('.theme-switcher').on('click', function() {
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        localStorage.setItem('admin-theme', newTheme);
        applyTheme(newTheme);
    });
}

function applyTheme(theme) {
    if (theme === 'dark') {
        $('body').addClass('dark-mode');
    } else {
        $('body').removeClass('dark-mode');
    }
}

// Dashboard specific functions
function initDashboard() {
    loadDashboardStats();
    initCharts();
}

function loadDashboardStats() {
    fetch('/api/admin/dashboard/stats', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        updateDashboardStats(data);
    })
    .catch(error => {
        console.error('Failed to load dashboard stats:', error);
    });
}

function updateDashboardStats(stats) {
    $('.stat-users').text(stats.users || 0);
    $('.stat-products').text(stats.products || 0);
    $('.stat-orders').text(stats.orders || 0);
    $('.stat-revenue').text(stats.revenue || 0);
}

function initCharts() {
    // Initialize Chart.js charts if Chart library is available
    if (typeof Chart !== 'undefined') {
        initSalesChart();
        initCategoriesChart();
    }
}

function initSalesChart() {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
}

function initCategoriesChart() {
    const ctx = document.getElementById('categoriesChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Electronics', 'Clothing', 'Books', 'Home & Garden'],
            datasets: [{
                data: [300, 150, 100, 80],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

// Product management functions
function initProductManagement() {
    initProductTable();
    initProductForm();
    initBulkActions();
}

function initProductTable() {
    if ($.fn.DataTable) {
        $('#productsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/api/admin/products/datatable',
            columns: [
                { data: 'checkbox', orderable: false, searchable: false },
                { data: 'image', orderable: false },
                { data: 'name' },
                { data: 'category' },
                { data: 'price' },
                { data: 'status' },
                { data: 'actions', orderable: false, searchable: false }
            ]
        });
    }
}

function initProductForm() {
    // Image upload preview
    $('.image-upload input[type="file"]').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.image-preview').html(`<img src="${e.target.result}" class="img-fluid">`);
            };
            reader.readAsDataURL(file);
        }
    });
}

function initBulkActions() {
    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.product-checkbox').prop('checked', this.checked);
        updateBulkActionButtons();
    });

    // Individual checkboxes
    $(document).on('change', '.product-checkbox', function() {
        updateBulkActionButtons();
    });

    // Bulk delete
    $('#bulkDelete').on('click', function() {
        const selected = $('.product-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selected.length === 0) {
            alert('Please select products to delete');
            return;
        }

        if (confirm('Are you sure you want to delete selected products?')) {
            bulkDeleteProducts(selected);
        }
    });
}

function updateBulkActionButtons() {
    const selected = $('.product-checkbox:checked').length;
    $('.bulk-actions').toggle(selected > 0);
}

function bulkDeleteProducts(ids) {
    fetch('/api/admin/products/bulk-delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload DataTable
            $('#productsTable').DataTable().ajax.reload();
            $('.product-checkbox').prop('checked', false);
            updateBulkActionButtons();

            // Show success message
            showToast('Products deleted successfully', 'success');
        } else {
            showToast('Failed to delete products', 'error');
        }
    })
    .catch(error => {
        console.error('Bulk delete failed:', error);
        showToast('Failed to delete products', 'error');
    });
}

// Utility functions
function getAuthToken() {
    return localStorage.getItem('auth_token') || '';
}

function getDataTableLanguageUrl() {
    const currentLang = document.documentElement.lang || 'en';
    const langUrls = {
        'uz': '/js/datatable-uz.json',
        'ru': '/js/datatable-ru.json',
        'en': '/js/datatable-en.json'
    };
    return langUrls[currentLang] || langUrls['en'];
}

function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast" role="alert">
            <div class="toast-header bg-${type}">
                <strong class="mr-auto text-white">Notification</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
                    <span>&times;</span>
                </button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;

    $('#toast-container').append(toastHtml);
    $('.toast').last().toast('show');
}

// Export functions for global use
window.adminFunctions = {
    initDashboard,
    initProductManagement,
    showToast,
    switchLanguage
};
