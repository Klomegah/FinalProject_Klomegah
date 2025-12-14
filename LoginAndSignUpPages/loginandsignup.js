const form = document.getElementById("form");
const firstname_input = document.getElementById("firstname-input");
const lastname_input = document.getElementById("lastname-input");
const email_input = document.getElementById("email-input");
const password_input = document.getElementById("password-input");
const confirm_password_input = document.getElementById("confirm-password-input");
const error_message = document.getElementById("error-message");
const success_message = document.getElementById("success-message");

// Check for URL parameters (activation messages)
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    
    if (message === 'activated') {
        if (success_message) {
            success_message.textContent = 'Account activated successfully! You can now log in.';
            success_message.style.display = 'block';

            // Remove message from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    } else if (message === 'already_activated') {
        if (success_message) {
            success_message.textContent = 'Account is already activated. You can log in.';
            success_message.style.display = 'block';
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
});


form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Always prevent default to handle with JavaScript

    let errors = [];
    if (firstname_input){
        //if we have a first name input, we are on the sign up page
        errors = getSignUpErrors(firstname_input.value,
                                 lastname_input.value, 
                                 email_input.value, 
                                 password_input.value,
                                  confirm_password_input.value);

        // If validation passes, submit via AJAX
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

                if (data.success) {
                    // Show activation popup modal
                    const modal = document.getElementById('activation-modal');
                    const activateBtn = document.getElementById('activate-account-btn');
                    const statusMsg = document.getElementById('activation-status');
                    const wrapper = document.querySelector('.wrapper');
                    
                    console.log('Modal elements:', {modal: !!modal, activateBtn: !!activateBtn, token: !!data.activation_token});
                    
                    if (modal && activateBtn && data.activation_token) {
                        // Hide wrapper (form is inside), show modal
                        if (wrapper) wrapper.style.display = 'none';
                        
                        // Store token for activation
                        activateBtn.setAttribute('data-token', data.activation_token);
                        modal.style.display = 'flex';
                        
                        // Handle activation button click
                        activateBtn.onclick = async function() {
                            const token = this.getAttribute('data-token');
                            if (!token) {
                                statusMsg.style.color = 'red';
                                statusMsg.textContent = 'No activation token found. Please register again.';
                                return;
                            }
                            
                            this.disabled = true;
                            this.textContent = 'Activating...';
                            statusMsg.textContent = '';
                            
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
                                    statusMsg.style.color = 'green';
                                    statusMsg.textContent = activateData.message || 'Account activated! Redirecting...';
                                    setTimeout(() => {
                                        window.location.href = 'login-html.php?message=activated';
                                    }, 1500);
                                } else {
                                    statusMsg.style.color = 'red';
                                    statusMsg.textContent = activateData.error || 'Activation failed. Please try again.';
                                    this.disabled = false;
                                    this.textContent = 'Activate Account';
                                }
                            } catch (error) {
                                statusMsg.style.color = 'red';
                                statusMsg.textContent = 'Network error. Please try again.';
                                this.disabled = false;
                                this.textContent = 'Activate Account';
                            }
                        };

                    } 
                    
                } else {
                    // Show error message
                    error_message.style.color = 'red';
                    error_message.innerText = data.error || 'Registration failed. Please try again.';
                    error_message.style.display = 'block';
                    
                    setTimeout(() => {
                        error_message.style.display = 'none';
                        error_message.innerText = '';
                    }, 5000);
                }
            } catch (error) {
                error_message.style.color = 'red';
                error_message.innerText = 'Network error. Please try again.';
                error_message.style.display = 'block';
            }
        } else {
            // Validation errors
            error_message.style.color = 'red';
            error_message.innerText = errors.join('. ');
            error_message.style.display = 'block';

            //Auto-hide error message after 5 seconds
            setTimeout(() => {
                error_message.style.display = 'none';
                error_message.innerText = '';
            }, 5000);
        }
    } else {
        //else we are on the login page
        errors = getLoginErrors(email_input.value, password_input.value);
        
        // If validation passes, submit login via AJAX
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

                if (data.success) {
                    // Redirect to Pomodoro page
                    window.location.href = '../PomodoroPages/pomodoro-html.php';
                } else {
                    // Show error message
                    error_message.style.color = 'red';
                    error_message.innerText = data.error || 'Login failed. Please try again.';
                    error_message.style.display = 'block';
                    
                    setTimeout(() => {
                        error_message.style.display = 'none';
                        error_message.innerText = '';
                    }, 5000);
                }
            } catch (error) {
                error_message.style.color = 'red';
                error_message.innerText = 'Network error. Please try again.';
                error_message.style.display = 'block';
            }
        } else {
            // Validation errors
            error_message.style.color = 'red';
            error_message.innerText = errors.join('. ');
            error_message.style.display = 'block';

            setTimeout(() => {
                error_message.style.display = 'none';
                error_message.innerText = '';
            }, 5000);
        }
    }
});



function getSignUpErrors(firstname, lastname, email, password, confirm_password){
    let errors = [];
    //validate first name
    if (!firstname || firstname.trim() == ''){
        errors.push("First name is required");
        firstname_input.parentElement.classList.add('incorrect');
    } else if (firstname.trim().length < 2){
        errors.push("First name must be at least 2 characters long");
        firstname_input.parentElement.classList.add('incorrect');
    }

    //validate last name
    if (!lastname || lastname.trim() == ''){
        errors.push("Last name is required");
        lastname_input.parentElement.classList.add('incorrect');
    } else if (lastname.trim().length < 2){
        errors.push("Last name must be at least 2 characters long");
        lastname_input.parentElement.classList.add('incorrect');
    }

    //validate email
    if (!email || email.trim() == ''){
        errors.push("Email is required");
        email_input.parentElement.classList.add('incorrect');
    }else {
         const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            errors.push("Please enter a valid email address");
            email_input.parentElement.classList.add('incorrect');
        }
    }
    

    //validate password
    if (!password || password.trim() == ''){
        errors.push("Password is required");
        password_input.parentElement.classList.add('incorrect');
    }else if (password.length < 8){
        errors.push("Password must be at least 8 characters long");
        password_input.parentElement.classList.add('incorrect');
    }

    //validate confirm password
    if (!confirm_password || confirm_password == ''){
        errors.push("Confirm password is required");
        confirm_password_input.parentElement.classList.add('incorrect');
    }else if (password !== confirm_password){
        errors.push("Passwords do not match");
        confirm_password_input.parentElement.classList.add('incorrect');
    }
    return errors;
}

function getLoginErrors(email, password){
    let errors = [];
    
       //validate email
    if (!email || email.trim() == ''){
        errors.push("Email is required");
        email_input.parentElement.classList.add('incorrect');
    }else {
         const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            errors.push("Please enter a valid email address");
            email_input.parentElement.classList.add('incorrect');
        }
    }

    //validate password
    if (!password || password.trim() == ''){
        errors.push("Password is required");
        password_input.parentElement.classList.add('incorrect');
    }else if (password.length < 8){
        errors.push("Password must be at least 8 characters long");
        password_input.parentElement.classList.add('incorrect');
    }

    return errors;
}

const allInputs = [firstname_input, lastname_input, email_input, password_input, confirm_password_input].filter(input => input !== null);

allInputs.forEach(input => {
    if (input){
        input.addEventListener('input', () => {
            if (input.parentElement.classList.contains('incorrect')){
                input.parentElement.classList.remove('incorrect');
                error_message.innerText = '';
            }
        }
        );
    }

});


