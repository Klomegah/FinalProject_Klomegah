// ============================================
// FORM VALIDATION AND SUBMISSION
// ============================================
// Why: Centralized validation ensures consistent user experience
// Alternative: Could use a validation library like Joi or Yup

// Get form and input elements
// Why: Caching DOM elements improves performance
const form = document.getElementById("form");
const firstname_input = document.getElementById("firstname-input");
const lastname_input = document.getElementById("lastname-input");
const email_input = document.getElementById("email-input");
const password_input = document.getElementById("password-input");
const confirm_password_input = document.getElementById("confirm-password-input");
const error_message = document.getElementById("error-message");

// Form submit event listener
// Why: Using async/await makes async code easier to read than callbacks
form.addEventListener('submit', async (e) => {
  e.preventDefault(); // Always prevent default to handle submission ourselves

  // Determine if we are on sign up or login page based on presence of firstname input
  // Why: Reusing same validation file for both pages reduces code duplication
  let errors = [];
  if (firstname_input) {
    // If we have a first name input, we are on the sign up page
    errors = getSignUpErrors(
      firstname_input.value,
      lastname_input.value, 
      email_input.value, 
      password_input.value,
      confirm_password_input.value
    );
  } else {
    // Else we are on the login page
    errors = getLoginErrors(email_input.value, password_input.value);
  }

  // If validation errors exist, show them and stop
  if (errors.length > 0) {
    error_message.innerText = errors.join('. ');
    error_message.style.display = 'block';

    // Auto-hide error message after 5 seconds
    // Why: Improves UX by clearing old errors automatically
    setTimeout(() => {
      error_message.style.display = 'none';
      error_message.innerText = '';
    }, 5000);

    return; // Stop further execution
  }

  // ============================================
  // ASYNC FORM SUBMISSION
  // ============================================
  // Why: Using fetch API is modern and supports async/await
  // Alternative: Could use XMLHttpRequest or axios library

  // Determine endpoint and payload based on page type
  let endpoint = '';
  let payload = {};

  if (firstname_input) {
    // Sign up page
    endpoint = '../PHP/signup.php';
    payload = {
      firstname: firstname_input.value,
      lastname: lastname_input.value,
      email: email_input.value,
      password: password_input.value,
      confirm_password: confirm_password_input.value
    };
  } else {
    // Login page
    endpoint = '../PHP/login.php';
    payload = {
      email: email_input.value,
      password: password_input.value
    };
  }

  // Send fetch request with JSON payload
  // Why: JSON is standard for API communication, easier to parse than form data
  try {
    const response = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    // Check if response is OK
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    // Try to parse JSON response
    // Why: Wrapping in try-catch handles malformed JSON gracefully
    let result;
    try {
      const responseText = await response.text();
      console.log('Response text:', responseText); // Debug: Remove in production
      result = JSON.parse(responseText);
      console.log('Parsed result:', result); // Debug: Remove in production
    } catch (jsonError) {
      console.error('JSON parse error:', jsonError);
      throw new Error('Invalid response from server. Please try again.');
    }

    // Handle successful response
    if (result.success === true) {
      // Hide any error messages before redirecting
      error_message.style.display = 'none';
      error_message.innerText = '';
      
      // Redirect to dashboard/pomodoro page
      // Why: After successful login/signup, take user to main app
      // Alternative: Could redirect to a welcome page or user's last visited page
      console.log('Login/Signup successful, redirecting...');
      window.location.href = '../pomodoro/pomodoro.html';
    } else {
      // Show error from server
      console.log('Showing error:', result.message);
      error_message.innerText = result.message || 'An error occurred. Please try again.';
      error_message.style.display = 'block';
      
      // Auto-hide error message after 5 seconds
      setTimeout(() => {
        error_message.style.display = 'none';
        error_message.innerText = '';
      }, 5000);
    }
  } catch (error) {
    // Handle network errors or other exceptions
    console.error('Error:', error);
    error_message.innerText = error.message || 'An error occurred. Please try again.';
    error_message.style.display = 'block';

    // Auto-hide error message after 5 seconds
    setTimeout(() => {
      error_message.style.display = 'none';
      error_message.innerText = '';
    }, 5000);
  }
});

// ============================================
// VALIDATION FUNCTIONS
// ============================================
// Why: Separating validation logic makes it reusable and testable
// Alternative: Could use a validation library or schema validation

function getSignUpErrors(firstname, lastname, email, password, confirm_password) {
  let errors = [];
  
  // Validate first name
  if (!firstname || firstname.trim() == '') {
    errors.push("First name is required");
    firstname_input.parentElement.classList.add('incorrect');
  } else if (firstname.trim().length < 2) {
    errors.push("First name must be at least 2 characters long");
    firstname_input.parentElement.classList.add('incorrect');
  }

  // Validate last name
  if (!lastname || lastname.trim() == '') {
    errors.push("Last name is required");
    lastname_input.parentElement.classList.add('incorrect');
  } else if (lastname.trim().length < 2) {
    errors.push("Last name must be at least 2 characters long");
    lastname_input.parentElement.classList.add('incorrect');
  }

  // Validate email
  if (!email || email.trim() == '') {
    errors.push("Email is required");
    email_input.parentElement.classList.add('incorrect');
  } else {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    // Why: Regex validates email format client-side
    // Alternative: Could use HTML5 email input type validation
    if (!emailPattern.test(email)) {
      errors.push("Please enter a valid email address");
      email_input.parentElement.classList.add('incorrect');
    }
  }

  // Validate password
  if (!password || password.trim() == '') {
    errors.push("Password is required");
    password_input.parentElement.classList.add('incorrect');
  } else if (password.length < 8) {
    errors.push("Password must be at least 8 characters long");
    password_input.parentElement.classList.add('incorrect');
  }

  // Validate confirm password
  if (!confirm_password || confirm_password == '') {
    errors.push("Confirm password is required");
    confirm_password_input.parentElement.classList.add('incorrect');
  } else if (password !== confirm_password) {
    errors.push("Passwords do not match");
    confirm_password_input.parentElement.classList.add('incorrect');
  }

  return errors;
}

function getLoginErrors(email, password) {
  let errors = [];
  
  // Validate email
  if (!email || email.trim() == '') {
    errors.push("Email is required");
    email_input.parentElement.classList.add('incorrect');
  } else {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      errors.push("Please enter a valid email address");
      email_input.parentElement.classList.add('incorrect');
    }
  }

  // Validate password
  if (!password || password.trim() == '') {
    errors.push("Password is required");
    password_input.parentElement.classList.add('incorrect');
  } else if (password.length < 8) {
    errors.push("Password must be at least 8 characters long");
    password_input.parentElement.classList.add('incorrect');
  }

  return errors;
}

// ============================================
// INPUT ERROR CLEARING
// ============================================
// Why: Clear visual feedback when user corrects errors improves UX

// Get all inputs (filter out nulls for login page)
const allInputs = [
  firstname_input, 
  lastname_input, 
  email_input, 
  password_input, 
  confirm_password_input
].filter(input => input !== null);

// Add event listeners to clear error styling when user types
// Why: Real-time feedback makes form feel responsive
allInputs.forEach(input => {
  if (input) {
    // Use 'change' for select elements, 'input' for text fields
    // Why: Different event types for different input types
    const eventType = input.tagName === 'SELECT' ? 'change' : 'input';
    input.addEventListener(eventType, () => {
      if (input.parentElement.classList.contains('incorrect')) {
        input.parentElement.classList.remove('incorrect');
        error_message.innerText = '';
      }
    });
  }
});
