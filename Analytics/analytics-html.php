<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Analytics & Reports - LockIn">
    <title>Analytics & Reports - LockIn</title>
    <link rel="stylesheet" href="analytics-css.php">
    <link rel="stylesheet" href="../SharedNavigationBar/navbar-css.php">
    <link rel="stylesheet" href="../SharedUtilities/utils-css.php">
    <script src="chart.min.js"></script>
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
                <!-- User dropdown and back button will be injected here by navbar.js -->
            </div>
        </div>
    </nav>

    <div class="analytics-container">
        <div class="analytics-header">
            <h1>Analytics & Reports</h1>
            <p>Track your study progress and productivity</p>
        </div>

        <!-- Time Period Filters -->
        <div class="time-filters">
            <button class="filter-btn active" data-period="all">All Time</button>
            <button class="filter-btn" data-period="month">This Month</button>
            <button class="filter-btn" data-period="week">This Week</button>
            <button class="filter-btn" data-period="today">Today</button>
        </div>

        <!-- Overview Cards -->
        <div class="overview-cards">
            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="total-sessions">0</h3>
                    <p>Total Sessions</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="total-hours">0</h3>
                    <p>Study Hours</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="completion-rate">0%</h3>
                    <p>Task Completion</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="current-streak">0</h3>
                    <p>Day Streak</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <h2>Sessions Over Time</h2>
                <canvas id="sessions-chart"></canvas>
            </div>

            <div class="chart-card">
                <h2>Study Time by Day</h2>
                <canvas id="time-chart"></canvas>
            </div>
        </div>

        <!-- Feynman Notes Section -->
        <div class="feynman-section">
            <h2 class="section-title"> Feynman Notes Analytics</h2>
            
            <div class="overview-cards">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3 id="total-notes">0</h3>
                        <p>Total Notes</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3 id="notes-completion-rate">0%</h3>
                        <p>Sessions with Notes</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3 id="last-note-date">N/A</h3>
                        <p>Last Note</p>
                    </div>
                </div>
            </div>

            <!-- Sessions With/Without Notes Chart -->
            <div class="chart-card">
                <h2>Sessions with Notes</h2>
                <canvas id="notes-chart"></canvas>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="navigation-buttons">
            <button type="button" class="btn-secondary" onclick="window.location.href='../PomodoroPages/pomodoro-html.php'">Back to Timer</button>
            <button type="button" class="btn-secondary" onclick="window.location.href='../FeynmanPages/feynmannotes-html.php'">Back to Feynman Notes</button>
        </div>
    </div>

    <script src="../SharedUtilities/utils.js"></script>
    <script src="../SharedNavigationBar/navbar.js"></script>
    <script src="lockinanalytics.js"></script>
</body>
</html>


