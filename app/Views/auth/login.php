<?php
$title = 'Login | StatusBoard';
ob_start();
?>
<div class="box">
    <h2 class="log-in">Sign In</h2>
    <form method="POST" action="/login" class="form">
        <label>Email</label>
        <input type="email" name="email" required />
        <label>Password</label>
        <input type="password" name="password" required />
        <button type="submit">Sign In →</button>
        <p><small>Don't have an account? <a href="/register">Register</a></small></p>
    </form>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
