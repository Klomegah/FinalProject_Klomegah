// LOGIN AND SIGNUP PAGE - Handles both login and registration forms

// Get references to all the form elements we'll need
const form = document.getElementById("form");
const firstname_input = document.getElementById("firstname-input");
const lastname_input = document.getElementById("lastname-input");
const email_input = document.getElementById("email-input");
const password_input = document.getElementById("password-input");
const confirm_password_input = document.getElementById("confirm-password-input");

// When page loads, check if user is coming from account activation
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    
    // Show success message using SweetAlert2 if user just activated account
    if (message === 'activated') {
        SwalAlert.success('Account Activated', 'Account activated successfully! You can now log in.');
        // Remove message from URL
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (message === 'already_activated') {
        SwalAlert.info('Already Activated', 'Account is already activated. You can log in.');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});


// Handle form submission for both login and signup
form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Stop the form from submitting normally - we'll use AJAX instead

    let errors = [];
    // Check if we're on the signup page (signup has firstname input, login doesn't)
    if (firstname_input){
        // We're on the signup page - validate all signup fields
        errors = getSignUpErrors(firstname_input.value,
                                 lastname_input.value, 
                                 email_input.value, 
                                 password_input.value,
                                  confirm_password_input.value);

        // If all fields are valid, send registration request to server
        if (errors.length === 0) {
            try {
                const response = await fetch('../Authentication/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        firstname: firstname_input.value.trim(),
                        lastname: lastname_input.value.trim(),
                        email: email_input.value.trim(),
                        password: password_input.value
                    })
                });

                const data = await response.json();
                console.log('Registration response:', data);

                // If registration was successful, show the activation popup
                if (data.success) {
                    // Get references to the activation modal elements
                    const modal = document.getElementById('activation-modal');
                    const activateBtn = document.getElementById('activate-account-btn');
                    const wrapper = document.querySelector('.wrapper');
                    
                    console.log('Modal elements:', {modal: !!modal, activateBtn: !!activateBtn, token: !!data.activation_token});
                    
                    // If we have all the elements we need, show the activation modal
                    if (modal && activateBtn && data.activation_token) {
                        // Hide the signup form and show the activation modal
                        if (wrapper) wrapper.style.display = 'none';
                        
                        // Store the activation token on the button so we can use it later
                        activateBtn.setAttribute('data-token', data.activation_token);
                        modal.style.display = 'flex';
                        
                        // When user clicks "Activate Account" button, activate their account
                        activateBtn.onclick = async function() {
                            const token = this.getAttribute('data-token');
                            if (!token) {
                                SwalAlert.error('Activation Error', 'No activation token found. Please register again.');
                                return;
                            }
                            
                            this.disabled = true;
                            this.textContent = 'Activating...';
                            
                            try {
                                const activateResponse = await fetch('../Authentication/activate.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: 'token=' + encodeURIComponent(token)
                                });
                                
                                const activateData = await activateResponse.json();
                                
                                if (activateData.success) {
                                    // Show success message and redirect
                                    SwalAlert.success('Account Activated', activateData.message || 'Account activated! Redirecting to login...').then(() => {
                                        window.location.href = 'login-html.php?message=activated';
                                    });
                                } else {
                                    // Show error message and re-enable button
                                    SwalAlert.error('Activation Failed', activateData.error || 'Activation failed. Please try again.');
                                    this.disabled = false;
                                    this.textContent = 'Activate Account';
                                }
                            } catch (error) {
                                // Show network error and re-enable button
                                SwalAlert.error('Network Error', 'Network error. Please try again.');
                                this.disabled = false;
                                this.textContent = 'Activate Account';
                            }
                        };

                    } 
                    
                } else {
                    // Show error message using SweetAlert2
                    SwalAlert.error('Registration Failed', data.error || 'Registration failed. Please try again.');
                }
            } catch (error) {
                // Show network error using SweetAlert2
                SwalAlert.error('Network Error', 'Network error. Please try again.');
            }
        } else {
            // Show validation errors using SweetAlert2
            SwalAlert.error('Validation Error', errors.join('. '));
        }
    } else {
        // We're on the login page - validate email and password
        errors = getLoginErrors(email_input.value, password_input.value);
        
        // If validation passes, send login request to server
        if (errors.length === 0) {
            try {
                const response = await fetch('../Authentication/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email_input.value.trim(),
                        password: password_input.value
                    })
                });

                const data = await response.json();

                // If login was successful, redirect to the Pomodoro timer page
                if (data.success) {
                    window.location.href = '../PomodoroPages/pomodoro-html.php';
                } else {
                    // If login failed, show error message using SweetAlert2
                    SwalAlert.error('Login Failed', data.error || 'Login failed. Please try again.');
                }
            } catch (error) {
                // Show network error using SweetAlert2
                SwalAlert.error('Network Error', 'Network error. Please try again.');
            }
        } else {
            // Show validation errors using SweetAlert2
            SwalAlert.error('Validation Error', errors.join('. '));
        }
    }
});



// Validate all the signup form fields and return any errors found
function getSignUpErrors(firstname, lastname, email, password, confirm_password){
    let errors = [];
    
    // Check first name - must exist and be at least 2 characters
    if (!firstname || firstname.trim() == ''){
        errors.push("First name is required");
        firstname_input.parentElement.classList.add('incorrect');
    } else if (firstname.trim().length < 2){
        errors.push("First name must be at least 2 characters long");
        firstname_input.parentElement.classList.add('incorrect');
    }

    // Check last name - must exist and be at least 2 characters
    if (!lastname || lastname.trim() == ''){
        errors.push("Last name is required");
        lastname_input.parentElement.classList.add('incorrect');
    } else if (lastname.trim().length < 2){
        errors.push("Last name must be at least 2 characters long");
        lastname_input.parentElement.classList.add('incorrect');
    }

    // Check email - must exist and be in valid email format
    if (!email || email.trim() == ''){
        errors.push("Email is required");
        email_input.parentElement.classList.add('incorrect');
    }else {
         // Use regex pattern to check if email looks valid (has @ and domain)
         const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            errors.push("Please enter a valid email address");
            email_input.parentElement.classList.add('incorrect');
        }
    }
    

    // Check password - must exist and be at least 8 characters
    if (!password || password.trim() == ''){
        errors.push("Password is required");
        password_input.parentElement.classList.add('incorrect');
    }else if (password.length < 8){
        errors.push("Password must be at least 8 characters long");
        password_input.parentElement.classList.add('incorrect');
    }

    // Check confirm password - must match the password field
    if (!confirm_password || confirm_password == ''){
        errors.push("Confirm password is required");
        confirm_password_input.parentElement.classList.add('incorrect');
    }else if (password !== confirm_password){
        errors.push("Passwords do not match");
        confirm_password_input.parentElement.classList.add('incorrect');
    }
    return errors;
}

// Validate the login form fields and return any errors found
function getLoginErrors(email, password){
    let errors = [];
    
    // Check email - must exist and be in valid format
    if (!email || email.trim() == ''){
        errors.push("Email is required");
        email_input.parentElement.classList.add('incorrect');
    }else {
         // Use regex to check if email format is valid
         const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            errors.push("Please enter a valid email address");
            email_input.parentElement.classList.add('incorrect');
        }
    }

    // Check password - must exist and be at least 8 characters
    if (!password || password.trim() == ''){
        errors.push("Password is required");
        password_input.parentElement.classList.add('incorrect');
    }else if (password.length < 8){
        errors.push("Password must be at least 8 characters long");
        password_input.parentElement.classList.add('incorrect');
    }

    return errors;
}

// Remove error styling when user starts typing in any input field
// This gives immediate feedback that they're fixing the error
const allInputs = [firstname_input, lastname_input, email_input, password_input, confirm_password_input].filter(input => input !== null);

allInputs.forEach(input => {
    if (input){
        // When user types in a field, remove the red error styling
        input.addEventListener('input', () => {
            if (input.parentElement.classList.contains('incorrect')){
                input.parentElement.classList.remove('incorrect');
            }
        });
    }
});



