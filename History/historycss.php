<?php header('Content-Type: text/css'); ?>

/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

:root {
    --primary-purple: #8672FF;
    --primary-purple-dark: #6B4EFF;
    --bg-off-white: #FAF9F6;
    --text-dark: #1A1A1A;
    --text-medium: #4A4A4A;
    --border-light: #E5E5E5;
    --input-color: #F3F0FF;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--bg-off-white);
    min-height: 100vh;
    padding-top: 70px;
}

/* Navbar (same as other pages) */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(250, 249, 246, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-light);
    z-index: 1000;
    padding: 1rem 0;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    text-decoration: none;
    color: var(--text-dark);
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
}




/* Main Content */
.main-content {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2.5rem;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.page-header p {
    color: var(--text-medium);
    font-size: 1.1rem;
}

/* Filter Buttons */
.filter-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.75rem 1.5rem;
    border: 2px solid var(--text-dark);
    border-radius: 15px;
    background: white;
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.filter-btn:hover {
    background: var(--input-color);
    border-color: var(--primary-purple);
    color: var(--primary-purple);
    transform: translateY(-2px);
}

.filter-btn.active {
    background: var(--primary-purple);
    border-color: var(--primary-purple);
    color: white;
    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.4);
}

/* Sessions List */
.sessions-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.loading,
.no-sessions {
    text-align: center;
    padding: 3rem;
    color: var(--text-medium);
    font-size: 1.1rem;
}

/* Session Card */
.session-card {
    background: var(--input-color);
    border: 2px solid var(--input-color);
    border-radius: 15px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.session-card:hover {
    border-color: var(--primary-purple);
    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.2);
}

.session-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.session-date {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
}

.session-time {
    font-size: 0.9rem;
    color: var(--text-medium);
}

.session-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.85rem;
    color: var(--text-medium);
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
}

.session-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Delete Button */

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-2px);
}


.btn-delete {
    background: #ef4444;
    color: white;
    border: none;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: background 0.2s, transform 0.2s;
    font-family: 'Poppins', sans-serif;
}

.notes-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}

.notes-badge.has-notes {
    background: #10b981;
    color: white;
}

.notes-badge.no-notes {
    background: #d1d5db;
    color: #6b7280;
}


/* View Notes Button */
.btn-view {
    background: var(--primary-purple);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.2s ease;
    font-family: 'Poppins', sans-serif;
}

.btn-view:hover {
    background: var(--primary-purple-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        padding: 1rem;
    }
    
    .container {
        padding: 1.5rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .session-details {
        grid-template-columns: 1fr;
    }
    
    .session-actions {
        flex-direction: column;
    }
    
    .btn {
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
    margin-top: 2rem;
}

/* Secondary Button Styles */
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
        margin-top: 1.5rem;
    }

    .navigation-buttons .btn-secondary {
        width: 100%;
        min-width: unset;
    }
}
