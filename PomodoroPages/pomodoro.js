
// Selecting elements from the DOM
const startButton = document.getElementById('start-btn');
const pauseButton = document.getElementById('stop-btn');
const resetButton = document.getElementById('reset-btn');
const timerDisplay = document.getElementById('timer');

// Initial time setup
let timeLeft = 1500; // 25 minutes in seconds
let interval;

// Function to update the timer display
function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;

    timerDisplay.innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}


// Start timer function
const startTimer = () => {
    interval = setInterval(() => {
        timeLeft--;
        updateTimer();
        if (timeLeft <= 0) {
            clearInterval(interval);
            alert("Time's up!");
            timeLeft = 1500; // Reset to 25 minutes
            updateTimer();
        }

    }, 1000);
}

// Stop timer function
const stopTimer = () => {
    clearInterval(interval);
}

// Reset timer function
const resetTimer = () => {
    clearInterval(interval);
    timeLeft = 1500; // Reset to 25 minutes
    updateTimer();
}

//connecting buttons to functions
startButton.addEventListener('click', startTimer);
pauseButton.addEventListener('click', stopTimer);
resetButton.addEventListener('click', resetTimer)

// this is basic setup for a pomodoro timer and I need to enhance it further