
// ENHANCED POMODORO TIMER - STATE MANAGEMENT

// Timer modes configuration
const timerModes = {
    pomodoro: 1500,    // 25 minutes
    'short-break': 300, // 5 minutes
    'long-break': 900  // 15 minutes
};

// Why: Using a state object keeps all timer data organized and makes debugging easier
const timerState = {
    timeLeft: 1500, // 25 minutes in seconds (default Pomodoro length)
    isRunning: false,
    intervalId: null,
    currentMode: 'pomodoro', // Current timer mode
    defaultTime: 1500 // Store default so we can reset easily
};

// DOM element references
// Why: Caching DOM elements improves performance and makes code cleaner
const elements = {
    startButton: document.getElementById('start-btn'),
    timerDisplay: document.getElementById('timer'),
    taskInput: document.getElementById('task-input'),
    tasksList: document.getElementById('tasks-list'),
    modeButtons: document.querySelectorAll('.mode-btn')
};

// Tasks state
let tasks = [];
let currentSessionId = null;

/* UTILITY FUNCTIONS    **
 * Formats seconds into MM:SS format
 * Why: Separating formatting logic makes it reusable and testable
 */

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

/**
 * Updates the timer display
 * Why: Single function for UI updates ensures consistency
 */

function updateTimerDisplay() {
    elements.timerDisplay.textContent = formatTime(timerState.timeLeft);
    
    // Visual feedback: Add pulsing animation when timer is running
    if (timerState.isRunning) {
        elements.timerDisplay.style.animation = 'pulse 1s ease-in-out infinite';
    } else {
        elements.timerDisplay.style.animation = 'none';
    }
}

/**
 * Updates button states based on timer status
 * Why: Better UX - buttons reflect current state (e.g., disable start when running)
 */

function updateButtonStates() {
    if (timerState.isRunning) {
        elements.startButton.textContent = 'PAUSE';
        elements.startButton.style.cursor = 'pointer';
    } else {
        elements.startButton.textContent = 'START';
        elements.startButton.style.cursor = 'pointer';
    }
}

/**
 * Switches timer mode (Pomodoro, Short Break, Long Break)
 */
function switchMode(mode) {
    if (timerState.isRunning) {
        pauseTimer();
    }
    
    timerState.currentMode = mode;
    timerState.defaultTime = timerModes[mode];
    timerState.timeLeft = timerModes[mode];
    
    // Update active mode button
    elements.modeButtons.forEach(btn => {
        if (btn.dataset.mode === mode) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    updateTimerDisplay();
    updateButtonStates();
}

/* TIMER CONTROL FUNCTIONS     */

/**
 * Starts the timer
 * Why: Clear function name and prevents multiple intervals from running
 */

function startTimer() {
    // Prevent starting if already running
    if (timerState.isRunning) return;
    
    // Generate session ID when timer starts
    if (!currentSessionId) {
        currentSessionId = Date.now().toString();
    }
    
    timerState.isRunning = true;
    updateButtonStates();
    
    // Why: Using setInterval with proper cleanup prevents memory leaks
    timerState.intervalId = setInterval(() => {
        timerState.timeLeft--;
        updateTimerDisplay();
        
        // Check if timer reached zero
        if (timerState.timeLeft <= 0) {
            completeTimer();
        }
    }, 1000);
    
    // Update display immediately
    updateTimerDisplay();
    
    // Play notification sound (optional enhancement)
    playNotification('start');
}

/**
 * Pauses the timer
 * Why: Clear separation of pause vs reset logic
 */

function pauseTimer() {
    if (!timerState.isRunning) return;
    
    timerState.isRunning = false;
    clearInterval(timerState.intervalId);
    timerState.intervalId = null;
    
    updateButtonStates();
    updateTimerDisplay();
}

/**
 * Toggles timer (start/pause)
 */
function toggleTimer() {
    if (timerState.isRunning) {
        pauseTimer();
    } else {
        startTimer();
    }
}

/**
 * Resets the timer to default time for current mode
 * Why: Ensures timer stops before resetting (prevents bugs)
 */
function resetTimer() {
    // Stop timer if running
    if (timerState.isRunning) {
        pauseTimer();
    }
    
    timerState.timeLeft = timerState.defaultTime;
    updateTimerDisplay();
    updateButtonStates();
}

/**
 * Handles timer completion
 * Why: Centralized completion logic makes it easy to add features (sounds, notifications, etc.)
 */
function completeTimer() {
    pauseTimer();
    
    // Play completion sound
    playNotification('complete');
    
    // Check if it's a break mode
    if (timerState.currentMode !== 'pomodoro') {
        // For breaks, just show notification and reset
        showCompletionNotification('Break time is over. Get back to work!');
        resetTimer();
        return;
    }
    
    // For Pomodoro sessions, create session data and show modal
    const sessionData = {
        sessionId: currentSessionId || Date.now().toString(),
        date: new Date().toISOString(),
        duration: timerModes.pomodoro,
        tasks: tasks.filter(t => !t.completed).map(t => t.text),
        completedTasks: tasks.filter(t => t.completed).map(t => t.text)
    };
    
    // Store session data in localStorage
    const sessions = JSON.parse(localStorage.getItem('pomodoroSessions') || '[]');
    sessions.push(sessionData);
    localStorage.setItem('pomodoroSessions', JSON.stringify(sessions));
    localStorage.setItem('currentSession', JSON.stringify(sessionData));
    
    // Show modal asking if they want to continue or proceed to Feynman notes
    showSessionCompleteModal();
    
    // Reset to default time
    timerState.timeLeft = timerState.defaultTime;
    updateTimerDisplay();
}
    

/**
 * Shows a visual notification when timer completes
 * Why: Better UX than browser alert - doesn't block interaction
 */

function showCompletionNotification(message = "Time's up! Great work!") {
    // Create notification element
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 1em 2em;
        border-radius: 0.8em;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Shows a modal asking if user wants to continue or proceed to Feynman notes
 */
function showSessionCompleteModal() {
    
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease-out;
    `;
    
    // Create modal content
    const modal = document.createElement('div');
    modal.className = 'session-complete-modal';
    modal.style.cssText = `
        background: white;
        border-radius: 20px;
        padding: 2.5em 3em;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        text-align: center;
        animation: slideUp 0.3s ease-out;
        font-family: 'Poppins', sans-serif;
    `;
    
    modal.innerHTML = `
        <h2 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem; color: #1A1A1A;">Session Complete!</h2>
        <p style="color: #4A4A4A; margin-bottom: 2rem; font-size: 1.1rem;">What would you like to do next?</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <button id="continue-session-btn" style="
                padding: 1em 2em;
                border: none;
                border-radius: 12px;
                background: #8672FF;
                color: white;
                font-weight: 600;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
            ">Continue Session</button>
            <button id="feynman-notes-btn" style="
                padding: 1em 2em;
                border: 2px solid #8672FF;
                border-radius: 12px;
                background: white;
                color: #8672FF;
                font-weight: 600;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
            ">Go to Feynman Notes</button>
        </div>
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Button event listeners
    const continueBtn = modal.querySelector('#continue-session-btn');
    const feynmanBtn = modal.querySelector('#feynman-notes-btn');
    
    continueBtn.addEventListener('mouseenter', () => {
        continueBtn.style.transform = 'translateY(-2px)';
        continueBtn.style.boxShadow = '0 4px 12px rgba(134, 114, 255, 0.4)';
    });
    continueBtn.addEventListener('mouseleave', () => {
        continueBtn.style.transform = 'translateY(0)';
        continueBtn.style.boxShadow = 'none';
    });
    
    feynmanBtn.addEventListener('mouseenter', () => {
        feynmanBtn.style.transform = 'translateY(-2px)';
        feynmanBtn.style.boxShadow = '0 4px 12px rgba(134, 114, 255, 0.2)';
    });
    feynmanBtn.addEventListener('mouseleave', () => {
        feynmanBtn.style.transform = 'translateY(0)';
        feynmanBtn.style.boxShadow = 'none';
    });
    
    continueBtn.addEventListener('click', () => {
        overlay.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => overlay.remove(), 300);

        // Switch to short break
        switchMode('short-break');
    });
    
    feynmanBtn.addEventListener('click', () => {
        window.location.href = '../FeynmanPages/feynmannotes-html.php';
    });
    

    // Close on overlay click
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => overlay.remove(), 300);

            // Default to continuing session if clicked outside
            switchMode('short-break');
        }
    });
    
    // Add CSS animations 
    if (!document.getElementById('modal-animations')) {
        const style = document.createElement('style');
        style.id = 'modal-animations';
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(100px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes slideOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(100px);
                }
            }
        `;
        document.head.appendChild(style);
    }
}

/* Plays a notification sound
 * Why: Audio feedback helps users stay aware even when not looking at screen
 */

function playNotification(type = 'start') {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        // Different frequencies for different events
        if (type === 'complete') {
            oscillator.frequency.value = 600;
            oscillator.type = 'sine';
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.8);
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.8);
        } else {
            oscillator.frequency.value = 400;
            oscillator.type = 'sine';
            gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.3);
        }
    } catch (e) {
        // Silently fail if audio not supported
        console.log('Audio not supported');
    }
}


// TASKS MANAGEMENT

function addTask(text) {
    if (!text.trim()) return;
    
    const task = {
        id: Date.now().toString(),
        text: text.trim(),
        completed: false
    };
    
    tasks.push(task);
    renderTasks();
    saveTasks();
    elements.taskInput.value = '';
}

function toggleTask(id) {
    const task = tasks.find(t => t.id === id);
    if (task) {
        task.completed = !task.completed;
        renderTasks();
        saveTasks();
    }
}

function deleteTask(id) {
    tasks = tasks.filter(t => t.id !== id);
    renderTasks();
    saveTasks();
}

function renderTasks() {
    elements.tasksList.innerHTML = '';
    
    if (tasks.length === 0) {
        elements.tasksList.innerHTML = '<p style="text-align: center; opacity: 0.7; padding: 1rem;">No tasks yet. Add one above!</p>';
        return;
    }
    
    tasks.forEach(task => {
        const taskItem = document.createElement('div');
        taskItem.className = `task-item ${task.completed ? 'completed' : ''}`;
        taskItem.innerHTML = `
            <input type="checkbox" class="task-checkbox" ${task.completed ? 'checked' : ''} onchange="toggleTask('${task.id}')">
            <span class="task-text">${task.text}</span>
            <button class="task-delete" onclick="deleteTask('${task.id}')">Delete</button>
        `;
        elements.tasksList.appendChild(taskItem);
    });
}

function saveTasks() {
    localStorage.setItem('pomodoroTasks', JSON.stringify(tasks));
}

function loadTasks() {
    const saved = localStorage.getItem('pomodoroTasks');
    if (saved) {
        tasks = JSON.parse(saved);
        renderTasks();
    }
}

// Task input event listener moved to DOMContentLoaded

// EVENT LISTENERS
// Why: Using addEventListener instead of inline handlers is best practice
elements.startButton.addEventListener('click', toggleTimer);

// Mode selector buttons
elements.modeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        switchMode(btn.dataset.mode);
    });
});

// Keyboard shortcuts for better accessibility
// Why: Keyboard support makes the app more accessible and faster to use
document.addEventListener('keydown', (e) => {
    // Prevent shortcuts when typing in inputs (if we add any later)
    if (e.target.tagName === 'INPUT') return;
    
    switch(e.key.toLowerCase()) {
        case ' ':
        case 's':
            e.preventDefault();
            toggleTimer();
            break;
        case 'r':
            e.preventDefault();
            resetTimer();
            break;
    }
});

// Task input event listener
if (elements.taskInput) {
    elements.taskInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const taskText = elements.taskInput.value.trim();
            if (taskText) {
                addTask(taskText);
            }
        }
    });
} else {
    console.error('Task input element not found!');
}

// Make functions globally available for inline handlers
window.toggleTask = toggleTask;
window.deleteTask = deleteTask;

// INITIALIZATION

// Initialize the timer display and button states
// Why: Ensures UI is correct on page load
updateTimerDisplay();
updateButtonStates();
loadTasks();

// Reset session ID when timer resets
const originalResetTimer = resetTimer;

resetTimer = function() {
    currentSessionId = null;
    originalResetTimer();
};

// Initialize mode selector
elements.modeButtons.forEach(btn => {
    if (btn.dataset.mode === timerState.currentMode) {
        btn.classList.add('active');
    }
});


