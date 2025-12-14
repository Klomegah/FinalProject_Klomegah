<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$userId = $_SESSION['user_id'];

// Debug: Check if any sessions exist at all for this user
$debugStmt = $con->prepare("SELECT COUNT(*) as total, mode FROM pomodoro_sessions WHERE user_id = ? GROUP BY mode");
$debugStmt->bind_param("i", $userId);
$debugStmt->execute();
$debugResult = $debugStmt->get_result();
$debugSessions = [];
while ($row = $debugResult->fetch_assoc()) {
    $debugSessions[] = $row;
}
$debugStmt->close();

// Get total sessions (only Pomodoro sessions)
$stmt = $con->prepare("SELECT COUNT(*) as total FROM pomodoro_sessions WHERE user_id = ? AND mode = 'pomodoro'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$totalSessions = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Get total study time (in seconds, only Pomodoro sessions)
$stmt = $con->prepare("SELECT SUM(duration) as total_seconds FROM pomodoro_sessions WHERE user_id = ? AND mode = 'pomodoro'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$totalSeconds = $stmt->get_result()->fetch_assoc()['total_seconds'] ?? 0;
$totalHours = floor($totalSeconds / 3600);
$totalMinutes = floor(($totalSeconds % 3600) / 60);
$stmt->close();

// Get task statistics
$stmt = $con->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$taskStats = $stmt->get_result()->fetch_assoc();
$totalTasks = $taskStats['total'] ?? 0;
$completedTasks = $taskStats['completed'] ?? 0;
$completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
$stmt->close();

// Get total notes count
$stmt = $con->prepare("SELECT COUNT(*) as total FROM feynman_notes WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$totalNotes = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Get average session duration
$avgDuration = $totalSessions > 0 ? round($totalSeconds / $totalSessions) : 0;
$avgMinutes = floor($avgDuration / 60);

// Get longest session
$stmt = $con->prepare("SELECT MAX(duration) as longest FROM pomodoro_sessions WHERE user_id = ? AND mode = 'pomodoro'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$longestSession = $stmt->get_result()->fetch_assoc()['longest'] ?? 0;
$longestMinutes = floor($longestSession / 60);
$stmt->close();

// Get sessions per day (last 7 days)
$stmt = $con->prepare("SELECT DATE(session_date) as date, COUNT(*) as session_count, SUM(duration) as total_duration FROM pomodoro_sessions WHERE user_id = ? AND mode = 'pomodoro' AND session_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(session_date) ORDER BY date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$dailyStats = [];
while ($row = $result->fetch_assoc()) {
    $dailyStats[] = [
        'date' => $row['date'],
        'session_count' => $row['session_count'],
        'total_duration' => $row['total_duration'],
        'total_minutes' => floor($row['total_duration'] / 60)
    ];
}
$stmt->close();

// Get sessions per month (last 6 months)
$stmt = $con->prepare("SELECT DATE_FORMAT(session_date, '%Y-%m') as month, COUNT(*) as session_count, SUM(duration) as total_duration FROM pomodoro_sessions WHERE user_id = ? AND mode = 'pomodoro' AND session_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) GROUP BY DATE_FORMAT(session_date, '%Y-%m') ORDER BY month DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$monthlyStats = [];
while ($row = $result->fetch_assoc()) {
    $monthlyStats[] = [
        'month' => $row['month'],
        'session_count' => $row['session_count'],
        'total_duration' => $row['total_duration'],
        'total_minutes' => floor($row['total_duration'] / 60),
        'total_hours' => floor($row['total_duration'] / 3600)
    ];
}
$stmt->close();

// Get most productive day of week
$stmt = $con->prepare("SELECT DAYNAME(session_date) as day_name, DAYOFWEEK(session_date) as day_number, COUNT(*) as session_count, SUM(duration) as total_duration FROM pomodoro_sessions WHERE user_id = ? AND mode = 'pomodoro' GROUP BY DAYNAME(session_date), DAYOFWEEK(session_date) ORDER BY session_count DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$dayStats = [];
while ($row = $result->fetch_assoc()) {
    $dayStats[] = [
        'day_name' => $row['day_name'],
        'day_number' => $row['day_number'],
        'session_count' => $row['session_count'],
        'total_duration' => $row['total_duration'],
        'total_minutes' => floor($row['total_duration'] / 60)
    ];
}
$stmt->close();

// Get study streak (consecutive days with at least one session)
// This is simplified - counts distinct days in last 30 days that have sessions
$stmt = $con->prepare("SELECT COUNT(DISTINCT DATE(session_date)) as distinct_days FROM pomodoro_sessions WHERE user_id = ? AND mode = 'pomodoro' AND session_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
$stmt->bind_param("i", $userId);
$stmt->execute();
$streakResult = $stmt->get_result()->fetch_assoc();
$studyStreak = $streakResult['distinct_days'] ?? 0;
$stmt->close();

echo json_encode([
    'success' => true,
    'debug' => [
        'user_id' => $userId,
        'all_sessions_by_mode' => $debugSessions
    ],
    'reports' => [
        'total_sessions' => (int)$totalSessions,
        'total_study_time' => [
            'seconds' => (int)$totalSeconds,
            'minutes' => $totalMinutes,
            'hours' => $totalHours,
            'formatted' => "{$totalHours}h {$totalMinutes}m"
        ],
        'average_session_duration' => [
            'seconds' => $avgDuration,
            'minutes' => $avgMinutes,
            'formatted' => "{$avgMinutes} minutes"
        ],
        'longest_session' => [
            'seconds' => (int)$longestSession,
            'minutes' => $longestMinutes,
            'formatted' => "{$longestMinutes} minutes"
        ],
        'total_notes' => (int)$totalNotes,
        'tasks' => [
            'total' => (int)$totalTasks,
            'completed' => (int)$completedTasks,
            'pending' => (int)($totalTasks - $completedTasks),
            'completion_rate' => $completionRate
        ],
        'daily_stats' => $dailyStats,
        'monthly_stats' => $monthlyStats,
        'day_of_week_stats' => $dayStats,
        'study_streak' => (int)$studyStreak
    ]
]);
?>
