<?php
$title = 'Register | AuthBoard';
ob_start();
?>
<div class="box">
    <div class="auth-header">
        <h2>Create Account</h2>
        <p class="auth-subtitle">Join our community today</p>
    </div>
    <form method="POST" action="/register" class="form">
        <label>Name</label>
        <input type="text" name="name" required />
        <label>Email</label>
        <input type="email" name="email" required />
        <label>Password</label>
        <input type="password" name="password" id="password" required 
               oninput="checkPasswordStrength(this.value)" />
        <div class="password-strength">
            <div class="strength-bar">
                <div class="strength-fill" id="strengthFill"></div>
            </div>
            <div class="strength-text" id="strengthText">Enter a password</div>
        </div>

        <button type="submit">Register</button>
        <p>Have an account? <a href="/login">Login</a></p>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.querySelector('.password-strength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    function checkPasswordStrength(password) {
        strengthMeter.className = 'password-strength';
        
        if (password.length === 0) {
            strengthText.textContent = 'Enter a password';
            return;
        }
        
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        if (password.length < 6) {
            strengthMeter.classList.add('strength-weak');
            strengthText.textContent = 'Very Weak';
        } else if (strength <= 2) {
            strengthMeter.classList.add('strength-weak');
            strengthText.textContent = 'Weak';
        } else if (strength === 3) {
            strengthMeter.classList.add('strength-fair');
            strengthText.textContent = 'Fair';
        } else if (strength === 4) {
            strengthMeter.classList.add('strength-good');
            strengthText.textContent = 'Good';
        } else {
            strengthMeter.classList.add('strength-strong');
            strengthText.textContent = 'Strong';
        }
    }
    
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
    });
});
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    
    if (password.length < 8 || 
        !/[A-Z]/.test(password) || 
        !/[0-9]/.test(password)) {
        e.preventDefault();
        alert('Password must be at least 8 characters with uppercase letters and numbers');
        return false;
    }
});
</script>    

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
