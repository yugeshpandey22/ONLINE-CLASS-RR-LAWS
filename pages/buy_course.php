<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$course_id = intval($_GET['id'] ?? 0);
if ($course_id <= 0) { header("Location: courses.php"); exit; }

$res = $conn->query("SELECT * FROM courses WHERE id = $course_id AND active = 1");
$course = $res->fetch_assoc();
if (!$course) { header("Location: courses.php"); exit; }

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Check if already enrolled
$check = $conn->query("SELECT id FROM enrollments WHERE user_id = $user_id AND course_id = $course_id AND payment_status = 'completed'");
if ($check->num_rows > 0) {
    header("Location: dashboard.php");
    exit;
}

$msg = "";
if (isset($_POST['submit_payment'])) {
    $txn_id = trim($_POST['txn_id']);
    if (!empty($txn_id)) {
        // Create a pending enrollment
        $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, payment_status, transaction_id) VALUES (?, ?, 'pending', ?)");
        $stmt->bind_param("iis", $user_id, $course_id, $txn_id);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>Payment snapshot submitted! Admin will verify and unlock your course within 2-4 hours. 🔓</div>";
        }
    }
}
?>

<main style="background: var(--light-bg); padding: 120px 0 100px;">
    <div class="container" style="max-width: 900px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">
            <!-- Left: Order Summary -->
            <div>
                <h2 style="font-size: 2.22rem; font-weight: 800; color: var(--dark-bg); margin-bottom: 30px;">Order &middot; Complete Details</h2>
                <div style="background: white; padding: 40px; border-radius: 30px; box-shadow: 0 40px 80px rgba(0,0,0,0.05);">
                    <img src="../<?php echo !empty($course['image']) ? $course['image'] : 'assets/images/hero.png'; ?>" style="width: 100%; border-radius: 20px; margin-bottom: 25px;">
                    <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 10px;"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 30px;">Masterclass Program enrollment for lifetime access.</p>
                    <hr class="opacity-5 my-4">
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700;">
                        <span>Final Price</span>
                        <span class="text-primary">₹<?php echo number_format($course['price'], 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Right: Payment Section -->
            <div>
                <h2 style="font-size: 2.22rem; font-weight: 800; color: var(--dark-bg); margin-bottom: 30px;">Secure &middot; Payment</h2>
                <div style="background: white; padding: 40px; border-radius: 30px; box-shadow: 0 40px 80px rgba(0,0,0,0.05); border: 2px solid var(--primary);">
                    <?php echo $msg; ?>
                    
                    <div style="text-align: center; margin-bottom: 40px;">
                        <p style="font-weight: 700; color: var(--dark-bg); margin-bottom: 20px;">1. Pay via UPI &middot; Scan QR</p>
                        
                        <div class="qr-container" style="background: #fff; width: 100%; padding: 20px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid var(--border); margin-bottom: 20px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=upi://pay?pa=yugeshpandey22@okaxis%26pn=RR%20LAWS%26am=<?php echo $course['price']; ?>%26cu=INR" style="width: 250px; height: 250px; border-radius: 12px;">
                            <div style="margin-top: 15px;">
                                <span style="background: #f1f5f9; padding: 8px 15px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">
                                    UPI ID: <span style="color: var(--primary);">yugeshpandey22@okaxis</span>
                                </span>
                            </div>
                        </div>
                        
                        <p style="margin-top: 15px; font-weight: 800; font-size: 1.5rem; color: var(--dark-bg);">Amount: <span class="text-primary">₹<?php echo number_format($course['price'], 2); ?></span></p>
                    </div>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">2. Verify &middot; Transaction ID (UTR)</label>
                            <input type="text" name="txn_id" class="form-control form-control-lg border-2" required placeholder="12 Digit UTR Number" style="border-radius: 12px; border-color: var(--primary);">
                            <small class="text-muted">Submit the UTR/Reference number from your payment receipt.</small>
                        </div>
                        
                        <button type="submit" name="submit_payment" class="btn btn-primary w-100 py-3 rounded-pill fw-bold" style="font-size: 1.2rem; transform: translateY(0); transition: 0.3s; box-shadow: 0 15px 35px rgba(212, 175, 55, 0.3);">Submit Payment Verification &rarr;</button>
                    </form>
                    
                    <div style="margin-top: 30px; padding: 20px; background: rgba(212, 175, 55, 0.05); border-radius: 15px; border-left: 5px solid var(--primary);">
                        <p style="font-size: 0.85rem; color: #7c5d0a; margin: 0; font-weight: 600;">
                            <i class="fas fa-info-circle"></i> Once submitted, our team will verify the payment within 2-4 hours and your course will be activated in your dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
