let allSessions = [];
let currentFilter = 'all';

// Load sessions on page load
document.addEventListener('DOMContentLoaded', () => {
    loadSessions();
    setupFilterButtons();
});

// Load sessions from API
async function loadSessions() {
    const container = document.getElementById('sessions-container');
    container.innerHTML = '<div class="loading">Loading sessions...</div>';
    
    try {
        const response = await fetch('../Sessions/get_sessions_stats.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        const data = await response.json();
        
        if (data.success && data.sessions) {
            allSessions = data.sessions;
            filterAndDisplaySessions(currentFilter);
        } else {
            container.innerHTML = '<div class="no-sessions">No sessions found. Complete a Pomodoro session to see it here!</div>';
        }
    } catch (error) {
        console.error('Error loading sessions:', error);
        container.innerHTML = '<div class="no-sessions">Error loading sessions. Please refresh the page.</div>';
    }
}

// Setup filter button listeners
function setupFilterButtons() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Filter sessions
            currentFilter = btn.dataset.filter;
            filterAndDisplaySessions(currentFilter);
        });
    });
}

// Filter sessions by time period
function filterAndDisplaySessions(filter) {
    const now = new Date();
    let filteredSessions = allSessions;
    
    if (filter === 'today') {
        filteredSessions = allSessions.filter(session => {
            const sessionDate = new Date(session.session_date);
            return sessionDate.toDateString() === now.toDateString();
        });
    } else if (filter === 'week') {
        const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
        filteredSessions = allSessions.filter(session => {
            const sessionDate = new Date(session.session_date);
            return sessionDate >= weekAgo;
        });
    } else if (filter === 'month') {
        const monthAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
        filteredSessions = allSessions.filter(session => {
            const sessionDate = new Date(session.session_date);
            return sessionDate >= monthAgo;
        });
    }
    
    displaySessions(filteredSessions);
}

// Display sessions in the UI
function displaySessions(sessions) {
    const container = document.getElementById('sessions-container');
    
    if (sessions.length === 0) {
        container.innerHTML = '<div class="no-sessions">No sessions found for this time period.</div>';
        return;
    }
    
    container.innerHTML = sessions.map(session => createSessionCard(session)).join('');
    
    // Add event listeners for delete buttons
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => deleteSession(btn.dataset.sessionId));
    });
    
    // Add event listeners for view notes buttons
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => viewNotes(btn.dataset.sessionId));
    });
}

// Create HTML for session card
function createSessionCard(session) {
    const date = new Date(session.session_date);
    const formattedDate = date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    const notesBadge = session.has_notes
        ? '<span class="notes-badge has-notes">✓ Has Notes</span>'
        : '<span class="notes-badge no-notes">No Notes</span>';
    
    const viewNotesBtn = session.has_notes
        ? `<button class="btn btn-view" data-session-id="${session.session_id}">View Notes</button>`
        : '';
    
    return `
        <div class="session-card">
            <div class="session-header">
                <div>
                    <div class="session-date">${formattedDate}</div>
                </div>
                ${notesBadge}
            </div>
            
            <div class="session-actions">
                ${viewNotesBtn}
                <button class="btn-delete" data-session-id="${session.session_id}">Delete</button>
            </div>
        </div>
    `;
}

// Delete session
async function deleteSession(sessionId) {
    if (!confirm('Are you sure you want to delete this session? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch('../Sessions/delete_session.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ session_id: parseInt(sessionId) })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Remove from local array
            allSessions = allSessions.filter(s => s.session_id !== parseInt(sessionId));
            filterAndDisplaySessions(currentFilter);
            
            // Show success message
            alert('Session deleted successfully!');
        } else {
            alert('Failed to delete session: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error deleting session:', error);
        alert('Error deleting session. Please try again.');
    }
}

// View notes for session
function viewNotes(sessionId) {
    // Redirect to Feynman notes page with session_id parameter
    window.location.href = `../FeynmanPages/feynmannotes-html.php?session_id=${sessionId}`;
}
