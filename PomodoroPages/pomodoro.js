// ENHANCED POMODORO TIMER - STATE MANAGEMENT

// Timer modes configuration - TESTING ONLY
const timerModes = {
    pomodoro: 10,    // Changed from 1500 to 10 seconds for testing
    'short-break': 5, 
    'long-break': 10
};

// Why: Using a state object keeps all timer data organized and makes debugging easier

const timerState = {
    timeLeft: 1500, // 25 minutes in seconds (remaining time)
    isRunning: false,
    intervalId: null,
    currentMode: 'pomodoro', // Current timer mode
    defaultTime: 1500, // Store default so we can reset easily
    targetEndTime: null, // Timestamp when timer should end (milliseconds)
    pausedTimeLeft: null, // Remaining time when paused (for resume)
    consecutiveSkips: 0 // Track how many times user skipped reflection
};

// DOM element references
// Why: Caching DOM elements improves performance and makes code cleaner

const elements = {
    startButton: document.getElementById('start-btn'),
    timerDisplay: document.querySelector('.timer-text'), // CHANGED - use querySelector for class
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
 * Calculates progress percentage (0-100) based on remaining time
 * Why: Separate function makes it reusable and testable
 */
function calculateProgress() {
    const totalTime = timerState.defaultTime; // e.g., 1500 seconds
    const remainingTime = timerState.timeLeft; // e.g., 750 seconds
    const elapsedTime = totalTime - remainingTime;
    const percentage = (elapsedTime / totalTime) * 100;
    return Math.min(100, Math.max(0, percentage)); // Clamp between 0-100
}

/**
 * Updates the timer display
 * Why: Single function for UI updates ensures consistency
 * Now calculates time based on actual system time for accuracy
 */

function updateTimerDisplay() {
    let remainingSeconds = timerState.timeLeft;
    
    // If timer is running, calculate remaining time from target end time
    if (timerState.isRunning && timerState.targetEndTime) {
        const now = Date.now();
        const remaining = Math.max(0, Math.floor((timerState.targetEndTime - now) / 1000));
        remainingSeconds = remaining;
        timerState.timeLeft = remainingSeconds; // Keep state in sync
        
        // Check if timer reached zero
        if (remainingSeconds <= 0) {
            completeTimer();
            return;
        }
    }
    
    // Update timer text display
    elements.timerDisplay.textContent = formatTime(remainingSeconds);
    
    // Update circular progress ring
    updateProgressRing();
    
    // Visual feedback: Add pulsing animation when timer is running
    if (timerState.isRunning) {
        elements.timerDisplay.style.animation = 'pulse 1s ease-in-out infinite';
    } else {
        elements.timerDisplay.style.animation = 'none';
    }
}

/**
 * Updates the circular progress ring based on timer progress
 * Uses CSS conic-gradient for smooth, continuous progress arc
 */

function updateProgressRing() {
    const progressRing = document.getElementById('progress-ring');
    if (!progressRing) return; // Safety check
    
    // Get CSS variable values for colors
    const rootStyles = getComputedStyle(document.documentElement);
    const purpleColor = rootStyles.getPropertyValue('--primary-purple').trim() || '#8672FF';
    const lightColor = rootStyles.getPropertyValue('--border-light').trim() || '#E5E5E5';
    
    const progress = calculateProgress(); // 0-100 percentage
    const angle = (progress / 100) * 360; // Convert to degrees (0-360)
    
    // Update conic-gradient for smooth continuous progress
    // Arc depletes clockwise (starts full, reduces as time decreases)
    progressRing.style.background = `conic-gradient(
        from 0deg,
        ${purpleColor} 0deg,
        ${purpleColor} ${angle}deg,
        ${lightColor} ${angle}deg,
        ${lightColor} 360deg
    )`;
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
    timerState.targetEndTime = null;
    timerState.pausedTimeLeft = null;
    
    // Update active mode button
    elements.modeButtons.forEach(btn => {
        if (btn.dataset.mode === mode) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    // Reset progress ring when switching modes
    updateTimerDisplay(); // This will reset the ring to 0% via updateProgressRing()
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
    
    // Session ID will be generated when session is saved to database

    // Calculate target end time based on current time + remaining time
    const now = Date.now();
    const durationMs = timerState.timeLeft * 1000; // Convert seconds to milliseconds
    
    // If resuming from pause, use the paused time left
    if (timerState.pausedTimeLeft !== null) {
        timerState.targetEndTime = now + (timerState.pausedTimeLeft * 1000);
        timerState.timeLeft = timerState.pausedTimeLeft;
        timerState.pausedTimeLeft = null;
    } else {
        // Starting fresh - use current timeLeft
        timerState.targetEndTime = now + durationMs;
    }
    
    timerState.isRunning = true;
    updateButtonStates();
    
    // Update display every second using actual system time
    // This ensures accuracy even if the interval is slightly off

    timerState.intervalId = setInterval(() => {
        updateTimerDisplay();
    }, 100); // Update more frequently for smoother display (every 100ms)
    
    // Update display immediately
    updateTimerDisplay();
    
    // Play notification sound on start 
    playNotification('start');
}

/**
 * Pauses the timer
 * Why: Clear separation of pause vs reset logic
 */

function pauseTimer() {
    if (!timerState.isRunning) return;
    
    // Calculate remaining time when pausing
    if (timerState.targetEndTime) {
        const now = Date.now();
        const remaining = Math.max(0, Math.floor((timerState.targetEndTime - now) / 1000));
        timerState.pausedTimeLeft = remaining;
        timerState.timeLeft = remaining;
    }
    
    timerState.isRunning = false;
    timerState.targetEndTime = null;
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
    timerState.targetEndTime = null;
    timerState.pausedTimeLeft = null;
    updateTimerDisplay(); // This will reset the ring to 0% via updateProgressRing()
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
    
    // For Pomodoro sessions, save to database and show modal
    saveSessionToDatabase();
    
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
 * Load skip counter from localStorage
 */
function loadSkipCounter() {
    const saved = localStorage.getItem('consecutiveSkips');
    if (saved !== null) {
        timerState.consecutiveSkips = parseInt(saved, 10);
    }
}

/**
 * Save skip counter to localStorage
 */
function saveSkipCounter() {
    localStorage.setItem('consecutiveSkips', timerState.consecutiveSkips.toString());
}

/**
 * Reset skip counter 
 */
function resetSkipCounter() {
    timerState.consecutiveSkips = 0;
    saveSkipCounter();
}

/**
 * Shows a modal asking if user wants to continue or proceed to Feynman notes
 * Modal variant changes based on consecutiveSkips count:
 * - 0 skips: Normal modal with equal choice
 * - 1 skip: Warning modal nudging towards reflection
 * - 2+ skips: Mandatory modal forcing reflection on 3rd second skip
 */

function showSessionCompleteModal() {
    const skipCount = timerState.consecutiveSkips;
    
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
    
    // Determine modal content based on skip count
    let title, message, buttons;
    
    if (skipCount === 0) {
        // NORMAL MODAL - First session, no pressure
        title = 'Session Complete!';
        message = 'What would you like to do next?';
        buttons = `
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

    } else if (skipCount === 1) {
        // WARNING MODAL - Second session, nudge towards reflection
        title = 'Great Session!';
        message = "You've completed 2 sessions without reflecting. Taking notes helps solidify your learning!";
        buttons = `
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <button id="feynman-notes-btn" style="
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
                    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.3);
                ">Go to Feynman Notes </button>
                <button id="continue-session-btn" style="
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
                ">Skip (Not Recommended)</button>
            </div>
        `;
    } else {
        // MANDATORY MODAL - Third session, must reflect
        title = 'Time to Reflect!';
        message = "You've completed 3 sessions. Let's consolidate your learning with Feynman notes before continuing.";
        buttons = `
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <button id="feynman-notes-btn" style="
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
                    box-shadow: 0 4px 12px rgba(134, 114, 255, 0.3);
                ">Go to Feynman Notes</button>
            </div>
        `;
    }
    
    modal.innerHTML = `
        <h2 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem; color: #1A1A1A;">${title}</h2>
        <p style="color: #4A4A4A; margin-bottom: 2rem; font-size: 1.1rem;">${message}</p>
        ${buttons}
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Button event listeners
    const continueBtn = modal.querySelector('#continue-session-btn');
    const feynmanBtn = modal.querySelector('#feynman-notes-btn');
    
    // Continue button 
    if (continueBtn) {
        continueBtn.addEventListener('mouseenter', () => {
            continueBtn.style.transform = 'translateY(-2px)';
            continueBtn.style.boxShadow = '0 4px 12px rgba(134, 114, 255, 0.4)';
        });
        continueBtn.addEventListener('mouseleave', () => {
            continueBtn.style.transform = 'translateY(0)';
            continueBtn.style.boxShadow = skipCount === 1 ? 'none' : 'none';
        });
        
        continueBtn.addEventListener('click', () => {
            // Increment skip counter
            timerState.consecutiveSkips++;
            saveSkipCounter();
            
            overlay.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => overlay.remove(), 300);
            switchMode('short-break');
        });
    }
    
    // Feynman notes button
    feynmanBtn.addEventListener('mouseenter', () => {
        feynmanBtn.style.transform = 'translateY(-2px)';
        feynmanBtn.style.boxShadow = '0 4px 12px rgba(134, 114, 255, 0.4)';
    });
    feynmanBtn.addEventListener('mouseleave', () => {
        feynmanBtn.style.transform = 'translateY(0)';
        feynmanBtn.style.boxShadow = skipCount === 1 ? '0 4px 12px rgba(134, 114, 255, 0.3)' : (skipCount >= 2 ? '0 4px 12px rgba(134, 114, 255, 0.3)' : 'none');
    });
    
    feynmanBtn.addEventListener('click', () => {
        // Reset skip counter when user goes to Feynman notes
        resetSkipCounter();
        window.location.href = '../FeynmanPages/feynmannotes-html.php';
    });
    
    // Close on overlay click (only if not mandatory)
    if (skipCount < 2) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                // Increment skip counter
                timerState.consecutiveSkips++;
                saveSkipCounter();
                
                overlay.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => overlay.remove(), 300);
                switchMode('short-break');
            }
        });
    }
    
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

async function addTask(text) {
    if (!text.trim()) return;
    
    const taskText = text.trim();
    
    try {
        const response = await fetch(`../Tasks/create_task.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                task_text: taskText
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Add task to local array with database ID
            const task = {
                id: data.task_id.toString(),
                text: taskText,
                completed: false
            };
            
            tasks.push(task);
            renderTasks();
            elements.taskInput.value = '';
        } else {
            console.error('Failed to create task:', data.error || 'Unknown error');
            alert('Failed to add task. Please try again.');
        }
    } catch (error) {
        console.error('Error creating task:', error);
        alert('Error adding task. Please check your connection and try again.');
    }
}

async function toggleTask(id) {
    const task = tasks.find(t => t.id === id);
    if (!task) return;
    
    const newCompletedStatus = !task.completed;
    
    try {
        const response = await fetch(`../Tasks/update_task.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                task_id: parseInt(id),
                completed: newCompletedStatus
            })
        });

        const data = await response.json();
        
        if (data.success) {
            task.completed = newCompletedStatus;
            renderTasks();
        } else {
             // Revert UI change on error
            renderTasks();
        }
    } catch (error) {
        
        // Revert UI change on error
        renderTasks();
    }
}

async function deleteTask(id) {

    // Automatically remove from UI
    const taskIndex = tasks.findIndex(t => t.id === id);
    if (taskIndex === -1) return;
    
    const taskToDelete = tasks[taskIndex];
    tasks = tasks.filter(t => t.id !== id);
    renderTasks();
    
    try {
        const response = await fetch(`../Tasks/delete_task.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                task_id: parseInt(id)
            })
        });

        const data = await response.json();
        
        if (!data.success) {
            // Restore task on error
            tasks.splice(taskIndex, 0, taskToDelete);
            renderTasks();
            console.error('Failed to delete task:', data.error || 'Unknown error');
            alert('Failed to delete task. Please try again.');
        }
    } catch (error) {
        // Restore task on error
        tasks.splice(taskIndex, 0, taskToDelete);
        renderTasks();
        console.error('Error deleting task:', error);
        alert('Error deleting task. Please check your connection and try again.');
    }
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

// Load tasks from database on page load
async function loadTasks() {
    try {
        const response = await fetch('../Tasks/get_tasks.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();
        
        if (data.success && data.tasks) {
            tasks = data.tasks.map(task => ({
                id: task.task_id.toString(),
                text: task.task_text,
                completed: task.completed
            }));
            renderTasks();
        } else {
            // If no tasks, just render empty list
            renderTasks();
        }
    } catch (error) {
        console.error('Error loading tasks:', error);
        // Don't let task loading errors break the page - just show empty list
        renderTasks();
    }
}

/**
 * Saves completed Pomodoro session to database
 */

async function saveSessionToDatabase() {
    // Get current local time (not UTC) to avoid timezone offset issues
    const now = new Date();
    const localDateString = now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0') + ' ' +
        String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');
    
    const sessionData = {
        session_date: localDateString, // Send local time as formatted string
        duration: timerModes.pomodoro,
        mode: 'pomodoro',
        tasks: tasks.filter(t => !t.completed).map(t => t.text),
        completed_tasks: tasks.filter(t => t.completed).map(t => t.text)
    };
    
    console.log('Attempting to save session:', sessionData); // Debug log remove later
    
    try {
        const url = `../Sessions/create_session.php`;

        console.log('Fetching URL:', url); // Debug log, remove later
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(sessionData)
        });

        console.log('Response status:', response.status, response.statusText); // Debug log
        
        if (!response.ok) {
            // Try to get error message from response
            let errorMessage = `HTTP error! status: ${response.status}`;
            try {
                const errorData = await response.json();
                errorMessage = errorData.error || errorData.message || errorMessage;
            } catch (e) {
                // If response isn't JSON, get as text
                const textResponse = await response.text();
                errorMessage = textResponse.substring(0, 200) || errorMessage;
            }
            throw new Error(errorMessage);
        }

        const data = await response.json();
        
        console.log('Session save response:', data); // Debug log
        
        if (data.success) {

            // Store session ID for use in Feynman notes
            currentSessionId = data.session_id.toString();
            
            // Also store in localStorage for Feynman notes compatibility (temporary)
            const sessionForNotes = {
                sessionId: currentSessionId,
                date: sessionData.session_date,
                duration: sessionData.duration,
                tasks: sessionData.tasks,
                completedTasks: sessionData.completed_tasks
            };
            localStorage.setItem('currentSession', JSON.stringify(sessionForNotes));
            
            console.log('Session saved successfully:', currentSessionId); // Debug log
            
            // Show modal asking if they want to continue or proceed to Feynman notes
            showSessionCompleteModal();

        } else {
            console.error('Failed to save session:', data);
            const errorMsg = data.error || 'Unknown error';
            const dbError = data.db_error ? ` Database error: ${data.db_error}` : '';
            alert(`Failed to save session: ${errorMsg}${dbError}`);

            // Still show modal even if save failed
            showSessionCompleteModal();
        }
    } catch (error) {
        console.error('Error saving session:', error);
        alert(`Error saving session: ${error.message}. Please check your connection and the browser console.`);
        // Still show modal even if save failed
        showSessionCompleteModal();
    }
}

// Make functions globally available for inline handlers
window.toggleTask = toggleTask;
window.deleteTask = deleteTask;

// INITIALIZATION

// Add event listener to start button
elements.startButton.addEventListener('click', toggleTimer);

// Add event listener to task input (Enter key)
elements.taskInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        addTask(elements.taskInput.value);
    }
});

// Add event listeners to mode buttons
elements.modeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        switchMode(btn.dataset.mode);
    });
    
    // Set initial active state
    if (btn.dataset.mode === timerState.currentMode) {
        btn.classList.add('active');
    }
});

// Initialize the timer display and button states
updateTimerDisplay();
updateButtonStates();
loadTasks();
loadSkipCounter();

// Reset session ID when timer resets
const originalResetTimer = resetTimer;

resetTimer = function() {
    currentSessionId = null;
    originalResetTimer();
};




