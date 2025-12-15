// ============================================
// FEYNMAN NOTES - SESSION TRACKING
// ============================================

// Load current session data
const currentSession = JSON.parse(localStorage.getItem('currentSession') || '{}');

// Display session info
if (currentSession.date) {
    const sessionDate = new Date(currentSession.date);
    document.getElementById('session-date').textContent = sessionDate.toLocaleString();
    document.getElementById('session-duration').textContent = Math.floor(currentSession.duration / 60);
} else {
    document.getElementById('session-info').style.display = 'none';
}

// Load existing notes for this session if they exist
const sessionId = currentSession.sessionId;
if (sessionId) {
    const allNotes = JSON.parse(localStorage.getItem('feynmanNotes') || '{}');
    const sessionNotes = allNotes[sessionId];
    
    if (sessionNotes) {
        document.getElementById('initial-explanation').value = sessionNotes.initialExplanation || '';
        document.getElementById('simplified-explanation').value = sessionNotes.simplifiedExplanation || '';
        document.getElementById('key-concepts').value = sessionNotes.keyConcepts || '';
    }
}

// Save notes function
function saveNotes() {
    if (!sessionId) {
        alert('No session found. Please complete a Pomodoro session first.');
        return;
    }

    const notes = {
        sessionId: sessionId,
        date: currentSession.date,
        duration: currentSession.duration,
        tasks: currentSession.tasks || [],
        initialExplanation: document.getElementById('initial-explanation').value.trim(),
        simplifiedExplanation: document.getElementById('simplified-explanation').value.trim(),
        keyConcepts: document.getElementById('key-concepts').value.trim(),
        savedAt: new Date().toISOString()
    };

    // Load all notes
    const allNotes = JSON.parse(localStorage.getItem('feynmanNotes') || '{}');
    
    // Update notes for this session
    allNotes[sessionId] = notes;
    
    // Save back to localStorage
    localStorage.setItem('feynmanNotes', JSON.stringify(allNotes));
    
    // Show success message
    const btn = document.getElementById('save-btn');
    const originalText = btn.textContent;
    btn.textContent = 'Saved!';
    btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    
    setTimeout(() => {
        btn.textContent = originalText;
    }, 2000);
    
    // Optionally redirect back to timer
    setTimeout(() => {
        if (confirm('Notes saved! Return to timer?')) {
            window.location.href = '../pomodoro/pomodoro.html';
        }
    }, 2000);
}

// Event listener for save button
document.getElementById('save-btn').addEventListener('click', saveNotes);

// Auto-save draft every 30 seconds
setInterval(() => {
    if (sessionId) {
        const draft = {
            initialExplanation: document.getElementById('initial-explanation').value,
            simplifiedExplanation: document.getElementById('simplified-explanation').value,
            keyConcepts: document.getElementById('key-concepts').value
        };
        localStorage.setItem(`draft_${sessionId}`, JSON.stringify(draft));
    }
}, 30000);

// Load draft on page load
if (sessionId) {
    const draft = localStorage.getItem(`draft_${sessionId}`);
    if (draft) {
        const draftData = JSON.parse(draft);
        if (!document.getElementById('initial-explanation').value) {
            document.getElementById('initial-explanation').value = draftData.initialExplanation || '';
        }
        if (!document.getElementById('simplified-explanation').value) {
            document.getElementById('simplified-explanation').value = draftData.simplifiedExplanation || '';
        }
        if (!document.getElementById('key-concepts').value) {
            document.getElementById('key-concepts').value = draftData.keyConcepts || '';
        }
    }
}

