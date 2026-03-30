<?php 
include '../includes/header.php'; 
include '../includes/db.php';

$msg = "";
$status = "info"; // Default state

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_req'])) {
    $email = trim($_POST['email']);
    
    // Check if user exists
    $user_q = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($user_q && $user_q->num_rows > 0) {
        // User exists, Generate token
        $token = bin2hex(random_bytes(16)); // Secure random hex string
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour')); // 1 hour expiry
        
        // Clean old tokens for this email and store new one
        $conn->query("DELETE FROM password_reset_tokens WHERE email = '$email'");
        $token_stmt = $conn->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)");
        $token_stmt->bind_param("sss", $email, $token, $expires);
        $token_stmt->execute();
        
        // --- EMAIL SIMULATION ---
        // In a real server you would use mail() or PHPMailer here.
        // For development, we will SHOW the "sent link" beautifully.
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/RR LAWS/pages/reset_password.php?token=$token&email=" . urlencode($email);
        
        $msg = "<p>A secure password reset link has been generated for you.</p>
                <div style='background: #F1F5F9; padding: 25px; border-radius: 20px; border: 1px dashed var(--primary); margin: 30px 0;'>
                    <p style='color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;'>Developer Mode: Sent to $email</p>
                    <a href='$reset_link' style='color: var(--primary); font-weight: 850; font-size: 1.1rem; text-decoration: underline;'>CLICK HERE TO RESET PASSWORD &rarr;</a>
                </div>
                <p style='font-size: 0.9rem; color: #94A3B8;'>In a LIVE environment, this link is sent directly to the student's inbox.</p>";
        $status = "success";
    } else {
        $msg = "<p>We couldn't find a student account associated with the email <strong>$email</strong>.</p>
                <p style='margin-top: 20px;'>Please ensure you're using the same email you used during registration.</p>";
        $status = "error";
    }
}
?>

<main style="background: var(--bg-light); padding: 120px 0 100px; display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <div class="container" style="max-width: 650px;">
        <div class="glass-panel" style="background: white; padding: 70px 50px; border-radius: 40px; box-shadow: 0 60px 150px rgba(0,0,0,0.1); border: 1px solid var(--border); text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 30px;"><?php echo ($status == 'success' ? '📩' : '🔍'); ?></div>
            <h2 style="font-size: 2.5rem; font-weight: 850; color: #000; margin-bottom: 25px;">
                <?php echo ($status == 'success' ? 'Email Successfully Linked' : 'Email Not Found'); ?>
            </h2>
            
            <div style="font-size: 1.2rem; color: #4B5563; line-height: 1.8;">
                <?php echo $msg; ?>
            </div>
            
            <div style="margin-top: 50px;">
                <a href="login.php" class="btn btn-primary" style="padding: 18px 50px; border-radius: 50px; font-weight: 800; font-size: 1.15rem; min-width: 250px;">Go Back to Login</a>
            </div>
            <?php if($status == 'error'): ?>
                <div style="margin-top: 20px;">
                    <a href="forgot_password.php" style="color: var(--primary); font-weight: 700; font-size: 0.95rem;">Try another Email Address &rarr;</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
