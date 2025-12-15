// HISTORY PAGE - Shows all past Pomodoro sessions and Feynman notes

// Store all sessions in memory so we can filter them without making new API calls
let allSessions = [];
// Track which filter is currently active (all, today, week, month)
let currentFilter = 'all';

// When page loads, fetch sessions and set up filter buttons
document.addEventListener('DOMContentLoaded', () => {
    loadSessions();
    setupFilterButtons();
});

// Fetch all sessions from the database via API
async function loadSessions() {
    const container = document.getElementById('sessions-container');
    container.innerHTML = '<div class="loading">Loading sessions...</div>';
    
    const result = await apiRequest('../Sessions/get_sessions_stats.php', {
        method: 'GET'
    });
    
    if (result.success && result.data.success && result.data.sessions) {
        allSessions = result.data.sessions;
        filterAndDisplaySessions(currentFilter);
    } else {
        container.innerHTML = '<div class="no-sessions">No sessions found. Complete a Pomodoro session to see it here!</div>';
    }
}

// Set up click handlers for the filter buttons (All Time, This Month, This Week, Today)
function setupFilterButtons() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    // When a filter button is clicked, make it active and filter the sessions
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to the clicked button
            btn.classList.add('active');
            
            // Get the filter type from the button's data attribute and apply it
            currentFilter = btn.dataset.filter;
            filterAndDisplaySessions(currentFilter);
        });
    });
}

// Filter sessions based on the selected time period (today, week, month, or all)
function filterAndDisplaySessions(filter) {
    const now = new Date();
    let filteredSessions = allSessions;
    
    // Filter to show only today's sessions
    if (filter === 'today') {
        filteredSessions = allSessions.filter(session => {
            const sessionDate = new Date(session.session_date);
            // Check if session date matches today's date
            return sessionDate.toDateString() === now.toDateString();
        });
    } 
    // Filter to show sessions from the last 7 days
    else if (filter === 'week') {
        const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
        filteredSessions = allSessions.filter(session => {
            const sessionDate = new Date(session.session_date);
            // Keep sessions that are newer than a week ago
            return sessionDate >= weekAgo;
        });
    } 
    // Filter to show sessions from the last 30 days
    else if (filter === 'month') {
        const monthAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
        filteredSessions = allSessions.filter(session => {
            const sessionDate = new Date(session.session_date);
            // Keep sessions that are newer than a month ago
            return sessionDate >= monthAgo;
        });
    }
    // If filter is 'all', show all sessions (no filtering needed)
    
    // Display the filtered sessions on the page
    displaySessions(filteredSessions);
}

// Display the filtered sessions as cards on the page
function displaySessions(sessions) {
    const container = document.getElementById('sessions-container');
    
    // If no sessions found, show a friendly message
    if (sessions.length === 0) {
        container.innerHTML = '<div class="no-sessions">No sessions found for this time period.</div>';
        return;
    }
    
    // Convert each session into an HTML card and display them all
    container.innerHTML = sessions.map(session => createSessionCard(session)).join('');
    
    // After creating the cards, add click handlers to the delete buttons
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => deleteSession(btn.dataset.sessionId));
    });
    
    // Add click handlers to the "View Notes" buttons
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => viewNotes(btn.dataset.sessionId));
    });
}

// Create the HTML for a single session card
function createSessionCard(session) {
    // Format the session date nicely (e.g., "January 15, 2025")
    const date = new Date(session.session_date);
    const formattedDate = date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // Show a badge indicating if this session has Feynman notes or not
    const notesBadge = session.has_notes
        ? '<span class="notes-badge has-notes">✓ Has Notes</span>'
        : '<span class="notes-badge no-notes">No Notes</span>';
    
    // Only show "View Notes" button if the session actually has notes
    const viewNotesBtn = session.has_notes
        ? `<button class="btn btn-view" data-session-id="${session.session_id}">View Notes</button>`
        : '';
    
    // Return the HTML for this session card
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

// Delete a session after user confirms
async function deleteSession(sessionId) {
    // Ask user to confirm before deleting (this is important - can't undo!)
    const confirmResult = await SwalAlert.confirm(
        'Delete Session', 
        'Are you sure you want to delete this session? This action cannot be undone.'
    );
    
    // If user clicked "Cancel", don't do anything
    if (!confirmResult.isConfirmed) {
        return;
    }
    
    // Send DELETE request to the server to remove the session
    const result = await apiRequest(`../Sessions/delete_session.php?session_id=${parseInt(sessionId)}`, {
        method: 'DELETE'
    });
    
    // If deletion was successful, update the UI
    if (result.success && result.data.success) {
        // Remove the session from our local array so it disappears from the page
        allSessions = allSessions.filter(s => s.session_id !== parseInt(sessionId));
        // Re-display sessions with current filter applied
        filterAndDisplaySessions(currentFilter);
        
        // Show a success message to let the user know it worked
        SwalAlert.success('Session Deleted', 'Session deleted successfully!');
    } else {
        // If something went wrong, show an error message
        SwalAlert.error('Failed to Delete Session', result.error || result.data?.error || 'Unknown error');
    }
}

// Navigate to the Feynman notes page for a specific session
function viewNotes(sessionId) {
    // Redirect to the notes page, passing the session ID in the URL
    window.location.href = `../FeynmanPages/feynmannotes-html.php?session_id=${sessionId}`;
}
