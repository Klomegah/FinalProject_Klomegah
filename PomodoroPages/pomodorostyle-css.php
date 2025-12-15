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
    min-height: 100vh;
    height: auto;
    overflow-x: hidden;
    overflow-y: auto;
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
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    overflow: auto;
    align-items: stretch;
    justify-content: center;
    min-height: calc(100vh - 70px);
    transition: all 0.3s ease;
}

/* Responsive: Stack vertically on smaller screens */
@media (max-width: 1024px) {
    .main-wrapper {
        flex-direction: column;
        align-items: center;
        padding: 1.5rem;
        gap: 1.5rem;
        overflow-y: auto;
        overflow-x: hidden;
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
        height: auto;
        max-height: 500px;
    }
    
    .timer-wrapper {
        width: clamp(280px, 40vw, 320px);
        height: clamp(280px, 40vw, 320px);
    }
}

/* Medium screens - adjust layout proportions */
@media (max-width: 1200px) and (min-width: 1025px) {
    .main-wrapper {
        gap: 1.5rem;
        padding: 1.5rem;
    }
    
    .timer-wrapper {
        width: clamp(300px, 25vw, 350px);
        height: clamp(300px, 25vw, 350px);
    }
    
    .tasks-section {
        width: clamp(320px, 28vw, 380px);
        min-width: 320px;
        max-width: 380px;
    }
}

/*  TIMER SECTION (LEFT) - No Rectangle Container     */
.timer-section {
    flex: 0 0 auto; 
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 2rem;
    padding: 2rem;
    min-width: 0;
    transition: all 0.3s ease;
    align-self: stretch; /* Match tasks section height */
    min-height: 400px; /* Match tasks section min-height */
}


/* Mode Selector - Matching Analytics Filter Buttons */
.mode-selector {
    display: flex;
    gap: 1rem;
    justify-content: center;
    width: 100%;
    flex-shrink: 0; /* Don't shrink, similar to tasks-header */
    flex-wrap: wrap;
    padding: 0 1rem;
}

.mode-btn {
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

.mode-btn:hover {
    background: var(--input-color);
    border-color: var(--primary-purple);
    color: var(--primary-purple);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.3);
}

.mode-btn.active {
    background: var(--primary-purple);
    border-color: var(--primary-purple);
    color: white;
    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.4);
}

/* Circular Timer Wrapper */
.timer-wrapper {
    position: relative;
    width: clamp(250px, 30vw, 350px);
    height: clamp(250px, 30vw, 350px);
    min-width: 250px;
    min-height: 250px;
    max-width: 350px;
    max-height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    flex: 1; /* Take available space, similar to tasks-list */
    min-height: 0;
}

.timer-circle {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Progress Ring  */
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

    /* outer progress ring */
    mask: radial-gradient(
        circle,
        transparent calc(50% - 5px),
        black calc(50% - 5px),
        black 50%,
        transparent 50%
    );
    -webkit-mask: radial-gradient(
        circle,
        transparent calc(50% - 5px),
        black calc(50% - 5px),
        black 50%,
        transparent 50%
    );
    transform: rotate(-90deg); 
    transition: background 0.1s linear;
    pointer-events: none;
}

/* Timer Text  */
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


.button-wrapper {
    display: flex;
    gap: 1.5em;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    margin-top: auto; /* Push button to bottom to align with tasks section add-task-box */
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
    width: clamp(300px, 30vw, 400px);
    min-width: 300px;
    max-width: 400px;
    background: var(--base-color);
    border: 2px solid var(--input-color);
    border-radius: 2em;
    padding: 1.5em;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    color: var(--text-color);
    min-height: 400px;
    max-height: calc(100vh - 150px);
    overflow: hidden;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.tasks-title {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 1rem;
    padding-top: 0;
    margin-top: 0;
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

    .main-wrapper {
        flex-direction: column;
        padding: 1rem;
        gap: 1.5rem;
        min-height: calc(100vh - 70px);
        overflow-y: auto;
        overflow-x: hidden;
    }

    .timer-section {
        width: 100%;
        max-width: 100%;
        padding: 1.5rem;
        gap: 1.5rem;
    }
    
    .tasks-section {
        width: 100%;
        max-width: 100%;
        min-width: 100%;
        margin: 0 auto;
        min-height: 350px;
        max-height: 500px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .tasks-header {
        flex-shrink: 0;
    }

    .tasks-list {
        flex: 1;
        min-height: 200px;
        max-height: 350px;
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
        flex-wrap: wrap;
        justify-content: center;
    }

    .mode-btn {
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
    }

    .timer-wrapper {
        width: clamp(250px, 50vw, 300px);
        height: clamp(250px, 50vw, 300px);
    }
}


@media (max-width: 768px) {
    .main-wrapper {
        padding: 1rem;
        gap: 1.25rem;
    }

    .timer-section {
        padding: 1rem;
        gap: 1.25rem;
    }

    .timer-wrapper {
        width: clamp(220px, 60vw, 280px);
        height: clamp(220px, 60vw, 280px);
    }

    .tasks-section {
        padding: 1.25em;
        min-height: 300px;
        max-height: 450px;
    }

    .tasks-list {
        min-height: 150px;
        max-height: 300px;
    }
}

@media (max-width: 480px) {
    body {
        padding-top: 70px;
    }

    .main-wrapper {
        padding: 0.75rem;
        gap: 1rem;
    }

    .timer-section {
        padding: 0.75rem;
        gap: 1rem;
    }

    .tasks-section {
        padding: 1em;
        min-height: 280px;
        max-height: 400px;
        border-radius: 1.5em;
    }

    .tasks-list {
        min-height: 120px;
        max-height: 250px;
    }

    .add-task-box {
        margin-top: 0.75rem;
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

    .timer-wrapper {
        width: clamp(200px, 70vw, 250px);
        height: clamp(200px, 70vw, 250px);
    }

    .timer-text {
        font-size: clamp(1.75rem, 10vw, 2.5rem);
    }

    .tasks-title {
        font-size: 1.25rem;
    }
}