<?php include '../includes/header.php'; ?>
<?php 
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    
    if($conn) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user && password_verify($pass, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "<div class='alert alert-danger'>Invalid email or password! 🔒</div>";
        }
    }
}
?>

<main style="background: var(--light-bg); padding: 120px 0 100px; display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <div class="container" style="max-width: 480px;">
        <div class="glass-panel" style="background: white; padding: 50px; border-radius: 30px; box-shadow: 0 50px 100px rgba(0,0,0,0.1); border: 1px solid var(--border);">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--dark-bg); margin-bottom: 10px;">Welcome Back!</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem;">Access your lectures and portal.</p>
            </div>
            
            <?php echo $msg; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Register Email ID</label>
                    <input type="email" name="email" class="form-control form-control-lg border-2" required placeholder="name@email.com" style="border-radius: 12px;">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Secure Password</label>
                    <input type="password" name="password" class="form-control form-control-lg border-2" required placeholder="Your Strong Password" style="border-radius: 12px;">
                </div>
                
                <button type="submit" name="login_btn" class="btn btn-primary w-100 py-3 rounded-pill fw-bold" style="font-size: 1.1rem; box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3);">Login Portal &middot; Access Academy</button>
            </form>
            
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: var(--text-muted);">Haven't joined us yet? <a href="signup.php" style="color: var(--primary); font-weight: 700;">Create Account &rarr;</a></p>
                <p style="margin-top: 15px;"><a href="../admin/login.php" style="color: #94A3B8; font-size: 0.8rem; font-weight: 600;">Admin Panel Key Access &rarr;</a></p>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
