// USER PROFILE PAGE - Manage account settings, change password, delete account

const API_BASE = '..';

// Fetch the current user's profile information and fill in the form
async function loadProfile() {
    const result = await apiRequest(`${API_BASE}/User/get_profile.php`, {
        method: 'GET'
    });
    
    if (result.success && result.data.success && result.data.user) {
        document.getElementById('firstname').value = result.data.user.firstname || '';
        document.getElementById('lastname').value = result.data.user.lastname || '';
        document.getElementById('email').value = result.data.user.email || '';
    } else {
        showStatusMessage('error', 'Failed to load profile. Please refresh the page.');
    }
}

// Handle when user submits the profile update form (name and email changes)
document.getElementById('profile-form').addEventListener('submit', async (e) => {
    e.preventDefault(); // Prevent the form from submitting normally (we'll use AJAX instead)
    
    // Disable the button and show "Saving..." while we process the update
    const btn = document.getElementById('save-profile-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Saving...';

    // Collect the form data (trim whitespace from all fields)
    const formData = {
        firstname: document.getElementById('firstname').value.trim(),
        lastname: document.getElementById('lastname').value.trim(),
        email: document.getElementById('email').value.trim()
    };

    // Send the update request to the server
    const result = await apiRequest(`${API_BASE}/User/update_profile.php`, {
        method: 'PUT',
        body: formData
    });
    
    // If update was successful, show success message and update the navbar
    if (result.success && result.data.success) {
        showStatusMessage('success', 'Profile updated successfully!');
        // Update the user name in the navigation bar to reflect the changes
        if (typeof updateNavbarUserInfo === 'function') {
            updateNavbarUserInfo();
        }
    } else {
        // If update failed, show an error message
        showStatusMessage('error', result.error || result.data?.error || 'Failed to update profile');
    }
    
    // Re-enable the button and restore original text
    btn.disabled = false;
    btn.textContent = originalText;
});

// Handle when user submits the password change form
document.getElementById('password-form').addEventListener('submit', async (e) => {
    e.preventDefault(); // Prevent normal form submission
    
    // Get the two password fields
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Check that both passwords match (user typed the same password twice)
    if (newPassword !== confirmPassword) {
        showPasswordStatusMessage('error', 'New passwords do not match');
        return; // Stop here if they don't match
    }

    // Check that password is at least 6 characters long
    if (newPassword.length < 6) {
        showPasswordStatusMessage('error', 'Password must be at least 6 characters long');
        return; // Stop here if password is too short
    }

    // Disable button and show "Changing..." while processing
    const btn = document.getElementById('change-password-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Changing...';

    // Prepare the password data to send
    const formData = {
        password: newPassword
    };

    // Send the password update to the server
    const result = await apiRequest(`${API_BASE}/User/update_profile.php`, {
        method: 'PUT',
        body: formData
    });
    
    // If password change was successful, show success and clear the form
    if (result.success && result.data.success) {
        showPasswordStatusMessage('success', 'Password changed successfully!');
        // Clear the password fields for security
        document.getElementById('password-form').reset();
    } else {
        // If it failed, show an error message
        showPasswordStatusMessage('error', result.error || result.data?.error || 'Failed to change password');
    }
    
    // Re-enable the button
    btn.disabled = false;
    btn.textContent = originalText;
});

// ACCOUNT DELETION - Dangerous operation, so we use a confirmation modal

// When user clicks "Delete Account" button, show the confirmation modal
document.getElementById('delete-account-btn').addEventListener('click', () => {
    document.getElementById('delete-modal').style.display = 'flex';
});

// When user clicks "Cancel" in the modal, hide it
document.getElementById('cancel-delete-btn').addEventListener('click', () => {
    document.getElementById('delete-modal').style.display = 'none';
});

// When user confirms they want to delete their account
document.getElementById('confirm-delete-btn').addEventListener('click', async () => {
    // Disable button and show "Deleting..." while processing
    const btn = document.getElementById('confirm-delete-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Deleting...';

    // Send DELETE request to permanently remove the account
    const result = await apiRequest(`${API_BASE}/User/delete_account.php`, {
        method: 'DELETE'
    });
    
    // If deletion was successful, show success message and redirect to landing page
    if (result.success && result.data.success) {
        SwalAlert.success('Account Deleted', 'Your account has been permanently deleted.').then(() => {
            window.location.href = '../LandingPages/landing-html.php';
        });
    } else {
        // If deletion failed, show error and re-enable the button
        SwalAlert.error('Failed to Delete Account', result.error || result.data?.error || 'Unknown error');
        btn.disabled = false;
        btn.textContent = originalText;
    }
});

// Close the modal if user clicks outside of it (on the dark overlay)
document.getElementById('delete-modal').addEventListener('click', (e) => {
    if (e.target.id === 'delete-modal') {
        document.getElementById('delete-modal').style.display = 'none';
    }
});

// HELPER FUNCTIONS - Display success/error messages to the user

// Show a status message for profile updates (success or error)
function showStatusMessage(type, message) {
    const statusEl = document.getElementById('status-message');
    statusEl.textContent = message;
    statusEl.className = `status-message ${type}`; // Add 'success' or 'error' class for styling
    statusEl.style.display = 'block';
    
    // Automatically hide the message after 5 seconds
    setTimeout(() => {
        statusEl.style.display = 'none';
    }, 5000);
}

// Show a status message for password changes (success or error)
function showPasswordStatusMessage(type, message) {
    const statusEl = document.getElementById('password-status-message');
    statusEl.textContent = message;
    statusEl.className = `status-message ${type}`; // Add 'success' or 'error' class for styling
    statusEl.style.display = 'block';
    
    // Automatically hide the message after 5 seconds
    setTimeout(() => {
        statusEl.style.display = 'none';
    }, 5000);
}

// Load the user's profile data when the page first loads
loadProfile();
