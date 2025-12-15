<?php
/**
 * Index Page - Landing Page Router
 * 
 * Why: This file acts as the entry point and handles routing:
 * 1. Checks if user is logged in (session exists)
 * 2. If logged in: redirects to Pomodoro timer page
 * 3. If not logged in: shows the landing page
 * 
 * Alternative: Could use a separate router file, but for simplicity, 
 * we handle it here
 */

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session (suppress warnings if session already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle logout/clear session if requested
// Why: Allows users to view landing page even if logged in
if (isset($_GET['logout']) || isset($_GET['clear'])) {
    // Clear all session data
    $_SESSION = array();
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    // Destroy the session
    session_destroy();
    // Redirect to clean URL without query parameters
    header('Location: index.php');
    exit();
}

// IMPORTANT: Landing page should ALWAYS be accessible
// No automatic redirects - users can navigate to app via links
// This ensures the landing page works for everyone, logged in or not
// If you're being redirected, add ?clear=1 to the URL to clear session

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LockIn - A personal study productivity web application for focused learning and reflective study">
    <title>LockIn - Focus. Learn. Reflect.</title>
    <link rel="stylesheet" href="landing.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <span class="logo-text">LockIn</span>
            </div>
            
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#faq">FAQ</a>
            </div>

            <div class="nav-actions">
                <a href="pomodoro/pomodoro.html" class="btn-secondary">Go to App</a>
                <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
                    <!-- User is logged in - show logout option -->
                    <a href="?logout=1" class="btn-primary">Log Out</a>
                <?php else: ?>
                    <!-- User is not logged in - show login/signup -->
                    <a href="Login And SignUp/login.html" class="btn-secondary">Log In</a>
                    <a href="Login And SignUp/signup.html" class="btn-primary">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Focus. Learn. Reflect.
                </h1>
                <p class="hero-subtitle">
                    LockIn helps you manage your focus, study, and learning sessions through structured time management and reflection tools. Combine Pomodoro-style focus sessions with Feynman-inspired reflective learning to achieve deeper understanding.
                </p>

                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">∞</div>
                        <div class="stat-label">Free Forever</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">⚡</div>
                        <div class="stat-label">Instant Setup</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">🔒</div>
                        <div class="stat-label">Privacy First</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="visual-card">
                    <div class="timer-preview">
                        <div class="timer-circle">
                            <span class="timer-time">25:00</span>
                            <span class="timer-label">Focus Session</span>
                        </div>
                    </div>
                    <div class="floating-elements">
                        <span class="float-1">⏱</span>
                        <span class="float-2">📚</span>
                        <span class="float-3">💡</span>
                        <span class="float-4">✨</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title">Why LockIn?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🍅</div>
                    <h3 class="feature-title">Pomodoro Timer</h3>
                    <p class="feature-description">
                        Structured focus sessions with customizable work and break intervals. 
                        Track your time and build consistent study habits.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🧠</div>
                    <h3 class="feature-title">Feynman Reflection</h3>
                    <p class="feature-description">
                        After each session, explain what you learned in simple terms. 
                        This deepens understanding and improves long-term retention.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Focus Mode</h3>
                    <p class="feature-description">
                        Distraction-free environment that helps you stay locked in. 
                        Minimize interruptions and maximize productivity.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Analytics & Insights</h3>
                    <p class="feature-description">
                        Track your progress with detailed analytics. 
                        See your focus streaks, total hours, and learning patterns.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3 class="feature-title">Calendar Integration</h3>
                    <p class="feature-description">
                        Sync with Google Calendar to schedule focus sessions 
                        and receive reminders for planned study periods.
                    </p>
                    
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3 class="feature-title">Customizable Themes</h3>
                    <p class="feature-description">
                        Choose between light and dark modes for optimal comfort. 
                        Personalize your workspace to match your preferences.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Set Your Timer</h3>
                    <p class="step-description">
                        Choose your focus duration (typically 25 minutes) and break length. 
                        Customize intervals to match your workflow.
                    </p>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Focus & Work</h3>
                    <p class="step-description">
                        Activate Focus Mode to minimize distractions. 
                        Work on your tasks with full concentration during the timer.
                    </p>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <h3 class="step-title">Track Progress</h3>
                    <p class="step-description">
                        Review your analytics dashboard to see your focus streaks, 
                        total hours, and learning reflections over time.
                    </p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Reflect & Learn</h3>
                    <p class="step-description">
                        After each session, use the Feynman method to explain 
                        what you learned. Simplify complex concepts in your own words.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>What is LockIn and how does it work?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        LockIn is a personal productivity web application that combines Pomodoro-style 
                        focus timers with Feynman-inspired reflection techniques. You set a timer, 
                        focus on your work, then reflect on what you learned to deepen understanding.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Is LockIn free to use?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        Yes! LockIn is completely free to use. Create an account and start 
                        improving your focus and learning habits today.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>What is the Feynman Reflection method?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        The Feynman method involves explaining what you learned in simple terms, 
                        as if teaching someone else. This helps identify gaps in understanding 
                        and reinforces learning through active recall.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Can I use LockIn on mobile devices?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        LockIn is a web application that works on all devices with a modern browser. 
                        Simply visit the website on your phone, tablet, or computer.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to improve your focus?</h2>
                <p class="cta-description">
                    Join LockIn today and start building better study habits through 
                    structured focus sessions and reflective learning.
                </p>
                <div class="cta-actions">
                    <a href="Login And SignUp/signup.html" class="btn-primary-large">Get Started</a>
                    <a href="Login And SignUp/login.html" class="btn-outline-large">Already have an account? Log In</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="logo">
                        <span class="logo-text">LockIn</span>
                    </div>
                    <p class="footer-tagline">Focus. Learn. Reflect.</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Product</h4>
                        <a href="#features">Features</a>
                        <a href="#how-it-works">How It Works</a>
                        <a href="#faq">FAQ</a>
                    </div>
                    <div class="footer-column">
                        <h4>Account</h4>
                        <a href="Login And SignUp/login.html">Log In</a>
                        <a href="Login And SignUp/signup.html">Sign Up</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 LockIn. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="landing.js"></script>
</body>
</html>

