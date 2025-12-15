// FEYNMAN NOTES PAGE - Reflection notes for completed Pomodoro sessions

// Store the current session data and ID
let currentSession = null;
let sessionId = null;

// Get the session ID from either the URL (if coming from History page) 
// or from localStorage (if coming directly from timer)
const urlParams = new URLSearchParams(window.location.search);
const sessionIdFromUrl = urlParams.get('session_id');
const sessionFromStorage = JSON.parse(localStorage.getItem('currentSession') || '{}');

// Use URL parameter if available, otherwise use the one from localStorage
sessionId = sessionIdFromUrl || sessionFromStorage.sessionId;

// Set up the page when it first loads - fetch session info and any existing notes
async function initializePage() {
    // If no session ID, show a warning and hide the session info section
    if (!sessionId) {
        document.getElementById('session-info').style.display = 'none';
        SwalAlert.warning('No Session Found', 'Please complete a Pomodoro session first.');
        return;
    }

    // Fetch the session details from the database
    const sessionResult = await apiRequest(`../Sessions/get_session.php?session_id=${sessionId}`, {
        method: 'GET'
    });
    
    // If we got the session data, display it on the page
    if (sessionResult.success && sessionResult.data.success && sessionResult.data.session) {
        currentSession = sessionResult.data.session;
        
        // Show when the session happened and how long it was
        const sessionDate = new Date(currentSession.session_date);
        document.getElementById('session-date').textContent = sessionDate.toLocaleString();
        document.getElementById('session-duration').textContent = Math.floor(currentSession.duration / 60);
    } else {
        // If we couldn't load the session, hide the info section
        document.getElementById('session-info').style.display = 'none';
        console.error('Failed to load session:', sessionResult.error || 'Unknown error');
    }

    // Load any saved notes and auto-saved drafts
    await loadNotes();
    await loadDraft();
}

// Load any previously saved notes from the database and fill in the text areas
async function loadNotes() {
    if (!sessionId) return;

    // Fetch the notes for this session
    const result = await apiRequest(`../Notes/get_notes.php?session_id=${sessionId}`, {
        method: 'GET'
    });
    
    // If notes exist, populate the three text areas with the saved content
    if (result.success && result.data.success && result.data.notes) {
        document.getElementById('initial-explanation').value = result.data.notes.initial_explanation || '';
        document.getElementById('simplified-explanation').value = result.data.notes.simplified_explanation || '';
        document.getElementById('key-concepts').value = result.data.notes.key_concepts || '';
    }
}

// Load any auto-saved drafts (unsaved work) from the database
async function loadDraft() {
    if (!sessionId) return;

    // Fetch the draft for this session
    const result = await apiRequest(`../Drafts/get_draft.php?session_id=${sessionId}`, {
        method: 'GET'
    });
    
    // Only load draft if the text areas are empty - we don't want to overwrite saved notes
    // This way, if you have saved notes, they take priority over drafts
    if (result.success && result.data.success && result.data.draft) {
        const initialEl = document.getElementById('initial-explanation');
        const simplifiedEl = document.getElementById('simplified-explanation');
        const conceptsEl = document.getElementById('key-concepts');

        // Fill in each field only if it's currently empty
        if (!initialEl.value && result.data.draft.initialExplanation) {
            initialEl.value = result.data.draft.initialExplanation;
        }
        if (!simplifiedEl.value && result.data.draft.simplifiedExplanation) {
            simplifiedEl.value = result.data.draft.simplifiedExplanation;
        }
        if (!conceptsEl.value && result.data.draft.keyConcepts) {
            conceptsEl.value = result.data.draft.keyConcepts;
        }
    }
}

// Start loading the page as soon as the script runs
initializePage();

// Save the notes to the database when user clicks the Save button
async function saveNotes() {
    // Make sure we have a session ID before trying to save
    if (!sessionId) {
        SwalAlert.warning('No Session Found', 'Please complete a Pomodoro session first.');
        return;
    }

    // Disable the button and show "Saving..." so user knows something is happening
    const btn = document.getElementById('save-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Saving...';

    // Collect all the text from the three text areas
    const notesData = {
        session_id: parseInt(sessionId),
        initial_explanation: document.getElementById('initial-explanation').value.trim(),
        simplified_explanation: document.getElementById('simplified-explanation').value.trim(),
        key_concepts: document.getElementById('key-concepts').value.trim()
    };

    // Send the notes to the server to save them
    const result = await apiRequest('../Notes/save_notes.php', {
        method: 'POST',
        body: notesData
    });
    
    // If save was successful, show success message and offer to go back to timer
    if (result.success && result.data.success) {
        // Change button to green "Saved!" for visual feedback
        btn.textContent = 'Saved!';
        btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        
        // After 2 seconds, reset the button back to normal
        setTimeout(() => {
            btn.textContent = originalText;
            btn.style.background = '';
            btn.disabled = false;
        }, 2000);
        
        // Ask user if they want to go back to the timer
        setTimeout(async () => {
            const confirmResult = await SwalAlert.confirm('Notes Saved!', 'Return to timer?');
            if (confirmResult.isConfirmed) {
                window.location.href = '../PomodoroPages/pomodoro-html.php';
            }
        }, 2000);
    } else {
        // If save failed, show an error message and re-enable the button
        SwalAlert.error('Failed to Save Notes', result.error || result.data?.error || 'Unknown error');
        btn.textContent = originalText;
        btn.disabled = false;
    }
}

// When user clicks the Save button, save the notes
document.getElementById('save-btn').addEventListener('click', saveNotes);

// Auto-save feature - saves drafts every 30 seconds so user doesn't lose work
let draftInterval = null;

function startAutoSave() {
    // Clear any existing auto-save interval (in case function is called multiple times)
    if (draftInterval) clearInterval(draftInterval);
    
    // Set up a timer that runs every 30 seconds
    draftInterval = setInterval(async () => {
        // Don't save if there's no session ID
        if (!sessionId) return;

        // Collect all the current text from the three text areas
        const draft = {
            initialExplanation: document.getElementById('initial-explanation').value,
            simplifiedExplanation: document.getElementById('simplified-explanation').value,
            keyConcepts: document.getElementById('key-concepts').value
        };

        // Save the draft to the database (this happens silently in the background)
        await apiRequest(`../Drafts/save_draft.php`, {
            method: 'POST',
            body: {
                session_id: parseInt(sessionId),
                draft_data: draft
            }
        });
        // We don't show any message to the user - it just happens automatically
    }, 30000); // Run every 30 seconds (30000 milliseconds)
}

// Start the auto-save feature if we have a session ID
if (sessionId) {
    startAutoSave();
}


