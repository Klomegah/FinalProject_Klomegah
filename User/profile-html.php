<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="User Profile - LockIn">
    <title>Profile - LockIn</title>
    <link rel="stylesheet" href="profilestyle-css.php">
    <link rel="stylesheet" href="../SharedNavigationBar/navbar-css.php">
</head>
<body>
    
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="../LandingPages/landing-html.php" class="logo">
                <span class="logo-text">LockIn</span>
            </a>
            
            <div class="nav-actions" id="nav-actions">
                <!-- User dropdown and back button will be injected here by navbar.js -->
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <h1>Profile Settings</h1>
            <p>Manage your account information and preferences</p>
        </div>

        <div class="profile-content">
            <!-- Profile Information Section -->
            <div class="profile-section">
                <h2>Personal Information</h2>
                <div id="status-message" class="status-message" style="display: none;"></div>
                
                <form id="profile-form">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <button type="submit" class="btn-primary" id="save-profile-btn">Save Changes</button>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="profile-section">
                <h2>Change Password</h2>
                <div id="password-status-message" class="status-message" style="display: none;"></div>
                
                <form id="password-form">
                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" id="new-password" name="new-password" required minlength="6">
                        <small>Password must be at least 6 characters long</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm New Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" required>
                    </div>

                    <button type="submit" class="btn-primary" id="change-password-btn">Change Password</button>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="profile-section danger-zone">
                <h2>Danger Zone</h2>
                <p class="danger-warning">Deleting your account will permanently remove all your data including sessions, tasks, and notes. This action cannot be undone.</p>
                
                <button type="button" class="btn-danger" id="delete-account-btn">Delete Account</button>
            </div>

            <div class="navigation-buttons">
                <button type="button" class="btn-secondary" onclick="window.location.href='../PomodoroPages/pomodoro-html.php'">Back to Timer</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='../FeynmanPages/feynmannotes-html.php'">Back to Feynman Notes</button>
            </div>
            

        </div>
    </div>

    <!-- Delete Account Confirmation Modal -->
    <div id="delete-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h3>Confirm Account Deletion</h3>
            <p>Are you sure you want to delete your account? This action cannot be undone.</p>
            <p class="warning-text">All your data will be permanently deleted:</p>
            <ul class="warning-list">
                <li>All Pomodoro sessions</li>
                <li>All tasks</li>
                <li>All Feynman notes</li>
                <li>All drafts</li>
            </ul>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="cancel-delete-btn">Cancel</button>
                <button type="button" class="btn-danger" id="confirm-delete-btn">Yes, Delete My Account</button>
            </div>
        </div>
    </div>

    <script src="../SharedNavigationBar/navbar.js"></script>
    <script src="profile.js"></script>
</body>
</html>


