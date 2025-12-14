<?php header('Content-Type: text/css'); ?>

/* Import Google Fonts for better typography */
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
    height: 100vh;
    overflow: hidden;
    background-color: var(--bg-off-white);
    font-family: 'Poppins', sans-serif;
    display: flex;
    flex-direction: column;
    padding-top: 70px;
}


/* App Navbar - Matching Landing Page Style */
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
    transition: all var(--transition-medium);
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
    font-family: 'Poppins', sans-serif;
}

.logo:hover {
    opacity: 0.8;
}

.nav-actions {
    display: flex;
    gap: var(--spacing-sm);
    align-items: center;
}

.logout-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all var(--transition-medium);
    border: none;
    cursor: pointer;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
}

.logout-btn {
    background-color: var(--primary-purple);
    color: var(--base-color);
    text-transform: uppercase;
}

.logout-btn:hover {
    background-color: var(--primary-purple-dark);
    transform: translateY(-2px);
}

.container {
    background: var(--base-color);
    border: 2px solid var(--input-color);
    border-radius: 2em;
    padding: 3em;
    max-width: 900px;
    width: 100%;
    color: var(--text-color);
    animation: fadeInUp 0.6s ease-out;
}


/* Main Wrapper: Timer Left, Tasks Right */
.main-wrapper {
    display: flex;
    flex: 1;
    gap: 2rem;
    padding: 2rem;
    max-width: 1200px; /* Reduced from 1400px for better centering */
    margin: 0 auto;
    width: 100%;
    overflow: hidden;
    align-items: stretch;
    justify-content: center; /* Center the content */
    height: calc(100vh - 70px);
    max-height: calc(100vh - 70px);
}

/* Responsive: Stack vertically on smaller screens */
@media (max-width: 1024px) {
    .main-wrapper {
        flex-direction: column;
        align-items: center;
        height: auto;
        min-height: calc(100vh - 70px);
        padding: 1.5rem;
        gap: 1.5rem;
    }
    
    .timer-section {
        width: 100%;
        max-width: 500px;
        flex: 0 0 auto;
    }
    
    .tasks-section {
        width: 100%;
        max-width: 600px;
        flex: 0 0 auto;
        min-height: 400px;
    }
    
    .timer-wrapper {
        width: 300px;
        height: 300px;
    }
}

/*  TIMER SECTION (LEFT) - No Rectangle Container     */
.timer-section {
    flex: 0 0 auto; /* Don't stretch, just fit content */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    padding: 2rem;
    /* No background, no border - just floating elements */
}


/* Mode Selector */
.mode-selector {
    display: flex;
    gap: 1rem;
    justify-content: center;
    width: 100%;
}

.mode-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    background: transparent;
    color: var(--text-color);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.mode-btn.active {
    background-color: var(--accent-color);
    color: var(--base-color);
}

.mode-btn:hover:not(.active) {
    background-color: rgba(134, 114, 255, 0.1);
    color: var(--accent-color);
}

/* Circular Timer Wrapper */
.timer-wrapper {
    position: relative;
    width: 500px; /* Slightly larger for better visibility */
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0; /* Prevent shrinking */
}

.timer-circle {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    /* No background, no border - just the rings and text */
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Progress Ring (Outer Ring Only - No Inner Ring) */
.timer-progress-ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    width: 100%;
    height: 100%;
    background: conic-gradient(
        from 0deg,
        var(--primary-purple) 0deg,
        var(--primary-purple) 0deg,
        var(--border-light) 0deg,
        var(--border-light) 360deg
    );
    /* Create outer ring only using mask - thicker ring, no inner ring */
    mask: radial-gradient(
        circle,
        transparent calc(50% - 12px),
        black calc(50% - 12px),
        black 50%,
        transparent 50%
    );
    -webkit-mask: radial-gradient(
        circle,
        transparent calc(50% - 12px),
        black calc(50% - 12px),
        black 50%,
        transparent 50%
    );
    transform: rotate(-90deg); /* Start from top (12 o'clock) */
    transition: background 0.1s linear;
    pointer-events: none;
}

/* Timer Text (Centered Inside Circle) */
.timer-text {
    position: relative;
    z-index: 10;
    font-size: clamp(2.5rem, 8vw, 4rem);
    font-weight: 700;
    font-variant-numeric: tabular-nums;
    color: var(--accent-color);
    letter-spacing: 0.05em;
    pointer-events: none;
    line-height: 1;
}

/* Legacy timer class - hidden for backward compatibility */
.timer {
    display: none;
}

.button-wrapper {
    display: flex;
    gap: 1.5em;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
}

#start-btn {
    font-size: clamp(1em, 2.5vw, 1.2em);
    font-weight: 700;
    letter-spacing: 1px;
    padding: 0.75em 2em;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    color: var(--base-color);
    background-color: var(--accent-color);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    width: 100%;
    max-width: 250px;
    text-transform: uppercase;
}

#start-btn:hover {
    background-color: var(--primary-purple-dark);
    transform: translateY(-2px);
}

#start-btn:active {
    transform: translateY(0);
}


/* TASKS SECTION (RIGHT) */

.tasks-section {
    width: 350px;
    background: var(--base-color);
    border: 2px solid var(--input-color);
    border-radius: 2em;
    padding: 1.5em;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    color: var(--text-color);
    height: 100%;
    max-height: 100%;
    overflow: hidden;
    flex-shrink: 0;
}

.tasks-title {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 1rem;
}

.tasks-list {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: 0;
    max-height: 100%;
    padding-right: 0.5rem;
}

/* Custom scrollbar for tasks list */
.tasks-list::-webkit-scrollbar {
    width: 6px;
}

.tasks-list::-webkit-scrollbar-track {
    background: transparent;
}

.tasks-list::-webkit-scrollbar-thumb {
    background: var(--accent-color);
    border-radius: 3px;
}

.tasks-list::-webkit-scrollbar-thumb:hover {
    background: var(--primary-purple-dark);
}

.task-item {
    background: var(--input-color);
    padding: 1rem;
    border-radius: 1em;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: background 0.2s, border-color 0.2s;
    border: 2px solid var(--input-color);
}

.task-item:hover {
    border-color: var(--accent-color);
}

.task-item.completed {
    opacity: 0.6;
    text-decoration: line-through;
}

.task-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: var(--accent-color);
}

.task-text {
    flex: 1;
    font-size: 0.95rem;
}

.task-delete {
    background: #ef4444;
    border: none;
    color: var(--base-color);
    padding: 0.25rem 0.5rem;
    border-radius: 0.5em;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.2s;
}

.task-delete:hover {
    background: #dc2626;
}

.add-task-box {
    margin-top: auto;
    flex-shrink: 0;
    width: 100%;
    display: block;
}

#task-input {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--input-color);
    border-radius: 1em;
    background: var(--input-color);
    color: var(--text-color);
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
    transition: border-color 0.2s, background 0.2s;
    box-sizing: border-box;
}

#task-input::placeholder {
    color: #6B6B6B;
}

#task-input:hover {
    border-color: var(--accent-color);
}

#task-input:focus {
    outline: none;
    border-color: var(--accent-color);
}



/*  RESPONSIVE DESIGN  */

@media (max-width: 968px) {
    body {
        min-height: 100vh;
        height: auto;
        overflow-y: auto;
        overflow-x: hidden;
        padding-top: 70px;
    }

    .app-nav-container {
        padding: 0 var(--spacing-sm);
    }

    .main-wrapper {
        flex-direction: column;
        padding: 1rem;
        gap: 1.5rem;
        height: auto;
        min-height: calc(100vh - 70px);
        overflow: visible;
    }

    .timer-section {
        width: 100%;
        min-height: auto;
    }

    .container {
        max-width: 100%;
        padding: 2em 2em;
        gap: 20px;
        min-height: 400px;
        width: 100%;
    }
    
    .tasks-section {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        flex-shrink: 1;
        min-height: 350px;
        max-height: none;
        height: auto;
        overflow: visible;
        display: flex;
        flex-direction: column;
    }

    .tasks-header {
        flex-shrink: 0;
    }

    .tasks-list {
        flex: 1;
        min-height: 200px;
        max-height: 400px;
        overflow-y: auto;
    }

    .add-task-box {
        flex-shrink: 0;
        margin-top: 1rem;
        width: 100%;
    }
    
    .tasks-header {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }

    .task-item {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .task-text {
        flex: 1;
        min-width: 150px;
    }

    .mode-selector {
        gap: 0.75rem;
    }

    .mode-btn {
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
    }
}


@media (max-width: 480px) {
    body {
        min-height: 100vh;
        height: auto;
        overflow-y: auto;
        overflow-x: hidden;
        padding-top: 70px;
    }

    .main-wrapper {
        padding: 0.75rem;
        gap: 1rem;
        height: auto;
        min-height: calc(100vh - 70px);
    }

    .timer-section {
        padding: 1rem;
        gap: 1rem;
    }

    .tasks-section {
        padding: 1em;
        min-height: 300px;
        max-height: none;
        height: auto;
        overflow: visible;
        display: flex;
        flex-direction: column;
    }

    .tasks-list {
        flex: 1;
        min-height: 150px;
        max-height: 300px;
        overflow-y: auto;
    }

    .add-task-box {
        flex-shrink: 0;
        margin-top: 1rem;
        width: 100%;
    }

    #task-input {
        padding: 0.75rem;
        font-size: 0.9rem;
    }

    .mode-selector {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .mode-btn {
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
        flex: 1;
        min-width: 0;
    }

    #start-btn {
        padding: 0.65em 1.5em;
        font-size: 0.95em;
        max-width: 200px;
        width: 100%;
    }

    .task-item {
        padding: 0.75rem;
        font-size: 0.9rem;
    }

    .task-delete {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    /* Responsive Circular Timer */
    .timer-wrapper {
        width: 250px;
        height: 250px;
        margin: 1rem 0;
    }

    .timer-text {
        font-size: clamp(2rem, 8vw, 2.5rem);
    }
}