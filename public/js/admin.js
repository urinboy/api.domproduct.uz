/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/css/admin.css":
/*!*********************************!*\
  !*** ./resources/css/admin.css ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/js/admin.js":
/*!*******************************!*\
  !*** ./resources/js/admin.js ***!
  \*******************************/
/***/ (() => {

// AdminLTE Admin Panel JS - DOM Product
// jQuery va Bootstrap AdminLTE framework tomonidan yuklanadi

$(document).ready(function () {
  console.log('DOM Product Admin panel initialized');

  // Initialize admin features
  initAdminFeatures();
  initNotifications();
  initLanguageSwitcher();
  initThemeSwitcher();

  // Sidebar collapse/expand functionality
  $('[data-widget="pushmenu"]').on('click', function () {
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
  $('form').on('submit', function () {
    var $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
  });

  // Smooth page transitions
  $('a:not([href^="#"]):not([href^="javascript:"]):not([data-toggle])').on('click', function (e) {
    var href = $(this).attr('href');
    if (href && href.includes('/admin/')) {
      e.preventDefault();
      $('body').fadeOut(200, function () {
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
  $(document).on('click', '.notification-item', function () {
    var notificationId = $(this).data('id');
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
  }).then(function (response) {
    return response.json();
  }).then(function (data) {
    updateNotificationCount(data.unread_count);
    renderNotifications(data.notifications);
  })["catch"](function (error) {
    console.error('Failed to load notifications:', error);
  });
}
function markNotificationAsRead(id) {
  fetch("/api/admin/notifications/".concat(id, "/read"), {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      'X-Requested-With': 'XMLHttpRequest'
    }
  }).then(function (response) {
    return response.json();
  }).then(function (data) {
    if (data.success) {
      $(".notification-item[data-id=\"".concat(id, "\"]")).removeClass('unread');
      updateNotificationCount();
    }
  })["catch"](function (error) {
    console.error('Failed to mark notification as read:', error);
  });
}
function updateNotificationCount(count) {
  var $badge = $('.notification-badge');
  if (count > 0) {
    $badge.text(count).show();
  } else {
    $badge.hide();
  }
}
function renderNotifications(notifications) {
  var $container = $('.notifications-container');
  if ($container.length === 0) return;
  $container.empty();
  if (notifications.length === 0) {
    $container.html('<div class="text-center p-3">No notifications</div>');
    return;
  }
  notifications.forEach(function (notification) {
    var $item = $("\n            <div class=\"notification-item ".concat(notification.read ? '' : 'unread', "\" data-id=\"").concat(notification.id, "\">\n                <div class=\"notification-content\">\n                    <div class=\"notification-title\">").concat(notification.title, "</div>\n                    <div class=\"notification-text\">").concat(notification.message, "</div>\n                    <div class=\"notification-time\">").concat(notification.created_at, "</div>\n                </div>\n            </div>\n        "));
    $container.append($item);
  });
}
function initLanguageSwitcher() {
  // Language switcher smooth animations
  $('.language-switcher .dropdown').on('show.bs.dropdown', function () {
    $(this).find('.dropdown-menu').addClass('animate__fadeIn');
  });

  // Language switch handler
  $('.language-switch').on('click', function (e) {
    e.preventDefault();
    var lang = $(this).data('lang');
    switchLanguage(lang);
  });
}
function switchLanguage(code) {
  var url = new URL(window.location);
  url.searchParams.set('lang', code);

  // Show loading state
  $('.language-switcher .dropdown-toggle').html('<i class="fas fa-spinner fa-spin"></i>');

  // Redirect with language parameter
  window.location.href = url.toString();
}
function initThemeSwitcher() {
  var currentTheme = localStorage.getItem('admin-theme') || 'light';
  applyTheme(currentTheme);
  $('.theme-switcher').on('click', function () {
    var newTheme = currentTheme === 'light' ? 'dark' : 'light';
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
  }).then(function (response) {
    return response.json();
  }).then(function (data) {
    updateDashboardStats(data);
  })["catch"](function (error) {
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
  var ctx = document.getElementById('salesChart');
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
  var ctx = document.getElementById('categoriesChart');
  if (!ctx) return;
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Electronics', 'Clothing', 'Books', 'Home & Garden'],
      datasets: [{
        data: [300, 150, 100, 80],
        backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(75, 192, 192)']
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
      columns: [{
        data: 'checkbox',
        orderable: false,
        searchable: false
      }, {
        data: 'image',
        orderable: false
      }, {
        data: 'name'
      }, {
        data: 'category'
      }, {
        data: 'price'
      }, {
        data: 'status'
      }, {
        data: 'actions',
        orderable: false,
        searchable: false
      }]
    });
  }
}
function initProductForm() {
  // Image upload preview
  $('.image-upload input[type="file"]').on('change', function () {
    var file = this.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('.image-preview').html("<img src=\"".concat(e.target.result, "\" class=\"img-fluid\">"));
      };
      reader.readAsDataURL(file);
    }
  });
}
function initBulkActions() {
  // Select all checkbox
  $('#selectAll').on('change', function () {
    $('.product-checkbox').prop('checked', this.checked);
    updateBulkActionButtons();
  });

  // Individual checkboxes
  $(document).on('change', '.product-checkbox', function () {
    updateBulkActionButtons();
  });

  // Bulk delete
  $('#bulkDelete').on('click', function () {
    var selected = $('.product-checkbox:checked').map(function () {
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
  var selected = $('.product-checkbox:checked').length;
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
    body: JSON.stringify({
      ids: ids
    })
  }).then(function (response) {
    return response.json();
  }).then(function (data) {
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
  })["catch"](function (error) {
    console.error('Bulk delete failed:', error);
    showToast('Failed to delete products', 'error');
  });
}

// Utility functions
function getAuthToken() {
  return localStorage.getItem('auth_token') || '';
}
function getDataTableLanguageUrl() {
  var currentLang = document.documentElement.lang || 'en';
  var langUrls = {
    'uz': '/js/datatable-uz.json',
    'ru': '/js/datatable-ru.json',
    'en': '/js/datatable-en.json'
  };
  return langUrls[currentLang] || langUrls['en'];
}
function showToast(message) {
  var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'info';
  var toastHtml = "\n        <div class=\"toast\" role=\"alert\">\n            <div class=\"toast-header bg-".concat(type, "\">\n                <strong class=\"mr-auto text-white\">Notification</strong>\n                <button type=\"button\" class=\"ml-2 mb-1 close\" data-dismiss=\"toast\">\n                    <span>&times;</span>\n                </button>\n            </div>\n            <div class=\"toast-body\">").concat(message, "</div>\n        </div>\n    ");
  $('#toast-container').append(toastHtml);
  $('.toast').last().toast('show');
}

// Export functions for global use
window.adminFunctions = {
  initDashboard: initDashboard,
  initProductManagement: initProductManagement,
  showToast: showToast,
  switchLanguage: switchLanguage
};

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/admin": 0,
/******/ 			"css/admin": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/admin"], () => (__webpack_require__("./resources/js/admin.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/admin"], () => (__webpack_require__("./resources/css/admin.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;