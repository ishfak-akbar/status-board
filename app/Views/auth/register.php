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
        <input type="password" name="password" required />
        <button type="submit">Register</button>
        <p>Have an account? <a href="/login">Login</a></p>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
