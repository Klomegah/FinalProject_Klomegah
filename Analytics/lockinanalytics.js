let allSessions = [];
let currentPeriod = 'all';
let sessionsChart = null;
let timeChart = null;
let notesChart = null; // Add notes chart

// Fetch and display analytics data
async function loadAnalytics() {
    console.log('loadAnalytics function called');
    
    try {
        console.log('Fetching sessions from API...');
        
        // Get all sessions from the database
        const response = await fetch('../Sessions/get_sessions_stats.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        console.log('Response received:', response.status);
        
        const data = await response.json();
        
        console.log('Analytics data received:', data);
        console.log('Number of sessions in response:', data.sessions ? data.sessions.length : 0);
        
        if (data.success && data.sessions && data.sessions.length > 0) {
            allSessions = data.sessions;
            console.log('Stored', allSessions.length, 'sessions in allSessions array');
            console.log('Session dates:', allSessions.map(s => s.session_date).slice(0, 5)); // Show first 5 dates
            applyFilter(currentPeriod);
            
            // Load Feynman notes stats
            await loadNotesStats();
        } else {
            console.log('No sessions found or data.success is false');
            showNoDataMessage();
        }
    } catch (error) {
        showNoDataMessage();
    }
}

// Load Feynman Notes statistics
async function loadNotesStats() {
    console.log('Loading notes stats...'); // debug log
    
    try {
        const response = await fetch('../Notes/get_notes_stats.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        console.log('Notes stats response:', response.status); // Debug log
        
        const data = await response.json();
        
        console.log('Notes stats data:', data); // Debug log
        
        if (data.success && data.stats) {
            displayNotesStats(data.stats);
            createNotesChart(data.stats);
        } else {
            console.error('Failed to load notes stats:', data);
        }
    } catch (error) {
        console.error('Error loading notes stats:', error); // Changed from silent fail
    }
}

// Filter sessions by time period
function filterSessionsByPeriod(sessions, period) {
    if (period === 'all') return sessions;
    
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    
    return sessions.filter(session => {
        const sessionDate = new Date(session.session_date);
        
        switch(period) {
            case 'today':
                const sessionDay = new Date(sessionDate.getFullYear(), sessionDate.getMonth(), sessionDate.getDate());
                return sessionDay.getTime() === today.getTime();
                
            case 'week':
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                return sessionDate >= weekAgo;
                
            case 'month':
                const monthAgo = new Date(today);
                monthAgo.setMonth(monthAgo.getMonth() - 1);
                return sessionDate >= monthAgo;
                
            default:
                return true;
        }
    });
}

// Apply filter and update display
function applyFilter(period) {
    currentPeriod = period;
    const filteredSessions = filterSessionsByPeriod(allSessions, period);
    
    console.log(`Filtering for ${period}:`, filteredSessions.length, 'sessions found');
    
    // Remove "no data" message if it exists
    const noDataMsg = document.querySelector('.no-data-message');
    if (noDataMsg) noDataMsg.remove();
    
    if (filteredSessions.length > 0) {
        displayStats(filteredSessions);
        createCharts(filteredSessions);
    } else {
        
        // Clear stats
        document.getElementById('total-sessions').textContent = '0';
        document.getElementById('total-hours').textContent = '0h 0m';
        document.getElementById('completion-rate').textContent = '0';
        document.getElementById('current-streak').textContent = '0';
        
        // Destroy existing charts
        if (sessionsChart) {
            sessionsChart.destroy();
            sessionsChart = null;
        }
        if (timeChart) {
            timeChart.destroy();
            timeChart = null;
        }
        
        showNoDataMessage();
    }
}

function displayStats(sessions) {
    console.log('Sessions data:', sessions); // Debug log
    
    // Filter to only Pomodoro sessions (exclude breaks)
    const pomodoroSessions = sessions.filter(s => s.mode === 'pomodoro');
    console.log('Pomodoro sessions:', pomodoroSessions.length, 'out of', sessions.length, 'total sessions');
    
    // Calculate statistics (only for Pomodoro sessions)
    const totalSessions = pomodoroSessions.length;
    const totalMinutes = pomodoroSessions.reduce((sum, s) => sum + (s.duration / 60), 0);
    const totalHours = Math.floor(totalMinutes / 60);
    const remainingMinutes = Math.floor(totalMinutes % 60);
    
    // Count completed tasks - handle both string and array formats (only Pomodoro sessions)
    const completedTasks = pomodoroSessions.reduce((sum, s) => {
        if (!s.completed_tasks) return sum;
        
        // If it's a string (JSON), parse it
        if (typeof s.completed_tasks === 'string') {
            try {
                const parsed = JSON.parse(s.completed_tasks);
                return sum + (Array.isArray(parsed) ? parsed.length : 0);
            } catch (e) {
                return sum;
            }
        }
        
        // If it's already an array
        if (Array.isArray(s.completed_tasks)) {
            return sum + s.completed_tasks.length;
        }
        
        return sum;
    }, 0);
    
    const avgSession = totalSessions > 0 ? Math.floor(totalMinutes / totalSessions) : 0;
    
    console.log('Stats calculated:', { totalSessions, totalHours, remainingMinutes, completedTasks, avgSession }); // Debug log
    
    // Update DOM
    document.getElementById('total-sessions').textContent = totalSessions;
    document.getElementById('total-hours').textContent = `${totalHours}h ${remainingMinutes}m`;
    document.getElementById('completion-rate').textContent = completedTasks;
    document.getElementById('current-streak').textContent = calculateStreak(pomodoroSessions);
}

function calculateStreak(sessions) {
    if (!sessions || sessions.length === 0) return 0;
    
    // Filter to only Pomodoro sessions (not breaks)
    const pomodoroSessions = sessions.filter(s => s.mode === 'pomodoro');
    if (pomodoroSessions.length === 0) return 0;
    
    // Get unique dates (as date strings) from sessions - handle timezone properly
    const sessionDates = new Set();
    pomodoroSessions.forEach(session => {
        // Parse the session date - handle both UTC and local time
        const sessionDate = new Date(session.session_date);
        // Convert to local date string (YYYY-MM-DD) to avoid timezone issues
        const year = sessionDate.getFullYear();
        const month = String(sessionDate.getMonth() + 1).padStart(2, '0');
        const day = String(sessionDate.getDate()).padStart(2, '0');
        const dateString = `${year}-${month}-${day}`;
        sessionDates.add(dateString);
    });
    
    // Start from today (local time) and count backwards
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const todayYear = today.getFullYear();
    const todayMonth = String(today.getMonth() + 1).padStart(2, '0');
    const todayDay = String(today.getDate()).padStart(2, '0');
    const todayString = `${todayYear}-${todayMonth}-${todayDay}`;
    
    let streak = 0;
    let checkDate = new Date(today);
    
    // Count consecutive days backwards from today
    while (true) {
        const year = checkDate.getFullYear();
        const month = String(checkDate.getMonth() + 1).padStart(2, '0');
        const day = String(checkDate.getDate()).padStart(2, '0');
        const dateString = `${year}-${month}-${day}`;
        
        if (sessionDates.has(dateString)) {
            streak++;
            // Move to previous day
            checkDate.setDate(checkDate.getDate() - 1);
        } else {
            // No session on this day - streak is broken
            break;
        }
    }
    
    return streak;
}

function createCharts(sessions) {
    // Check if chart elements exist
    const sessionsCanvas = document.getElementById('sessions-chart');
    const timeCanvas = document.getElementById('time-chart');
    
    if (!sessionsCanvas || !timeCanvas) {
        console.error('Chart canvas elements not found');
        return;
    }
    
    // Destroy existing charts before creating new ones
    if (sessionsChart) {
        sessionsChart.destroy();
    }
    if (timeChart) {
        timeChart.destroy();
    }
    
    // Group sessions by date (only Pomodoro sessions for charts)
    const pomodoroSessions = sessions.filter(s => s.mode === 'pomodoro');
    const sessionsByDate = {};
    pomodoroSessions.forEach(session => {
        // Use consistent date format (YYYY-MM-DD) for proper sorting
        const sessionDate = new Date(session.session_date);
        const year = sessionDate.getFullYear();
        const month = String(sessionDate.getMonth() + 1).padStart(2, '0');
        const day = String(sessionDate.getDate()).padStart(2, '0');
        const dateKey = `${year}-${month}-${day}`;
        
        // For display, use locale date string
        const dateDisplay = sessionDate.toLocaleDateString();
        
        if (!sessionsByDate[dateKey]) {
            sessionsByDate[dateKey] = { count: 0, duration: 0, displayDate: dateDisplay };
        }
        sessionsByDate[dateKey].count++;
        sessionsByDate[dateKey].duration += session.duration / 60;
    });
    
    // Sort by date key (YYYY-MM-DD format ensures proper chronological sorting)
    const dateKeys = Object.keys(sessionsByDate).sort();
    const dates = dateKeys.map(key => sessionsByDate[key].displayDate);
    const sessionCounts = dateKeys.map(key => sessionsByDate[key].count);
    const sessionDurations = dateKeys.map(key => Math.floor(sessionsByDate[key].duration));
    
    console.log('Chart data:', { dates, sessionCounts, sessionDurations });
    
    // Sessions chart
    const sessionsCtx = sessionsCanvas.getContext('2d');
    sessionsChart = new Chart(sessionsCtx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Sessions',
                data: sessionCounts,
                borderColor: '#8672FF',
                backgroundColor: 'rgba(134, 114, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Time chart
    const timeCtx = timeCanvas.getContext('2d');
    timeChart = new Chart(timeCtx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Minutes',
                data: sessionDurations,
                backgroundColor: '#8672FF'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Display Feynman Notes statistics
function displayNotesStats(stats) {
    document.getElementById('total-notes').textContent = stats.total_notes;
    document.getElementById('notes-completion-rate').textContent = stats.completion_rate + '%';
    
    // Format last note date
    if (stats.last_note_date) {
        const lastDate = new Date(stats.last_note_date);
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        document.getElementById('last-note-date').textContent = lastDate.toLocaleDateString('en-US', options);
    } else {
        document.getElementById('last-note-date').textContent = 'N/A';
    }
}

// Create chart showing sessions with/without notes
function createNotesChart(stats) {
    const notesCanvas = document.getElementById('notes-chart');
    if (!notesCanvas) return;
    
    // Destroy existing chart
    if (notesChart) {
        notesChart.destroy();
    }
    
    const ctx = notesCanvas.getContext('2d');
    notesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['With Notes', 'Without Notes'],
            datasets: [{
                data: [stats.sessions_with_notes, stats.sessions_without_notes],
                backgroundColor: ['#8672FF', '#E5E5E5'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 14,
                            family: 'Poppins'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = stats.sessions_with_notes + stats.sessions_without_notes;
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

function showNoDataMessage() {
    const container = document.querySelector('.analytics-container');
    const existing = container.querySelector('.no-data-message');
    
    // Don't add multiple "no data" messages
    if (existing) return;
    
    const noDataDiv = document.createElement('div');
    noDataDiv.className = 'no-data-message';
    noDataDiv.style.cssText = 'text-align: center; padding: 3rem; color: white;';
    noDataDiv.innerHTML = `
        <h2>No data yet</h2>
        <p>Complete some Pomodoro sessions to see your analytics!</p>
    `;
    
    container.appendChild(noDataDiv);
}

// Set up filter button event listeners
document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            filterButtons.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            btn.classList.add('active');
            
            // Apply filter
            const period = btn.dataset.period;
            console.log('Filter clicked:', period);
            applyFilter(period);
        });
    });
});

// Load analytics on page load
loadAnalytics();

