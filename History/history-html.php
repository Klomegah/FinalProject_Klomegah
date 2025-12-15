
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session History - LockIn</title>
    <link rel="stylesheet" href="historycss.php">
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
              

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <header class="page-header">
                <h1> Session History</h1>
                <p>View all your past Pomodoro Sessions and Feynman Notes</p>
            </header>

            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All Time</button>
                <button class="filter-btn" data-filter="today">Today</button>
                <button class="filter-btn" data-filter="week">This Week</button>
                <button class="filter-btn" data-filter="month">This Month</button>
            </div>

            <!-- Sessions List -->
            <div id="sessions-container" class="sessions-list">
                <!-- Sessions will be loaded here by JavaScript -->
                <div class="loading">Loading sessions...</div>
            </div>

        
         <!-- Navigation Buttons -->
        <div class="navigation-buttons">
            <button type="button" class="btn-secondary" onclick="window.location.href='../PomodoroPages/pomodoro-html.php'">Back to Timer</button>
            <button type="button" class="btn-secondary" onclick="window.location.href='../FeynmanPages/feynmannotes-html.php'">Back to Feynman Notes</button>
        </div>
        
    </main>


    


    <script src="../SharedUtilities/utils.js"></script>
    <script src="../SharedNavigationBar/navbar.js"></script>
    <script src="history.js"></script>
</body>
</html>