<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A beautiful Pomodoro timer to help you focus and be productive">
    <title>Pomodoro Timer</title>
    <link rel="stylesheet" href="pomodorostyle-css.php">
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

    <div class="main-wrapper">
        <!-- Left Side: Timer Section  -->
        <div class="timer-section">
            <!-- Mode Selector -->
            <div class="mode-selector">
                <button class="mode-btn active" data-mode="pomodoro">Pomodoro</button>
                <button class="mode-btn" data-mode="short-break">Short Break</button>
                <button class="mode-btn" data-mode="long-break">Long Break</button>
            </div>

            <!-- Circular Timer  - Outer Ring Only -->
            <div class="timer-wrapper">
                <div class="timer-circle">
                    <!-- Progress ring (outer ring only, no inner ring) -->
                    <div class="timer-progress-ring" id="progress-ring"></div>
                    <!-- Time text centered inside -->
                    <div class="timer-text" id="timer">25:00</div>
                </div>
            </div>

            <!-- Control Buttons -->
            <div class="button-wrapper">
                <button id="start-btn">START</button>
            </div>
        </div>

        <!-- Right Side: Tasks Section -->
        <div class="tasks-section">
            <div class="tasks-header">
                <h2 class="tasks-title">Tasks</h2>
            </div>
            
            <div class="tasks-list" id="tasks-list">
                <!-- Tasks will be added here dynamically -->
            </div>
            
            <div class="add-task-box" id="add-task-box">
                <input type="text" id="task-input" placeholder="+ Add tasks here." />
            </div>
        </div>

    </div>

    <script src="../SharedUtilities/utils.js"></script>
    <script src="../SharedNavigationBar/navbar.js"></script>
    <script src="pomodoro.js"></script>
</body>
</html>
