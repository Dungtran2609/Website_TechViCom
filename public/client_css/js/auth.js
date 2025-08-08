// Authentication JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeAuth();
});

function initializeAuth() {
    // Check if we're on login or register page
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    
    if (loginForm) {
        setupLoginForm();
    }
    
    if (registerForm) {
        setupRegisterForm();
    }
}

function setupLoginForm() {
    const form = document.getElementById('login-form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        handleLogin();
    });
    
    // Setup real-time validation
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);
}

function setupRegisterForm() {
    const form = document.getElementById('register-form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        handleRegister();
    });
    
    // Setup real-time validation
    const inputs = {
        fullname: document.getElementById('fullname'),
        phone: document.getElementById('phone'),
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        confirmPassword: document.getElementById('confirm-password')
    };
    
    // Fullname validation
    inputs.fullname.addEventListener('blur', function() {
        validateFullname(this.value);
    });
    
    // Phone validation
    inputs.phone.addEventListener('blur', function() {
        validatePhone(this.value);
    });
    
    // Email validation
    inputs.email.addEventListener('blur', function() {
        validateEmail(this.value);
    });
    
    // Password validation with strength meter
    inputs.password.addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });
    
    inputs.password.addEventListener('blur', function() {
        validatePassword(this.value);
    });
    
    // Confirm password validation
    inputs.confirmPassword.addEventListener('blur', function() {
        validatePasswordMatch(inputs.password.value, this.value);
    });
}

function handleLogin() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;
    
    // Show loading state
    showLoadingState('login');
    
    // Simulate API call
    setTimeout(() => {
        // Validation
        if (!validateLoginData(email, password)) {
            hideLoadingState('login');
            return;
        }
        
        // Mock successful login
        const userData = {
            id: 1,
            name: 'Nguyễn Văn A',
            email: email,
            phone: '0123456789',
            avatar: null,
            loginTime: new Date().toISOString()
        };
        
        // Save user data
        localStorage.setItem('user', JSON.stringify(userData));
        
        if (remember) {
            localStorage.setItem('rememberLogin', 'true');
        }
        
        hideLoadingState('login');
        showNotification('Đăng nhập thành công!', 'success');
        
        // Redirect after 1 second
        setTimeout(() => {
            window.location.href = '../index.html';
        }, 1000);
        
    }, 2000);
}

function handleRegister() {
    const formData = {
        fullname: document.getElementById('fullname').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        confirmPassword: document.getElementById('confirm-password').value,
        gender: document.querySelector('input[name="gender"]:checked')?.value,
        birthdate: document.getElementById('birthdate').value,
        terms: document.getElementById('terms').checked,
        newsletter: document.getElementById('newsletter').checked
    };
    
    // Show loading state
    showLoadingState('register');
    
    // Simulate API call
    setTimeout(() => {
        // Validation
        if (!validateRegisterData(formData)) {
            hideLoadingState('register');
            return;
        }
        
        // Mock successful registration
        const userData = {
            id: Date.now(),
            name: formData.fullname,
            email: formData.email,
            phone: formData.phone,
            gender: formData.gender,
            birthdate: formData.birthdate,
            newsletter: formData.newsletter,
            registrationTime: new Date().toISOString()
        };
        
        // Save user data
        localStorage.setItem('user', JSON.stringify(userData));
        
        hideLoadingState('register');
        showNotification('Đăng ký thành công! Chào mừng bạn đến với Techvicom!', 'success');
        
        // Redirect after 2 seconds
        setTimeout(() => {
            window.location.href = '../index.html';
        }, 2000);
        
    }, 3000);
}

function validateLoginData(email, password) {
    let isValid = true;
    
    if (!email || !validateEmail(email)) {
        showFieldError('email', 'Email hoặc số điện thoại không hợp lệ');
        isValid = false;
    }
    
    if (!password || password.length < 6) {
        showFieldError('password', 'Mật khẩu phải có ít nhất 6 ký tự');
        isValid = false;
    }
    
    return isValid;
}

function validateRegisterData(data) {
    let isValid = true;
    
    if (!validateFullname(data.fullname)) isValid = false;
    if (!validatePhone(data.phone)) isValid = false;
    if (!validateEmail(data.email)) isValid = false;
    if (!validatePassword(data.password)) isValid = false;
    if (!validatePasswordMatch(data.password, data.confirmPassword)) isValid = false;
    if (!data.terms) {
        showNotification('Bạn phải đồng ý với điều khoản sử dụng', 'error');
        isValid = false;
    }
    
    return isValid;
}

function validateFullname(name) {
    if (!name || name.trim().length < 2) {
        showFieldError('fullname', 'Họ và tên phải có ít nhất 2 ký tự');
        return false;
    }
    
    if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(name)) {
        showFieldError('fullname', 'Họ và tên chỉ được chứa chữ cái và khoảng trắng');
        return false;
    }
    
    clearFieldError('fullname');
    return true;
}

function validatePhone(phone) {
    if (!phone) {
        showFieldError('phone', 'Số điện thoại là bắt buộc');
        return false;
    }
    
    const phoneRegex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
    if (!phoneRegex.test(phone)) {
        showFieldError('phone', 'Số điện thoại không hợp lệ');
        return false;
    }
    
    clearFieldError('phone');
    return true;
}

function validateEmail(email) {
    if (!email) {
        showFieldError('email', 'Email là bắt buộc');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showFieldError('email', 'Email không hợp lệ');
        return false;
    }
    
    clearFieldError('email');
    return true;
}

function validatePassword(password) {
    if (!password) {
        showFieldError('password', 'Mật khẩu là bắt buộc');
        return false;
    }
    
    if (password.length < 8) {
        showFieldError('password', 'Mật khẩu phải có ít nhất 8 ký tự');
        return false;
    }
    
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    const hasNumber = /\d/.test(password);
    
    if (!hasUpper || !hasLower || !hasNumber) {
        showFieldError('password', 'Mật khẩu phải có chữ hoa, chữ thường và số');
        return false;
    }
    
    clearFieldError('password');
    return true;
}

function validatePasswordMatch(password, confirmPassword) {
    const matchDiv = document.getElementById('password-match');
    
    if (!confirmPassword) {
        showFieldError('confirm-password', 'Xác nhận mật khẩu là bắt buộc');
        return false;
    }
    
    if (password !== confirmPassword) {
        if (matchDiv) {
            matchDiv.classList.remove('hidden');
        }
        showFieldError('confirm-password', 'Mật khẩu không khớp');
        return false;
    }
    
    if (matchDiv) {
        matchDiv.classList.add('hidden');
    }
    clearFieldError('confirm-password');
    return true;
}

function updatePasswordStrength(password) {
    const strengthBars = [
        document.getElementById('strength-1'),
        document.getElementById('strength-2'),
        document.getElementById('strength-3'),
        document.getElementById('strength-4')
    ];
    
    const strengthText = document.getElementById('password-strength');
    
    if (!strengthBars[0]) return;
    
    // Reset all bars
    strengthBars.forEach(bar => {
        bar.className = 'h-1 flex-1 bg-gray-200 rounded';
    });
    
    if (!password) {
        strengthText.textContent = '';
        return;
    }
    
    let strength = 0;
    let strengthLabel = '';
    let strengthColor = '';
    
    // Length check
    if (password.length >= 8) strength++;
    
    // Character type checks
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
    
    // Adjust strength for short passwords
    if (password.length < 8) strength = Math.min(strength, 1);
    
    // Set strength label and color
    switch (strength) {
        case 0:
        case 1:
            strengthLabel = 'Rất yếu';
            strengthColor = 'bg-custom-primary';
            break;
        case 2:
            strengthLabel = 'Yếu';
            strengthColor = 'bg-orange-500';
            break;
        case 3:
            strengthLabel = 'Trung bình';
            strengthColor = 'bg-yellow-500';
            break;
        case 4:
            strengthLabel = 'Mạnh';
            strengthColor = 'bg-green-500';
            break;
        case 5:
            strengthLabel = 'Rất mạnh';
            strengthColor = 'bg-green-600';
            break;
    }
    
    // Update bars
    for (let i = 0; i < Math.min(strength, 4); i++) {
        strengthBars[i].className = `h-1 flex-1 ${strengthColor} rounded`;
    }
    
    // Update text
    strengthText.textContent = strengthLabel;
    strengthText.className = `text-xs mt-1 ${strengthColor.replace('bg-', 'text-')}`;
}

function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    // Add error styling
    field.classList.add('border-custom-primary', 'focus:border-custom-primary', 'focus:ring-red-500');
    field.classList.remove('border-gray-300');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-500 text-xs mt-1';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    // Remove error styling
    field.classList.remove('border-custom-primary', 'focus:border-custom-primary', 'focus:ring-red-500');
    field.classList.add('border-gray-300');
    
    // Remove error message
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function showLoadingState(type) {
    const textElement = document.getElementById(`${type}-text`);
    const spinnerElement = document.getElementById(`${type}-spinner`);
    const submitButton = document.querySelector(`#${type}-form button[type="submit"]`);
    
    if (textElement) textElement.style.display = 'none';
    if (spinnerElement) spinnerElement.classList.remove('hidden');
    if (submitButton) submitButton.disabled = true;
}

function hideLoadingState(type) {
    const textElement = document.getElementById(`${type}-text`);
    const spinnerElement = document.getElementById(`${type}-spinner`);
    const submitButton = document.querySelector(`#${type}-form button[type="submit"]`);
    
    if (textElement) textElement.style.display = 'inline';
    if (spinnerElement) spinnerElement.classList.add('hidden');
    if (submitButton) submitButton.disabled = false;
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = document.getElementById(`${fieldId}-toggle`);
    
    if (!field || !toggle) return;
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        toggle.className = 'fas fa-eye';
    }
}

function loginWithGoogle() {
    showNotification('Chức năng đăng nhập Google sẽ được tích hợp trong phiên bản tiếp theo', 'info');
}

function loginWithFacebook() {
    showNotification('Chức năng đăng nhập Facebook sẽ được tích hợp trong phiên bản tiếp theo', 'info');
}

function registerWithGoogle() {
    showNotification('Chức năng đăng ký Google sẽ được tích hợp trong phiên bản tiếp theo', 'info');
}

function registerWithFacebook() {
    showNotification('Chức năng đăng ký Facebook sẽ được tích hợp trong phiên bản tiếp theo', 'info');
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 bg-white border-l-4 ${type === 'success' ? 'border-green-500' : type === 'error' ? 'border-custom-primary' : 'border-blue-500'} rounded-lg shadow-lg p-4 max-w-sm`;
    
    const iconClass = type === 'success' ? 'fa-check-circle text-green-500' : 
                      type === 'error' ? 'fa-exclamation-circle text-red-500' : 
                      'fa-info-circle text-blue-500';
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas ${iconClass}"></i>
            </div>
            <div class="ml-3">
                <p class="text-gray-800 text-sm">${message}</p>
            </div>
            <button onclick="this.closest('.fixed').remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Check if user is already logged in
function checkLoginStatus() {
    const user = localStorage.getItem('user');
    if (user) {
        // User is logged in, redirect to home
        window.location.href = '../index.html';
    }
}

// Auto-check login status when page loads
if (window.location.pathname.includes('login.html') || window.location.pathname.includes('register.html')) {
    checkLoginStatus();
}
