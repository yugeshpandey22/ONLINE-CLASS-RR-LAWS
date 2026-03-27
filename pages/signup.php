<?php include '../includes/header.php'; ?>
<?php 
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass = trim($_POST['password']);
    
    if($conn) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>Email already registered! 🔒</div>";
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed);
            if ($stmt->execute()) {
                $msg = "<div class='alert alert-success'>Registration successful! Please login. 🔓</div>";
            }
        }
    }
}
?>

<main style="background: var(--light-bg); padding: 120px 0 100px; display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <div class="container" style="max-width: 500px;">
        <div class="glass-panel" style="background: white; padding: 50px; border-radius: 30px; box-shadow: 0 50px 100px rgba(0,0,0,0.1); border: 1px solid var(--border);">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--dark-bg); margin-bottom: 15px;">Student Portal</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem;">Unlock premium law modules & certifications.</p>
            </div>
            
            <?php echo $msg; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-lg border-2" required placeholder="Enter Your Name" style="border-radius: 12px;">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Mobile Number</label>
                    <input type="text" name="phone" class="form-control form-control-lg border-2" required placeholder="+91 xxxxx xxxxx" style="border-radius: 12px;">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg border-2" required placeholder="name@email.com" style="border-radius: 12px;">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Secure Password</label>
                    <input type="password" name="password" class="form-control form-control-lg border-2" required placeholder="Create Strong Password" style="border-radius: 12px;">
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold" style="font-size: 1.1rem; box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3);">Create Account &middot; Join Academy</button>
            </form>
            
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: var(--text-muted);">Already part of our academy? <a href="login.php" style="color: var(--primary); font-weight: 700;">Login Here &rarr;</a></p>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
