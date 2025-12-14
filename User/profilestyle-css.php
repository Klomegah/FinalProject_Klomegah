<?php header('Content-Type: text/css'); ?>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');

:root {
    --accent-color: #8672FF;
    --base-color: white;
    --text-color: black;
    --input-color: #F3F0FF;
    --bg-off-white: #FAF9F6;
    --primary-purple: #8672FF;
    --primary-purple-dark: #6B4EFF;
    --text-dark: #1A1A1A;
    --text-medium: #4A4A4A;
    --border-light: #E5E5E5;
    --danger-color: #EF4444;
    --danger-dark: #DC2626;
    --success-color: #10B981;
    --spacing-sm: 1rem;
    --spacing-md: 2rem;
    --transition-medium: 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    background-color: var(--bg-off-white);
    font-family: 'Poppins', sans-serif;
    padding-top: 70px;
}

/* Navbar Styles (shared) */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(250, 249, 246, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-light);
    z-index: 1000;
    padding: var(--spacing-sm) 0;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--text-dark);
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: -0.02em;
}

.nav-actions {
    display: flex;
    gap: var(--spacing-sm);
    align-items: center;
}

/* Back Button */
.back-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: 2px solid var(--border-light);
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-dark);
    font-weight: 600;
    font-size: 0.95rem;
    transition: all var(--transition-medium);
    background-color: var(--base-color);
}

.back-btn:hover {
    border-color: var(--primary-purple);
    background-color: var(--input-color);
    color: var(--primary-purple);
}

.back-btn svg {
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .back-btn span {
        display: none;
    }
    .back-btn {
        padding: 0.5rem;
    }
}

/* Profile Container */
.profile-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 var(--spacing-md);
}

.profile-header {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.profile-header p {
    color: var(--text-medium);
    font-size: 1.1rem;
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.profile-section {
    background: var(--base-color);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.profile-section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--input-color);
    border-radius: 12px;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    transition: all var(--transition-medium);
    background-color: var(--base-color);
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-purple);
    background-color: var(--input-color);
}

.form-group small {
    display: block;
    margin-top: 0.25rem;
    color: var(--text-medium);
    font-size: 0.85rem;
}

/* Buttons */
.btn-primary {
    background-color: var(--primary-purple);
    color: var(--base-color);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-medium);
    font-family: 'Poppins', sans-serif;
}

.btn-primary:hover {
    background-color: var(--primary-purple-dark);
    transform: translateY(-2px);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-danger {
    background-color: var(--danger-color);
    color: var(--base-color);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-medium);
    font-family: 'Poppins', sans-serif;
}

.btn-danger:hover {
    background-color: var(--danger-dark);
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: transparent;
    color: var(--text-dark);
    border: 2px solid var(--border-light);
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-medium);
    font-family: 'Poppins', sans-serif;
}

.btn-secondary:hover {
    border-color: var(--primary-purple);
    background-color: var(--input-color);
    color: var(--primary-purple);
    transform: translateY(-2px);
}

/* Danger Zone */
.danger-zone {
    border: 2px solid #FEE2E2;
    background-color: #FEF2F2;
}

.danger-warning {
    color: var(--text-medium);
    margin-bottom: 1rem;
    line-height: 1.6;
}

/* Status Messages */
.status-message {
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    font-weight: 500;
}

.status-message.success {
    background-color: #D1FAE5;
    color: #065F46;
    border: 1px solid #10B981;
}

.status-message.error {
    background-color: #FEE2E2;
    color: #991B1B;
    border: 1px solid #EF4444;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-content {
    background: var(--base-color);
    border-radius: 20px;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-content h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.modal-content p {
    color: var(--text-medium);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.warning-text {
    font-weight: 600;
    color: var(--danger-color);
}

.warning-list {
    margin: 1rem 0;
    padding-left: 1.5rem;
    color: var(--text-medium);
}

.warning-list li {
    margin-bottom: 0.5rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}

@media (max-width: 768px) {
    .profile-container {
        margin: 1rem auto;
    }

    .profile-header h1 {
        font-size: 2rem;
    }

    .profile-section {
        padding: 1.5rem;
    }

    .modal-content {
        padding: 1.5rem;
    }

    .modal-actions {
        flex-direction: column;
    }

    .modal-actions button {
        width: 100%;
    }
}

/* Navigation Buttons Section */
.navigation-buttons {
    background: var(--base-color);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.navigation-buttons .btn-secondary {
    min-width: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .navigation-buttons {
        flex-direction: column;
        padding: 1.5rem;
    }

    .navigation-buttons .btn-secondary {
        width: 100%;
        min-width: unset;
    }
}

