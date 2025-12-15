// ============================================
// ENHANCED POMODORO TIMER - STATE MANAGEMENT
// ============================================

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
    timerDisplay: document.getElementById('timer')
};

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
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

// ============================================
// TIMER CONTROL FUNCTIONS
// ============================================

/**
 * Starts the timer
 * Why: Clear function name and prevents multiple intervals from running
 */
function startTimer() {
    // Prevent starting if already running
    if (timerState.isRunning) return;
    
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
    
    // Better notification than alert - less intrusive
    showCompletionNotification();
    
    // Reset to default time
    timerState.timeLeft = timerState.defaultTime;
    updateTimerDisplay();
    
    // Play completion sound
    playNotification('complete');
}

// ============================================
// UX ENHANCEMENTS
// ============================================

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

/**
 * Plays a notification sound
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

// ============================================
// EVENT LISTENERS
// ============================================

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
// INITIALIZATION
// ============================================

// Initialize the timer display and button states
// Why: Ensures UI is correct on page load
updateTimerDisplay();
updateButtonStates();

// Add CSS animations dynamically (could also be in CSS file)
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

