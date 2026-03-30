<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>
<?php 
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    
    if($conn) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // VERIFY PASSWORD HASH
            if (password_verify($pass, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: dashboard.php");
                exit;
            } else {
                $msg = "<div class='alert alert-danger' style='padding: 15px; border-radius: 12px; font-weight: 700; margin-bottom: 25px;'>Incorrect password! Please try again. 🔒</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger' style='padding: 15px; border-radius: 12px; font-weight: 700; margin-bottom: 25px;'>No account found with this email. ❌</div>";
        }
    }
}
?>

<main style="background: var(--bg-light); padding: 120px 0 100px; display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <div class="container" style="max-width: 480px;">
        <div class="glass-panel" style="background: white; padding: 50px; border-radius: 30px; box-shadow: 0 50px 100px rgba(0,0,0,0.1); border: 1px solid var(--border);">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 2.22rem; font-weight: 850; color: #000; margin-bottom: 10px;">Welcome Back!</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 500;">Integrated Classes Sasaram Portal</p>
            </div>
            
            <?php echo $msg; ?>

            <form method="POST">
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 700; font-size: 0.9rem; color: #000; margin-bottom: 8px;">Email Address</label>
                    <input type="email" name="email" required placeholder="name@email.com" style="width: 100%; padding: 15px; border: 2px solid #E2E8F0; border-radius: 12px; font-size: 1rem; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif;">
                </div>
                <div style="margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label style="font-weight: 700; font-size: 0.9rem; color: #000;">Password</label>
                        <a href="forgot_password.php" style="font-size: 0.85rem; color: var(--primary); font-weight: 800; text-decoration: underline;">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" required placeholder="Enter Your Password" style="width: 100%; padding: 15px; border: 2px solid #E2E8F0; border-radius: 12px; font-size: 1rem; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif;">
                </div>
                
                <button type="submit" name="login_btn" class="btn btn-primary" style="width: 100%; padding: 18px; border-radius: 50px; font-size: 1.1rem; font-weight: 800; border: none; cursor: pointer; box-shadow: 0 15px 30px rgba(54, 75, 197, 0.22);">Login &middot; Access Academy</button>
            </form>
            
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: var(--text-muted); font-weight: 500;">Haven't joined us yet? <a href="signup.php" style="color: var(--primary); font-weight: 800; text-decoration: underline;">Create Account &rarr;</a></p>
                <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #F1F5F9;">
                    <a href="../admin/login.php" style="color: #94A3B8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Admin Entry &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
