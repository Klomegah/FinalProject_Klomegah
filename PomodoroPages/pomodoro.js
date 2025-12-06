
// ENHANCED POMODORO TIMER - STATE MANAGEMENT

// Why: Using a state object keeps all timer data organized and makes debugging easier
const timerState = {
    timeLeft: 1500, // 25 minutes in seconds (default Pomodoro length)
    isRunning: false,
    intervalId: null,
    defaultTime: 1500 // Store default so we can reset easily
};

// DOM element references
// Why: Caching DOM elements improves performance and makes code cleaner
const elements = {
    startButton: document.getElementById('start-btn'),
    pauseButton: document.getElementById('stop-btn'),
    resetButton: document.getElementById('reset-btn'),
    timerDisplay: document.getElementById('timer'),
    taskInput: document.getElementById('task-input'),
    tasksList: document.getElementById('tasks-list')
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
        elements.startButton.disabled = true;
        elements.startButton.style.opacity = '0.6';
        elements.startButton.style.cursor = 'not-allowed';
        elements.pauseButton.disabled = false;
        elements.pauseButton.style.opacity = '1';
    } else {
        elements.startButton.disabled = false;
        elements.startButton.style.opacity = '1';
        elements.startButton.style.cursor = 'pointer';
        elements.pauseButton.disabled = false;
        elements.pauseButton.style.opacity = '1';
    }
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
 * Resets the timer to default time
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
    
    // Create session data for Feynman notes
    const sessionData = {
        sessionId: currentSessionId || Date.now().toString(),
        date: new Date().toISOString(),
        duration: timerState.defaultTime,
        tasks: tasks.filter(t => !t.completed).map(t => t.text),
        completedTasks: tasks.filter(t => t.completed).map(t => t.text)
    };
    
    // Store session data in localStorage
    const sessions = JSON.parse(localStorage.getItem('pomodoroSessions') || '[]');
    sessions.push(sessionData);
    localStorage.setItem('pomodoroSessions', JSON.stringify(sessions));
    localStorage.setItem('currentSession', JSON.stringify(sessionData));
    
    // Play completion sound
    playNotification('complete');
    
    // Show notification and redirect to Feynman notes
    showCompletionNotification();
    
    // Redirect to Feynman notes page after 2 seconds
    setTimeout(() => {
        window.location.href = '../feynmannotes/feynman-notes.html';
    }, 2000);
    
    // Reset to default time
    timerState.timeLeft = timerState.defaultTime;
    updateTimerDisplay();
}
    
/* UX ENHANCEMENTS



/**
 * Shows a visual notification when timer completes
 * Why: Better UX than browser alert - doesn't block interaction
 */
function showCompletionNotification() {
    // Create notification element
    const notification = document.createElement('div');
    notification.textContent = "⏰ Time's up! Great work!";
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
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
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

// EVENT LISTENERS


// Why: Using addEventListener instead of inline handlers is best practice
elements.startButton.addEventListener('click', startTimer);
elements.pauseButton.addEventListener('click', pauseTimer);
elements.resetButton.addEventListener('click', resetTimer);

// Keyboard shortcuts for better accessibility
// Why: Keyboard support makes the app more accessible and faster to use
document.addEventListener('keydown', (e) => {
    // Prevent shortcuts when typing in inputs (if we add any later)
    if (e.target.tagName === 'INPUT') return;
    
    switch(e.key.toLowerCase()) {
        case ' ':
        case 's':
            e.preventDefault();
            if (timerState.isRunning) {
                pauseTimer();
            } else {
                startTimer();
            }
            break;
        case 'r':
            e.preventDefault();
            resetTimer();
            break;
    }
});

// ============================================
// TASK MANAGEMENT
// ============================================

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

// Task input event listener
elements.taskInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        addTask(elements.taskInput.value);
    }
});

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



document.head.appendChild(style);


