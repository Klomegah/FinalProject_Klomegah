// SHARED NAVBAR COMPONENT WITH USER DROPDOWN
// This file should be included in pages that need the user dropdow

// Load user info and create dropdown
async function initializeNavbar() {
    const navActions = document.getElementById('nav-actions');
    if (!navActions) {
        console.error('nav-actions element not found');
        return;
    }

    try {
        // Fetch user profile
        const response = await fetch(`../User/get_profile.php`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();
        
        if (data.success && data.user) {
            createUserDropdown(navActions, data.user);
        } else {
            // Fallback if profile fails to load
            console.error('Failed to load user profile:', data);
            createFallbackNav(navActions);
        }
    } catch (error) {
        console.error('Error loading user profile:', error);
        createFallbackNav(navActions);
    }
}

// Create user dropdown menu (always shown on all pages)
function createUserDropdown(container, user) {
    const firstLetter = user.firstname ? user.firstname.charAt(0).toUpperCase() : 'U';
    const fullName = `${user.firstname} ${user.lastname}`.trim() || user.email;
    const displayName = fullName.length > 20 ? fullName.substring(0, 17) + '...' : fullName;

    // Create a wrapper div for nav actions
    const navWrapper = document.createElement('div');
    navWrapper.className = 'nav-actions-wrapper';
    navWrapper.style.display = 'flex';
    navWrapper.style.alignItems = 'center';
    navWrapper.style.gap = '0.5rem';

    // User dropdown HTML
    navWrapper.innerHTML = `
        <div class="user-dropdown">
            <button class="user-menu-btn" id="user-menu-btn" aria-label="User menu">
                <div class="user-avatar">${firstLetter}</div>
                <span class="user-name">${displayName}</span>
                <svg class="dropdown-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="user-dropdown-menu" id="user-dropdown-menu">
                <div class="dropdown-header">
                    <div class="user-avatar-large">${firstLetter}</div>
                    <div class="user-info">
                        <div class="user-full-name">${fullName}</div>
                        <div class="user-email">${user.email}</div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="../User/profile-html.php" class="dropdown-item" id="profile-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Edit Profile</span>
                </a>
                <a href="../Analytics/analytics-html.php" class="dropdown-item" id="analytics-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Analytics/Reports</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="../Authentication/logout.php" class="dropdown-item logout-item" id="logout-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Log Out</span>
                </a>
            </div>
        </div>
    `;

    // Add event listeners
    const menuBtn = document.getElementById('user-menu-btn');
    const dropdownMenu = document.getElementById('user-dropdown-menu');
    const logoutLink = document.getElementById('logout-link');

    if (menuBtn && dropdownMenu) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!navWrapper.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // Logout confirmation and redirect
    if (logoutLink) {
        logoutLink.addEventListener('click', async (e) => {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to log out?')) {
                return;
            }

            // Make AJAX logout request
            try {
                const response = await fetch(logoutLink.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();
                // Redirect to login page
                window.location.href = '../LoginAndSignUpPages/login-html.php';
            } catch (error) {
                console.error('Logout error:', error);
                // Redirect to login page anyway
                window.location.href = '../LoginAndSignUpPages/login-html.php';
            }
        });
    }

}

// Fallback navigation if user info fails to load
function createFallbackNav(container) {
    container.innerHTML = `
        <a href="../Authentication/logout.php" class="logout-btn" id="logout-link-fallback">Log Out</a>
    `;

    const logoutLink = document.getElementById('logout-link-fallback');
    if (logoutLink) {
        logoutLink.addEventListener('click', async (e) => {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to log out?')) {
                return;
            }

            // Make AJAX logout request
            try {
                const response = await fetch(logoutLink.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();
                // Redirect to login page
                window.location.href = '../LoginAndSignUpPages/login-html.php';
            } catch (error) {
                console.error('Logout error:', error);
                // Redirect to login page anyway
                window.location.href = '../LoginAndSignUpPages/login-html.php';
            }
        });
    }
}

// Update navbar user info (for use after profile update)
window.updateNavbarUserInfo = function() {
    initializeNavbar();
};

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeNavbar);
} else {
    initializeNavbar();
}
