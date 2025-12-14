// PROFILE MANAGEMENT

const API_BASE = '..';

// Load user profile on page load
async function loadProfile() {
    try {
        const response = await fetch(`${API_BASE}/User/get_profile.php`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();
        
        if (data.success && data.user) {
            document.getElementById('firstname').value = data.user.firstname || '';
            document.getElementById('lastname').value = data.user.lastname || '';
            document.getElementById('email').value = data.user.email || '';
        } else {
            showStatusMessage('error', 'Failed to load profile. Please refresh the page.');
        }
    } catch (error) {
        console.error('Error loading profile:', error);
        showStatusMessage('error', 'Error loading profile. Please check your connection.');
    }
}

// Update profile
document.getElementById('profile-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const btn = document.getElementById('save-profile-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const formData = {
        firstname: document.getElementById('firstname').value.trim(),
        lastname: document.getElementById('lastname').value.trim(),
        email: document.getElementById('email').value.trim()
    };

    try {
        const response = await fetch(`${API_BASE}/User/update_profile.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        
        if (data.success) {
            showStatusMessage('success', 'Profile updated successfully!');
            // Update navbar user info if available
            if (typeof updateNavbarUserInfo === 'function') {
                updateNavbarUserInfo();
            }
        } else {
            showStatusMessage('error', data.error || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        showStatusMessage('error', 'Error updating profile. Please check your connection.');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
});

// Change password
document.getElementById('password-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Validate passwords match
    if (newPassword !== confirmPassword) {
        showPasswordStatusMessage('error', 'New passwords do not match');
        return;
    }

    if (newPassword.length < 6) {
        showPasswordStatusMessage('error', 'Password must be at least 6 characters long');
        return;
    }

    const btn = document.getElementById('change-password-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Changing...';

    const formData = {
        password: newPassword
    };

    try {
        const response = await fetch(`${API_BASE}/User/update_profile.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        
        if (data.success) {
            showPasswordStatusMessage('success', 'Password changed successfully!');
            // Clear password fields
            document.getElementById('password-form').reset();
        } else {
            showPasswordStatusMessage('error', data.error || 'Failed to change password');
        }
    } catch (error) {
        console.error('Error changing password:', error);
        showPasswordStatusMessage('error', 'Error changing password. Please check your connection.');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
});

// Delete account
document.getElementById('delete-account-btn').addEventListener('click', () => {
    document.getElementById('delete-modal').style.display = 'flex';
});

document.getElementById('cancel-delete-btn').addEventListener('click', () => {
    document.getElementById('delete-modal').style.display = 'none';
});

document.getElementById('confirm-delete-btn').addEventListener('click', async () => {
    const btn = document.getElementById('confirm-delete-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Deleting...';

    try {
        const response = await fetch(`${API_BASE}/User/delete_account.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();
        
        if (data.success) {
            // Account deleted - redirect to landing page
            alert('Your account has been permanently deleted.');
            window.location.href = '../LandingPages/landing.html';
        } else {
            alert('Failed to delete account: ' + (data.error || 'Unknown error'));
            btn.disabled = false;
            btn.textContent = originalText;
        }
    } catch (error) {
        console.error('Error deleting account:', error);
        alert('Error deleting account. Please check your connection and try again.');
        btn.disabled = false;
        btn.textContent = originalText;
    }
});

// Close modal when clicking outside
document.getElementById('delete-modal').addEventListener('click', (e) => {
    if (e.target.id === 'delete-modal') {
        document.getElementById('delete-modal').style.display = 'none';
    }
});

// Helper functions
function showStatusMessage(type, message) {
    const statusEl = document.getElementById('status-message');
    statusEl.textContent = message;
    statusEl.className = `status-message ${type}`;
    statusEl.style.display = 'block';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        statusEl.style.display = 'none';
    }, 5000);
}

function showPasswordStatusMessage(type, message) {
    const statusEl = document.getElementById('password-status-message');
    statusEl.textContent = message;
    statusEl.className = `status-message ${type}`;
    statusEl.style.display = 'block';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        statusEl.style.display = 'none';
    }, 5000);
}

// Load profile when page loads
loadProfile();
