<?php 
include '../includes/header.php'; 
include '../includes/db.php';
?>

<main style="background: var(--bg-light); padding: 120px 0 100px; display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <div class="container" style="max-width: 500px;">
        <div class="glass-panel" style="background: white; padding: 60px 40px; border-radius: 30px; box-shadow: 0 50px 120px rgba(0,0,0,0.08); border: 1px solid var(--border); text-align: center;">
            <div style="font-size: 3.5rem; margin-bottom: 25px;">🔒</div>
            <h2 style="font-size: 2.5rem; font-weight: 850; color: #000; margin-bottom: 15px;">Forgot Password?</h2>
            <p style="color: var(--text-muted); font-size: 1.15rem; line-height: 1.6; margin-bottom: 45px;">Provide your registered student email address, and we'll send you a secure link to reset your account password.</p>
            
            <form action="send_reset_link.php" method="POST">
                <div style="margin-bottom: 30px; text-align: left;">
                    <label style="display: block; font-weight: 800; font-size: 0.95rem; color: #000; margin-bottom: 10px;">Registered Email Address</label>
                    <input type="email" name="email" required placeholder="example@email.com" style="width: 100%; padding: 18px; border: 2px solid #E2E8F0; border-radius: 15px; font-size: 1.1rem; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif;">
                </div>
                
                <button type="submit" name="reset_req" class="btn btn-primary" style="width: 100%; padding: 20px; border-radius: 50px; font-size: 1.2rem; font-weight: 850; box-shadow: 0 15px 35px rgba(54, 75, 197, 0.25);">Send Reset Link &rarr;</button>
            </form>
            
            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #F1F5F9;">
                <p style="color: var(--text-muted); font-weight: 500;">Remembered your password? <a href="login.php" style="color: var(--primary); font-weight: 850; text-decoration: underline;">Go back to Login</a></p>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
