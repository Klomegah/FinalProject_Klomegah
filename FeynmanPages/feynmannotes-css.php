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
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    padding-top: 100px;
    padding-bottom: 4rem;
}

/* App Navbar - Matches Landing Page Style */
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



.container {
    background: var(--base-color);
    border: 2px solid var(--input-color);
    border-radius: 2em;
    padding: 2em 3em;
    max-width: 900px;
    width: 100%;
    color: var(--text-color);
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.title {
    font-size: clamp(2em, 5vw, 2.5em);
    font-weight: 700;
    text-align: center;
    margin-bottom: 0.5rem;
    letter-spacing: -0.02em;
}

hr {
    width: 100%;
    background: linear-gradient(90deg, transparent, var(--accent-color, #8672FF), transparent);
    height: 2px;
    border: none;
    border-radius: 1em;
    opacity: 0.4;
    margin: 1rem 0 1.5rem;
}

.subtitle {
    text-align: center;
    color: #6B6B6B;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.session-info {
    background: var(--input-color);
    padding: 1rem 1.5rem;
    border-radius: 1em;
    margin-bottom: 2rem;
    font-size: 0.95rem;
    border: 2px solid var(--input-color);
}

.session-info p {
    margin: 0.5rem 0;
}

.notes-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    margin-bottom: 2rem;
}

.notes-step {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.notes-step h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.step-description {
    opacity: 0.9;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--input-color);
    border-radius: 1em;
    background: var(--input-color);
    color: var(--text-color);
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    resize: vertical;
    transition: border-color 0.2s, background 0.2s;
}

textarea::placeholder {
    color: #6B6B6B;
}

textarea:hover {
    border-color: var(--accent-color);
}

textarea:focus {
    outline: none;
    border-color: var(--accent-color);
}

.actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

button {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 1rem;
    padding: 0.75rem 2rem;
    border-radius: 0.8em;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background-color: var(--accent-color);
    color: var(--base-color);
}

.btn-primary:hover {
    background-color: var(--text-color);
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--input-color);
    color: var(--text-color);
    border: 2px solid var(--input-color);
}

.btn-secondary:hover {
    border-color: var(--accent-color);
    color: var(--accent-color);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .app-nav-container {
        padding: 0 var(--spacing-sm);
    }

    .logo-text {
        font-size: 1.25rem;
    }

    .app-nav-btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    body {
        padding-top: 100px;
        padding-bottom: 3rem;
    }

    .container {
        padding: 2em 1.5em;
    }

    .actions {
        flex-direction: column;
    }

    button {
        width: 100%;
    }
}
