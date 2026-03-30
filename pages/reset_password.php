<?php 
include '../includes/header.php'; 
include '../includes/db.php';

$token = trim($_GET['token'] ?? '');
$email = trim($_GET['email'] ?? '');
$is_valid = false;
$msg = "";

if (isset($conn) && $token && $email) {
    // Check if token matches and is NOT expired
    $check_q = $conn->query("SELECT * FROM password_reset_tokens WHERE email = '$email' AND token = '$token' AND expires_at > NOW()");
    if ($check_q && $check_q->num_rows > 0) {
        $is_valid = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_pass'])) {
    $new_pass = trim($_POST['new_pass']);
    $confirm_pass = trim($_POST['confirm_pass']);
    
    if ($new_pass === $confirm_pass) {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        
        // Update user password and CLEAN token
        $conn->query("UPDATE users SET password = '$hashed' WHERE email = '$email'");
        $conn->query("DELETE FROM password_reset_tokens WHERE email = '$email'");
        
        // Redirect with Success
        $msg = "<div class='alert alert-success' style='padding: 25px; border-radius: 20px; font-weight: 800; font-size: 1.15rem; margin-bottom: 40px;'>Password Successfully Reset! 🔓 <br> Please login with your new credentials.</div>";
        $is_valid = false; // Hide form after success
    } else {
        $msg = "<div class='alert alert-danger' style='padding: 20px; border-radius: 12px; font-weight: 700; margin-bottom: 25px;'>Passwords do not match! ❌</div>";
    }
}
?>

<main style="background: var(--bg-light); padding: 120px 0 100px; display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <div class="container" style="max-width: 550px;">
        <div class="glass-panel" style="background: white; padding: 60px 50px; border-radius: 40px; box-shadow: 0 40px 100px rgba(0,0,0,0.1); border: 1px solid var(--border); text-align: center;">
            <div style="font-size: 3.5rem; margin-bottom: 25px;">🔑</div>
            <h2 style="font-size: 2.5rem; font-weight: 850; color: #000; margin-bottom: 15px;">Set New Password</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.6; margin-bottom: 45px;">Your reset link has been verified successfully. Please enter your new secure password below to regain access.</p>
            
            <?php echo $msg; ?>

            <?php if($is_valid): ?>
                <form method="POST">
                    <div style="margin-bottom: 25px; text-align: left;">
                        <label style="display: block; font-weight: 800; font-size: 0.95rem; color: #000; margin-bottom: 10px;">New Strong Password</label>
                        <input type="password" name="new_pass" required placeholder="Create Your New Password" style="width: 100%; padding: 18px; border: 2px solid #E2E8F0; border-radius: 15px; font-size: 1.1rem; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif;">
                    </div>
                    <div style="margin-bottom: 40px; text-align: left;">
                        <label style="display: block; font-weight: 800; font-size: 0.95rem; color: #000; margin-bottom: 10px;">Confirm New Password</label>
                        <input type="password" name="confirm_pass" required placeholder="Re-type Your New Password" style="width: 100%; padding: 18px; border: 2px solid #E2E8F0; border-radius: 15px; font-size: 1.1rem; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif;">
                    </div>
                    
                    <button type="submit" name="update_pass" class="btn btn-primary" style="width: 100%; padding: 20px; border-radius: 50px; font-size: 1.25rem; font-weight: 850; box-shadow: 0 15px 35px rgba(54, 75, 197, 0.25);">Reset Password &middot; Unlock Account</button>
                </form>
            <?php elseif(strpos($msg, 'Reset!') === false): ?>
                <!-- If link was invalid or expired -->
                <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); padding: 25px; border-radius: 20px; color: #ef4444; font-weight: 700;">
                    <p style="margin: 0;">Sorry, this reset link is invalid or has expired for security reasons. Please request a new one.</p>
                </div>
                <div style="margin-top: 30px;">
                    <a href="forgot_password.php" class="btn btn-outline" style="padding: 15px 40px; border-radius: 50px; font-weight: 700; color: #4B5563 !important;">Request New Reset Link &rarr;</a>
                </div>
            <?php else: ?>
                <!-- After successful reset -->
                <a href="login.php" class="btn btn-primary" style="padding: 18px 60px; border-radius: 50px; font-weight: 850; font-size: 1.25rem;">Go to Login Portal &rarr;</a>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
