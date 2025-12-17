// SHARED UTILITIES FOR LOCKIN APP
// Common functions used across the application

// Color scheme constants
const COLORS = {
    primary: '#8672FF',
    primaryDark: '#6B4EFF',
    white: '#FFFFFF',
    textDark: '#1A1A1A',
    textMedium: '#4A4A4A',
    error: '#EF4444',
    success: '#10B981'
};

// Configure SweetAlert2 with custom theme
if (typeof Swal !== 'undefined') {
    Swal.mixin({
        customClass: {
            popup: 'lockin-swal-popup',
            confirmButton: 'lockin-swal-confirm',
            cancelButton: 'lockin-swal-cancel',
            title: 'lockin-swal-title',
            htmlContainer: 'lockin-swal-content'
        },
        buttonsStyling: false,
        confirmButtonColor: COLORS.primary,
        cancelButtonColor: COLORS.textMedium
    });
}

// SweetAlert wrapper functions with LockIn theme
const SwalAlert = {
    // Success alert
    success: (title, text = '', options = {}) => {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            confirmButtonText: options.confirmText || 'OK',
            background: COLORS.white,
            color: COLORS.textDark,
            confirmButtonColor: COLORS.primary,
            ...options
        });
    },

    // Error alert
    error: (title, text = '', options = {}) => {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: text,
            confirmButtonText: options.confirmText || 'OK',
            background: COLORS.white,
            color: COLORS.textDark,
            confirmButtonColor: COLORS.error,
            ...options
        });
    },

    // Warning alert
    warning: (title, text = '', options = {}) => {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
            confirmButtonText: options.confirmText || 'OK',
            background: COLORS.white,
            color: COLORS.textDark,
            confirmButtonColor: COLORS.primary,
            ...options
        });
    },

    // Info alert
    info: (title, text = '', options = {}) => {
        return Swal.fire({
            icon: 'info',
            title: title,
            text: text,
            confirmButtonText: options.confirmText || 'OK',
            background: COLORS.white,
            color: COLORS.textDark,
            confirmButtonColor: COLORS.primary,
            ...options
        });
    },

    // Confirmation dialog
    confirm: (title, text = '', options = {}) => {
        return Swal.fire({
            icon: 'question',
            title: title,
            text: text,
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Yes',
            cancelButtonText: options.cancelText || 'Cancel',
            background: COLORS.white,
            color: COLORS.textDark,
            confirmButtonColor: COLORS.primary,
            cancelButtonColor: COLORS.textMedium,
            ...options
        });
    }
};

// API helper function for common fetch operations
async function apiRequest(url, options = {}) {
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };

    const config = {
        method: options.method || 'GET',
        headers: { ...defaultHeaders, ...options.headers },
        credentials: 'same-origin',
        ...options
    };

    // Add body if provided and method is not GET
    if (options.body && config.method !== 'GET') {
        config.body = typeof options.body === 'string' ? options.body : JSON.stringify(options.body);
    }

    try {
        const response = await fetch(url, config);

        // Handle non-JSON responses
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Invalid response format');
        }

        const data = await response.json();

        // Check if response indicates an error
        if (!response.ok && !data.success) {
            throw new Error(data.error || `HTTP error! status: ${response.status}`);
        }

        return { success: true, data, response };
    } catch (error) {
        console.error('API request error:', error);
        return {
            success: false,
            error: error.message || 'Network error. Please check your connection.',
            response: null
        };
    }
}

// Session Keep-Alive Mechanism
// Prevents PHP session expiration during long-running activities (Pomodoro timer, note-taking)
let keepAliveInterval = null;

/**
 * Start session keep-alive - sends periodic requests to refresh PHP session
 * @param {number} intervalMinutes - How often to refresh (default: 10 minutes)
 */
function startSessionKeepAlive(intervalMinutes = 10) {
    // Clear any existing interval
    stopSessionKeepAlive();
    
    // Convert minutes to milliseconds
    const intervalMs = intervalMinutes * 60 * 1000;
    
    // Send keep-alive request immediately, then periodically
    sendKeepAlive();
    
    keepAliveInterval = setInterval(() => {
        sendKeepAlive();
    }, intervalMs);
}

/**
 * Stop session keep-alive
 */
function stopSessionKeepAlive() {
    if (keepAliveInterval) {
        clearInterval(keepAliveInterval);
        keepAliveInterval = null;
    }
}

/**
 * Send a keep-alive request to refresh the session
 */
async function sendKeepAlive() {
    try {
        // Silently refresh session - don't show errors to user
        await fetch('../Authentication/keep_alive.php', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    } catch (error) {
        // Silently fail - session might already be expired or network issue
        // Don't interrupt user's work with error messages
        console.debug('Keep-alive request failed (this is usually fine):', error);
    }
}

// Make functions globally available
window.SwalAlert = SwalAlert;
window.apiRequest = apiRequest;
window.COLORS = COLORS;
window.startSessionKeepAlive = startSessionKeepAlive;
window.stopSessionKeepAlive = stopSessionKeepAlive;
