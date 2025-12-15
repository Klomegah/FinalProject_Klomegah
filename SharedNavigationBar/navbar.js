// SHARED NAVIGATION BAR - Appears on all authenticated pages
// This file creates the user dropdown menu with profile, analytics, history, and logout links

// Fetch user profile data and create the dropdown menu
async function initializeNavbar() {
    const navActions = document.getElementById('nav-actions');
    if (!navActions) {
        console.error('nav-actions element not found');
        return;
    }

    const result = await apiRequest(`../User/get_profile.php`, {
        method: 'GET'
    });
    
    // If we successfully got user data, create dropdown with real user info
    if (result.success && result.data.success && result.data.user) {
        createUserDropdown(navActions, result.data.user);
    } else {
        // If loading user data failed, still show the dropdown with placeholder values
        // This ensures the navbar always works, even if there's a temporary API issue
        const defaultUser = {
            firstname: 'User',
            lastname: '',
            email: 'user@example.com'
        };
        createUserDropdown(navActions, defaultUser);
        console.warn('Failed to load user profile, using default values');
    }
}

// Build the HTML for the user dropdown menu and add it to the page
function createUserDropdown(container, user) {
    // Get the first letter of the user's name for the avatar circle
    const firstLetter = user.firstname ? user.firstname.charAt(0).toUpperCase() : 'U';
    // Combine first and last name, or use email if no name
    const fullName = `${user.firstname} ${user.lastname}`.trim() || user.email;
    // Truncate name if it's too long to fit in the navbar
    const displayName = fullName.length > 20 ? fullName.substring(0, 17) + '...' : fullName;

    // Create a wrapper div for nav actions
    const navWrapper = document.createElement('div');
    navWrapper.className = 'nav-actions-wrapper';
    navWrapper.style.display = 'flex';
    navWrapper.style.alignItems = 'center';
    navWrapper.style.gap = '0.5rem';
    
    // Clear container and append wrapper
    container.innerHTML = '';
    container.appendChild(navWrapper);

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

                <a href="../History/history-html.php" class="dropdown-item" id="history-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>History</span>
                </a>


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

    // Set up click handlers for the dropdown menu
    const menuBtn = document.getElementById('user-menu-btn');
    const dropdownMenu = document.getElementById('user-dropdown-menu');
    const logoutLink = document.getElementById('logout-link');

    // When user clicks the user menu button, show/hide the dropdown
    if (menuBtn && dropdownMenu) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent the click from bubbling up
            dropdownMenu.classList.toggle('show'); // Toggle the 'show' class to show/hide menu
        });

        // Close the dropdown if user clicks anywhere outside of it
        document.addEventListener('click', (e) => {
            if (!navWrapper.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // Handle logout button click - ask for confirmation first
    if (logoutLink) {
        logoutLink.addEventListener('click', async (e) => {
            e.preventDefault(); // Prevent the link from navigating immediately
            
            // Ask user to confirm they want to log out
            const confirmResult = await SwalAlert.confirm('Log Out', 'Are you sure you want to log out?');
            if (!confirmResult.isConfirmed) {
                return; // If they clicked "Cancel", don't do anything
            }

            // Send a request to the server to destroy the session
            try {
                await fetch(logoutLink.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                // After logout, redirect to the login page
                window.location.href = '../LoginAndSignUpPages/login-html.php';
            } catch (error) {
                console.error('Logout error:', error);
                // Even if there's an error, still redirect to login page
                window.location.href = '../LoginAndSignUpPages/login-html.php';
            }
        });
    }

}

// Function to refresh the navbar (useful after updating profile - name might have changed)
window.updateNavbarUserInfo = function() {
    initializeNavbar();
};

// Start building the navbar as soon as the page is ready
if (document.readyState === 'loading') {
    // If page is still loading, wait for it to finish
    document.addEventListener('DOMContentLoaded', initializeNavbar);
} else {
    // If page is already loaded, initialize immediately
    initializeNavbar();
}
