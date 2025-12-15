<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Feynman Reflection Notes - Explain what you learned">
    <title>Feynman Notes - LockIn</title> 
    <link rel="stylesheet" href="feynmannotes-css.php">
    <link rel="stylesheet" href="../SharedNavigationBar/navbar-css.php">
    <link rel="stylesheet" href="../SharedUtilities/utils-css.php">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                <button 
                    type="button" 
                    id="initial-voice-btn" 
                    aria-label="Dictate initial explanation"
                    style="border:none;background:transparent;cursor:pointer;display:inline-flex;align-items:center;gap:0.25rem;margin-bottom:0.5rem;padding:0;"
                >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 8C6.25 4.82436 8.82436 2.25 12 2.25C15.1756 2.25 17.75 4.82436 17.75 8V11C17.75 14.1756 15.1756 16.75 12 16.75C8.82436 16.75 6.25 14.1756 6.25 11V8ZM12 3.75C9.65279 3.75 7.75 5.65279 7.75 8V11C7.75 13.3472 9.65279 15.25 12 15.25C14.3472 15.25 16.25 13.3472 16.25 11V8C16.25 5.65279 14.3472 3.75 12 3.75ZM10.25 8C10.25 7.58579 10.5858 7.25 11 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H11C10.5858 8.75 10.25 8.41421 10.25 8ZM4 9.25C4.41421 9.25 4.75 9.58579 4.75 10V11C4.75 15.0041 7.99594 18.25 12 18.25C16.0041 18.25 19.25 15.0041 19.25 11V10C19.25 9.58579 19.5858 9.25 20 9.25C20.4142 9.25 20.75 9.58579 20.75 10V11C20.75 15.5798 17.2314 19.3379 12.75 19.7183V22C12.75 22.4142 12.4142 22.75 12 22.75C11.5858 22.75 11.25 22.4142 11.25 22V19.7183C6.7686 19.3379 3.25 15.5798 3.25 11V10C3.25 9.58579 3.58579 9.25 4 9.25ZM9.25 11C9.25 10.5858 9.58579 10.25 10 10.25H14C14.4142 10.25 14.75 10.5858 14.75 11C14.75 11.4142 14.4142 11.75 14 11.75H10C9.58579 11.75 9.25 11.4142 9.25 11Z" fill="#1C274C"/>
                    </svg>
                    <span class="voice-label" style="font-size:0.85rem;color:#555;">Speak</span>
                </button>

                <textarea 
                    id="initial-explanation" 
                    placeholder="Explain what you learned in your own words..."
                    rows="8"
                ></textarea>
            </div>


            <div class="notes-step">
                <h2>Step 2: Simplification</h2>
                <p class="step-description">Now rewrite your explanation in simpler terms, as if explaining to a beginner.</p>

                <button 
                    type="button" 
                    id="simplified-voice-btn" 
                    aria-label="Dictate simplified explanation"
                    style="border:none;background:transparent;cursor:pointer;display:inline-flex;align-items:center;gap:0.25rem;margin-bottom:0.5rem;padding:0;"
                >

                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 8C6.25 4.82436 8.82436 2.25 12 2.25C15.1756 2.25 17.75 4.82436 17.75 8V11C17.75 14.1756 15.1756 16.75 12 16.75C8.82436 16.75 6.25 14.1756 6.25 11V8ZM12 3.75C9.65279 3.75 7.75 5.65279 7.75 8V11C7.75 13.3472 9.65279 15.25 12 15.25C14.3472 15.25 16.25 13.3472 16.25 11V8C16.25 5.65279 14.3472 3.75 12 3.75ZM10.25 8C10.25 7.58579 10.5858 7.25 11 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H11C10.5858 8.75 10.25 8.41421 10.25 8ZM4 9.25C4.41421 9.25 4.75 9.58579 4.75 10V11C4.75 15.0041 7.99594 18.25 12 18.25C16.0041 18.25 19.25 15.0041 19.25 11V10C19.25 9.58579 19.5858 9.25 20 9.25C20.4142 9.25 20.75 9.58579 20.75 10V11C20.75 15.5798 17.2314 19.3379 12.75 19.7183V22C12.75 22.4142 12.4142 22.75 12 22.75C11.5858 22.75 11.25 22.4142 11.25 22V19.7183C6.7686 19.3379 3.25 15.5798 3.25 11V10C3.25 9.58579 3.58579 9.25 4 9.25ZM9.25 11C9.25 10.5858 9.58579 10.25 10 10.25H14C14.4142 10.25 14.75 10.5858 14.75 11C14.75 11.4142 14.4142 11.75 14 11.75H10C9.58579 11.75 9.25 11.4142 9.25 11Z" fill="#1C274C"/>
                    </svg>
                    <span class="voice-label" style="font-size:0.85rem;color:#555;">Speak</span>

                </button>

                <textarea 
                    id="simplified-explanation" 
                    placeholder="Simplify your explanation - use simple language and avoid jargon..."
                    rows="8"
                ></textarea>

            </div>


            <div class="notes-step">
                <h2>Step 3: Key Concepts</h2>
                <p class="step-description">List the main concepts or ideas you focused on.</p>

                <button 
                    type="button" 
                    id="concepts-voice-btn" 
                    aria-label="Dictate key concepts"
                    style="border:none;background:transparent;cursor:pointer;display:inline-flex;align-items:center;gap:0.25rem;margin-bottom:0.5rem;padding:0;"
                >

                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 8C6.25 4.82436 8.82436 2.25 12 2.25C15.1756 2.25 17.75 4.82436 17.75 8V11C17.75 14.1756 15.1756 16.75 12 16.75C8.82436 16.75 6.25 14.1756 6.25 11V8ZM12 3.75C9.65279 3.75 7.75 5.65279 7.75 8V11C7.75 13.3472 9.65279 15.25 12 15.25C14.3472 15.25 16.25 13.3472 16.25 11V8C16.25 5.65279 14.3472 3.75 12 3.75ZM10.25 8C10.25 7.58579 10.5858 7.25 11 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H11C10.5858 8.75 10.25 8.41421 10.25 8ZM4 9.25C4.41421 9.25 4.75 9.58579 4.75 10V11C4.75 15.0041 7.99594 18.25 12 18.25C16.0041 18.25 19.25 15.0041 19.25 11V10C19.25 9.58579 19.5858 9.25 20 9.25C20.4142 9.25 20.75 9.58579 20.75 10V11C20.75 15.5798 17.2314 19.3379 12.75 19.7183V22C12.75 22.4142 12.4142 22.75 12 22.75C11.5858 22.75 11.25 22.4142 11.25 22V19.7183C6.7686 19.3379 3.25 15.5798 3.25 11V10C3.25 9.58579 3.58579 9.25 4 9.25ZM9.25 11C9.25 10.5858 9.58579 10.25 10 10.25H14C14.4142 10.25 14.75 10.5858 14.75 11C14.75 11.4142 14.4142 11.75 14 11.75H10C9.58579 11.75 9.25 11.4142 9.25 11Z" fill="#1C274C"/>
                    </svg>
                    <span class="voice-label" style="font-size:0.85rem;color:#555;">Speak</span>
                </button>

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

    <!-- Shared Utilities -->
    <script src="../SharedUtilities/utils.js"></script>
    <script src="../SharedNavigationBar/navbar.js"></script>
    <script src="feynmannotes.js"></script>

</body>
</html>

