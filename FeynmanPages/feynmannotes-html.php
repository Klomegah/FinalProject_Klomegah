<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Feynman Reflection Notes - Explain what you learned">
    <title>Feynman Notes - LockIn</title>
    
    <link rel="stylesheet" href="feynmannotes-css.php">
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
                <!-- User dropdown will be injected here by navbar.js -->
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="title">Feynman Reflection Notes</h1>
        <hr>
        <p class="subtitle">Explain what you learned in simple terms, as if teaching someone else</p>
        
        <div class="session-info" id="session-info">
            <p><strong>Session Date:</strong> <span id="session-date"></span></p>
            <p><strong>Duration:</strong> <span id="session-duration"></span> minutes</p>
        </div>

        <div class="notes-section">
            <div class="notes-step">
                <h2>Step 1: Initial Explanation</h2>
                <p class="step-description">Write your understanding of what you learned during this session.</p>
                <textarea 
                    id="initial-explanation" 
                    placeholder="Explain what you learned in your own words..."
                    rows="8"
                ></textarea>
            </div>

            <div class="notes-step">
                <h2>Step 2: Simplification</h2>
                <p class="step-description">Now rewrite your explanation in simpler terms, as if explaining to a beginner.</p>
                <textarea 
                    id="simplified-explanation" 
                    placeholder="Simplify your explanation - use simple language and avoid jargon..."
                    rows="8"
                ></textarea>
            </div>

            <div class="notes-step">
                <h2>Step 3: Key Concepts</h2>
                <p class="step-description">List the main concepts or ideas you focused on.</p>
                <textarea
                    id="key-concepts" 
                    placeholder="List the key concepts, one per line..."
                    rows="5"
            ></textarea>
            </div>
        </div>

        <div class="actions">
            <button type="button" class="btn-secondary" onclick="window.location.href='../PomodoroPages/pomodoro-html.php'">Back to Timer</button>
            <button type="button" class="btn-primary" id="save-btn">Save Notes</button>
        </div>
    </div>

    <script src="../SharedNavigationBar/navbar.js"></script>
    <script src="feynmannotes.js"></script>
</body>
</html>

