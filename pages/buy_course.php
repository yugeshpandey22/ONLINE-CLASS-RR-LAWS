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
                        <p style="font-weight: 700; color: var(--dark-bg); margin-bottom: 20px;">1. Scan the QR Code to Pay</p>
                        <!-- QR Code Placeholder -->
                        <div style="background: #f1f5f9; width: 100%; height: 260px; border-radius: 20px; display: flex; align-items: center; justify-content: center; border: 2px dashed var(--border);">
                           <div style="text-align:center;">
                                <i class="fas fa-qrcode" style="font-size: 4rem; color: var(--border);"></i>
                                <p style="font-size: 0.85rem; color: #94A3B8; margin-top: 15px;">QR Code Image Pending<br>Add via Assets/Images</p>
                           </div>
                        </div>
                        <p style="margin-top: 15px; font-weight: 700; font-size: 1.2rem; color: var(--primary);">Pay: ₹<?php echo number_format($course['price'], 2); ?></p>
                    </div>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">2. Enter Transaction ID / UTR Number</label>
                            <input type="text" name="txn_id" class="form-control form-control-lg border-2" required placeholder="e.g. 402941xxxxx" style="border-radius: 12px;">
                            <small class="text-muted">Enter the unique ID from your Google Pay / PhonePe receipt.</small>
                        </div>
                        
                        <button type="submit" name="submit_payment" class="btn btn-primary w-100 py-3 rounded-pill fw-bold" style="font-size: 1.1rem;">Submit Payment &middot; Request Access</button>
                    </form>
                    
                    <p style="text-align: center; margin-top: 25px; font-size: 0.8rem; color: #94A3B8;">
                        <i class="fas fa-shield-alt"></i> Manual Verification ensures 100% course security. Account is unlocked once ID is verified.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
