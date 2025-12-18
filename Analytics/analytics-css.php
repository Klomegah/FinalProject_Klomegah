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


@media (max-width: 768px) {
    .back-btn span {
        display: none;
    }
    .back-btn {
        padding: 0.5rem;
    }
}

/* Analytics Container */
.analytics-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 var(--spacing-md);
}

.analytics-header {
    text-align: center;
    margin-bottom: 2rem;
}

.analytics-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.analytics-header p {
    color: var(--text-medium);
    font-size: 1.1rem;
}

/* Overview Cards */
.overview-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--base-color);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
}


.stat-info h3 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-purple);
    margin-bottom: 0.25rem;
}

.stat-info p {
    color: var(--text-medium);
    font-size: 0.9rem;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: var(--base-color);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.chart-card h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
}

.chart-card canvas {
    max-height: 300px;
}

/* Monthly Section */
.monthly-section {
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .overview-cards {
        grid-template-columns: 1fr;
    }

    .charts-section {
        grid-template-columns: 1fr;
    }

    .analytics-header h1 {
        font-size: 2rem;
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

/* Time Filter Buttons -  Pomodoro mode buttons */
.time-filters {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    padding: 0 1rem;
}

.filter-btn {
    padding: 1rem 2rem;
    border: 2px solid var(--text-dark);
    border-radius: 15px;
    background: var(--base-color);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
    min-width: 120px;
}

.filter-btn:hover {
    background: var(--input-color);
    border-color: var(--primary-purple);
    color: var(--primary-purple);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.3);
}

.filter-btn.active {
    background: var(--primary-purple);
    border-color: var(--primary-purple);
    color: white;
    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.4);
}
