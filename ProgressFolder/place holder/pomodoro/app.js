// State management
let state = {
  currentMode: 'focus', // 'focus', 'shortBreak', 'longBreak'
  timeLeft: 25 * 60, // seconds
  isRunning: false,
  intervalId: null,
  sessionCount: 0,
  completedPomodoros: 0,
  tasks: [],
  settings: {
    focusLength: 25,
    shortBreakLength: 5,
    longBreakLength: 15,
    longBreakAfter: 4,
    autoStartFocus: true,
    autoStartBreak: true,
    ambientSound: false
  },
  todayMinutes: 0,
  streak: 0,
  lastActivityDate: null
};

// DOM elements
const elements = {
  timeDisplay: document.getElementById('timeDisplay'),
  startBtn: document.getElementById('startBtn'),
  pauseBtn: document.getElementById('pauseBtn'),
  skipBtn: document.getElementById('skipBtn'),
  sessionStatus: document.getElementById('sessionStatus'),
  sessionCount: document.getElementById('sessionCount'),
  todayMinutes: document.getElementById('todayMinutes'),
  streakValue: document.getElementById('streakValue'),
  modeButtons: document.querySelectorAll('.mode-btn'),
  taskList: document.getElementById('taskList'),
  taskForm: document.getElementById('taskForm'),
  taskTitle: document.getElementById('taskTitle'),
  taskEstimate: document.getElementById('taskEstimate'),
  settingsPanel: document.getElementById('settingsPanel'),
  openSettingsBtn: document.getElementById('openSettingsBtn'),
  closeSettingsBtn: document.getElementById('closeSettingsBtn'),
  settingsForm: document.getElementById('settingsForm'),
  clearDataBtn: document.getElementById('clearDataBtn'),
  addTemplateBtn: document.getElementById('addTemplateBtn')
};

// Initialize
function init() {
  loadFromStorage();
  updateUI();
  setupEventListeners();
  checkStreak();
}

// Load state from localStorage
function loadFromStorage() {
  const saved = localStorage.getItem('pomodoroState');
  if (saved) {
    const parsed = JSON.parse(saved);
    state.tasks = parsed.tasks || [];
    state.settings = { ...state.settings, ...(parsed.settings || {}) };
    state.completedPomodoros = parsed.completedPomodoros || 0;
    state.sessionCount = parsed.sessionCount || 0;
    state.todayMinutes = parsed.todayMinutes || 0;
    state.streak = parsed.streak || 0;
    state.lastActivityDate = parsed.lastActivityDate || null;
  }
  
  // Load settings into form
  document.getElementById('focusInput').value = state.settings.focusLength;
  document.getElementById('shortBreakInput').value = state.settings.shortBreakLength;
  document.getElementById('longBreakInput').value = state.settings.longBreakLength;
  document.getElementById('cycleInput').value = state.settings.longBreakAfter;
  document.getElementById('autoStartFocus').checked = state.settings.autoStartFocus;
  document.getElementById('autoStartBreak').checked = state.settings.autoStartBreak;
  document.getElementById('ambientSoundToggle').checked = state.settings.ambientSound;
  
  resetTimer();
}

// Save state to localStorage
function saveToStorage() {
  localStorage.setItem('pomodoroState', JSON.stringify({
    tasks: state.tasks,
    settings: state.settings,
    completedPomodoros: state.completedPomodoros,
    sessionCount: state.sessionCount,
    todayMinutes: state.todayMinutes,
    streak: state.streak,
    lastActivityDate: state.lastActivityDate
  }));
}

// Setup event listeners
function setupEventListeners() {
  // Timer controls
  elements.startBtn.addEventListener('click', startTimer);
  elements.pauseBtn.addEventListener('click', pauseTimer);
  elements.skipBtn.addEventListener('click', skipTimer);
  
  // Mode switching
  elements.modeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      if (state.isRunning) return;
      const mode = btn.dataset.mode;
      switchMode(mode);
    });
  });
  
  // Task form
  elements.taskForm.addEventListener('submit', addTask);
  
  // Settings
  elements.openSettingsBtn.addEventListener('click', () => {
    elements.settingsPanel.classList.remove('hidden');
  });
  
  elements.closeSettingsBtn.addEventListener('click', () => {
    elements.settingsPanel.classList.add('hidden');
  });
  
  elements.settingsForm.addEventListener('submit', saveSettings);
  
  elements.clearDataBtn.addEventListener('click', () => {
    if (confirm('Are you sure you want to reset all data? This cannot be undone.')) {
      localStorage.removeItem('pomodoroState');
      location.reload();
    }
  });
  
  elements.addTemplateBtn.addEventListener('click', () => {
    const templates = [
      { title: 'Code review', estimate: 2 },
      { title: 'Write documentation', estimate: 3 },
      { title: 'Team meeting', estimate: 1 },
      { title: 'Debug issue', estimate: 2 }
    ];
    const template = templates[Math.floor(Math.random() * templates.length)];
    elements.taskTitle.value = template.title;
    elements.taskEstimate.value = template.estimate;
  });
}

// Format time as MM:SS
function formatTime(seconds) {
  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

// Update UI
function updateUI() {
  elements.timeDisplay.textContent = formatTime(state.timeLeft);
  elements.sessionCount.textContent = `${state.completedPomodoros} / ${state.settings.longBreakAfter}`;
  elements.todayMinutes.textContent = `${state.todayMinutes} min`;
  elements.streakValue.textContent = `${state.streak} ${state.streak === 1 ? 'day' : 'days'}`;
  
  // Update mode buttons
  elements.modeButtons.forEach(btn => {
    btn.classList.toggle('active', btn.dataset.mode === state.currentMode);
  });
  
  // Update button states
  elements.startBtn.style.display = state.isRunning ? 'none' : 'block';
  elements.pauseBtn.style.display = state.isRunning ? 'block' : 'none';
  
  // Update status
  if (!state.isRunning && state.timeLeft === getModeDuration(state.currentMode)) {
    const modeNames = {
      focus: 'Focus',
      shortBreak: 'Short break',
      longBreak: 'Long break'
    };
    elements.sessionStatus.textContent = `Ready for ${modeNames[state.currentMode].toLowerCase()}.`;
  }
  
  renderTasks();
}

// Get duration for current mode
function getModeDuration(mode) {
  const durations = {
    focus: state.settings.focusLength * 60,
    shortBreak: state.settings.shortBreakLength * 60,
    longBreak: state.settings.longBreakLength * 60
  };
  return durations[mode] || durations.focus;
}

// Reset timer to current mode duration
function resetTimer() {
  state.timeLeft = getModeDuration(state.currentMode);
  updateUI();
}

// Switch mode
function switchMode(mode) {
  if (state.isRunning) return;
  state.currentMode = mode;
  resetTimer();
}

// Start timer
function startTimer() {
  if (state.isRunning) return;
  
  state.isRunning = true;
  elements.sessionStatus.textContent = state.currentMode === 'focus' 
    ? 'Focus time! Stay concentrated.' 
    : 'Take a break. You earned it.';
  
  state.intervalId = setInterval(() => {
    state.timeLeft--;
    
    if (state.timeLeft <= 0) {
      completeSession();
    } else {
      updateUI();
    }
  }, 1000);
  
  updateUI();
  playNotification();
}

// Pause timer
function pauseTimer() {
  if (!state.isRunning) return;
  
  state.isRunning = false;
  clearInterval(state.intervalId);
  elements.sessionStatus.textContent = 'Paused. Ready to continue?';
  updateUI();
}

// Skip timer
function skipTimer() {
  if (state.isRunning) {
    pauseTimer();
  }
  completeSession();
}

// Complete session
function completeSession() {
  state.isRunning = false;
  clearInterval(state.intervalId);
  
  if (state.currentMode === 'focus') {
    state.completedPomodoros++;
    state.sessionCount++;
    state.todayMinutes += state.settings.focusLength;
    updateTaskProgress();
    checkStreak();
    
    // Check if long break time
    if (state.completedPomodoros % state.settings.longBreakAfter === 0) {
      state.currentMode = 'longBreak';
      elements.sessionStatus.textContent = 'Great work! Time for a long break.';
    } else {
      state.currentMode = 'shortBreak';
      elements.sessionStatus.textContent = 'Session complete! Take a short break.';
    }
  } else {
    // Break finished, go back to focus
    state.currentMode = 'focus';
    elements.sessionStatus.textContent = 'Break over! Ready for another focus session?';
  }
  
  resetTimer();
  saveToStorage();
  playNotification();
  
  // Auto-start if enabled
  if ((state.currentMode === 'focus' && state.settings.autoStartFocus) ||
      (state.currentMode !== 'focus' && state.settings.autoStartBreak)) {
    setTimeout(() => startTimer(), 1000);
  }
}

// Update task progress
function updateTaskProgress() {
  const activeTask = state.tasks.find(t => !t.completed && t.completedPomodoros < t.estimate);
  if (activeTask) {
    activeTask.completedPomodoros++;
    if (activeTask.completedPomodoros >= activeTask.estimate) {
      activeTask.completed = true;
    }
    saveToStorage();
  }
}

// Check and update streak
function checkStreak() {
  const today = new Date().toDateString();
  const lastDate = state.lastActivityDate ? new Date(state.lastActivityDate).toDateString() : null;
  const yesterday = new Date();
  yesterday.setDate(yesterday.getDate() - 1);
  const yesterdayStr = yesterday.toDateString();
  
  if (lastDate === today) {
    // Already counted today
    return;
  } else if (lastDate === yesterdayStr) {
    // Continuing streak
    state.streak++;
  } else if (lastDate && lastDate !== today && lastDate !== yesterdayStr) {
    // Streak broken
    state.streak = 1;
  } else {
    // First time
    state.streak = state.streak || 1;
  }
  
  state.lastActivityDate = today;
  saveToStorage();
}

// Add task
function addTask(e) {
  e.preventDefault();
  
  const title = elements.taskTitle.value.trim();
  const estimate = parseInt(elements.taskEstimate.value) || 1;
  
  if (!title) return;
  
  state.tasks.push({
    id: Date.now(),
    title,
    estimate,
    completedPomodoros: 0,
    completed: false
  });
  
  elements.taskForm.reset();
  saveToStorage();
  updateUI();
}

// Delete task
function deleteTask(id) {
  state.tasks = state.tasks.filter(t => t.id !== id);
  saveToStorage();
  updateUI();
}

// Toggle task completion
function toggleTask(id) {
  const task = state.tasks.find(t => t.id === id);
  if (task) {
    task.completed = !task.completed;
    saveToStorage();
    updateUI();
  }
}

// Render tasks
function renderTasks() {
  elements.taskList.innerHTML = '';
  
  if (state.tasks.length === 0) {
    elements.taskList.innerHTML = '<li style="color: #94a3b8; text-align: center; padding: 24px;">No tasks yet. Add one above!</li>';
    return;
  }
  
  state.tasks.forEach(task => {
    const li = document.createElement('li');
    li.className = `task-item ${task.completed ? 'completed' : ''}`;
    
    li.innerHTML = `
      <div class="task-meta">
        <p class="task-title">${task.title}</p>
        <p class="task-stats">${task.completedPomodoros} / ${task.estimate} pomodoros</p>
      </div>
      <div class="task-actions">
        <button class="task-btn complete" onclick="toggleTask(${task.id})">
          ${task.completed ? 'Undo' : 'Complete'}
        </button>
        <button class="task-btn delete" onclick="deleteTask(${task.id})">Delete</button>
      </div>
    `;
    
    elements.taskList.appendChild(li);
  });
}

// Save settings
function saveSettings(e) {
  e.preventDefault();
  
  state.settings.focusLength = parseInt(document.getElementById('focusInput').value) || 25;
  state.settings.shortBreakLength = parseInt(document.getElementById('shortBreakInput').value) || 5;
  state.settings.longBreakLength = parseInt(document.getElementById('longBreakInput').value) || 15;
  state.settings.longBreakAfter = parseInt(document.getElementById('cycleInput').value) || 4;
  state.settings.autoStartFocus = document.getElementById('autoStartFocus').checked;
  state.settings.autoStartBreak = document.getElementById('autoStartBreak').checked;
  state.settings.ambientSound = document.getElementById('ambientSoundToggle').checked;
  
  if (!state.isRunning) {
    resetTimer();
  }
  
  saveToStorage();
  elements.settingsPanel.classList.add('hidden');
  updateUI();
}

// Play notification sound
function playNotification() {
  // Create a simple beep using Web Audio API
  try {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.value = 800;
    oscillator.type = 'sine';
    
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
  } catch (e) {
    console.log('Audio not supported');
  }
}

// Make functions globally accessible for onclick handlers
window.toggleTask = toggleTask;
window.deleteTask = deleteTask;

// Initialize app when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}

