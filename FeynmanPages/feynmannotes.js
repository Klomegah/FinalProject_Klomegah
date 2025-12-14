// FEYNMAN NOTES - SESSION TRACKING

let currentSession = null;
let sessionId = null;

// Get session ID from localStorage (set by timer) or URL parameter
const urlParams = new URLSearchParams(window.location.search);
const sessionIdFromUrl = urlParams.get('session_id');
const sessionFromStorage = JSON.parse(localStorage.getItem('currentSession') || '{}');

sessionId = sessionIdFromUrl || sessionFromStorage.sessionId;

// Load session data and notes
async function initializePage() {
    if (!sessionId) {
        document.getElementById('session-info').style.display = 'none';
        alert('No session found. Please complete a Pomodoro session first.');
        return;
    }

    try {
        // Load session info from database
        const sessionResponse = await fetch(`../Sessions/get_session.php?session_id=${sessionId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const sessionData = await sessionResponse.json();
        
        if (sessionData.success && sessionData.session) {
            currentSession = sessionData.session;
            
            // Display session info
            const sessionDate = new Date(currentSession.session_date);
            document.getElementById('session-date').textContent = sessionDate.toLocaleString();
            document.getElementById('session-duration').textContent = Math.floor(currentSession.duration / 60);
        } else {
            document.getElementById('session-info').style.display = 'none';
            console.error('Failed to load session:', sessionData.error || 'Unknown error');
        }
    } catch (error) {
        console.error('Error loading session:', error);
        document.getElementById('session-info').style.display = 'none';
    }

    // Load notes and drafts
    await loadNotes();
    await loadDraft();
}

// Load existing notes from database
async function loadNotes() {
    if (!sessionId) return;

    try {
        const response = await fetch(`../Notes/get_notes.php?session_id=${sessionId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();
        
        if (data.success && data.notes) {
            document.getElementById('initial-explanation').value = data.notes.initial_explanation || '';
            document.getElementById('simplified-explanation').value = data.notes.simplified_explanation || '';
            document.getElementById('key-concepts').value = data.notes.key_concepts || '';
        }
    } catch (error) {
        console.error('Error loading notes:', error);
    }
}

// Load draft from database
async function loadDraft() {
    if (!sessionId) return;

    try {
        const response = await fetch(`../Drafts/get_draft.php?session_id=${sessionId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();
        
        // Only load draft if notes fields are empty (don't overwrite saved notes)
        if (data.success && data.draft) {
            const initialEl = document.getElementById('initial-explanation');
            const simplifiedEl = document.getElementById('simplified-explanation');
            const conceptsEl = document.getElementById('key-concepts');

            if (!initialEl.value && data.draft.initialExplanation) {
                initialEl.value = data.draft.initialExplanation;
            }
            if (!simplifiedEl.value && data.draft.simplifiedExplanation) {
                simplifiedEl.value = data.draft.simplifiedExplanation;
            }
            if (!conceptsEl.value && data.draft.keyConcepts) {
                conceptsEl.value = data.draft.keyConcepts;
            }
        }
    } catch (error) {
        console.error('Error loading draft:', error);
    }
}

// Initialize page on load
initializePage();

// Save notes function
async function saveNotes() {
    if (!sessionId) {
        alert('No session found. Please complete a Pomodoro session first.');
        return;
    }

    const btn = document.getElementById('save-btn');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const notesData = {
        session_id: parseInt(sessionId),
        initial_explanation: document.getElementById('initial-explanation').value.trim(),
        simplified_explanation: document.getElementById('simplified-explanation').value.trim(),
        key_concepts: document.getElementById('key-concepts').value.trim()
    };

    console.log('Saving notes data:', notesData); // Debug log

    try {
        const response = await fetch(`../Notes/save_notes.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(notesData)
        });

        console.log('Response status:', response.status); // Debug log
        console.log('Response ok:', response.ok); // Debug log

        const data = await response.json();
        
        console.log('Response data:', data); // Debug log
        
        if (data.success) {
            // Show success message
            btn.textContent = 'Saved!';
            btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '';
                btn.disabled = false;
            }, 2000);
            
            // Redirect back to timer
            setTimeout(() => {
                if (confirm('Notes saved! Return to timer?')) {
                    window.location.href = '../PomodoroPages/pomodoro-html.php';
                }
            }, 2000);
        } else {
            console.error('Save failed:', data); // Debug log
            alert('Failed to save notes: ' + (data.error || 'Unknown error'));
            btn.textContent = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        alert('Error saving notes: ' + error.message + '. Check the console for details.');
        btn.textContent = originalText;
        btn.disabled = false;
    }
}

// Event listener for save button
document.getElementById('save-btn').addEventListener('click', saveNotes);

// Auto-save draft every 30 seconds
let draftInterval = null;

function startAutoSave() {
    if (draftInterval) clearInterval(draftInterval);
    
    draftInterval = setInterval(async () => {
        if (!sessionId) return;

        const draft = {
            initialExplanation: document.getElementById('initial-explanation').value,
            simplifiedExplanation: document.getElementById('simplified-explanation').value,
            keyConcepts: document.getElementById('key-concepts').value
        };

        try {
            await fetch(`../Drafts/save_draft.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    session_id: parseInt(sessionId),
                    draft_data: draft
                })
            });
            // Silently save drafts - no need to show feedback
        } catch (error) {
            console.error('Error auto-saving draft:', error);
            // Don't alert user for draft save failures
        }
    }, 30000); // Auto-save every 30 seconds
}

// Start auto-save once page is initialized
if (sessionId) {
    startAutoSave();
}

