<?php include '../includes/header.php'; ?>
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>

<main style="background: var(--light-bg); padding: 120px 0 100px;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
            <div>
                <span style="color: var(--primary); font-weight: 700; letter-spacing: 2px;">Academy Student Member</span>
                <h1 style="font-size: 3.5rem; font-weight: 800; color: var(--dark-bg); margin: 10px 0;">Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
            </div>
            <a href="logout.php" class="btn btn-outline" style="border: 2px solid #ef4444; color: #ef4444 !important; border-radius: 12px; font-weight: 700; padding: 10px 25px;">Logout Secure Portal</a>
        </div>

        <?php
        $live = $conn->query("SELECT * FROM live_classes WHERE status = 'live' LIMIT 1");
        if($live && $row = $live->fetch_assoc()):
            $is_public = $row['is_public'];
            $has_access = false;
            if($is_public) {
                $has_access = true;
            } else {
                // Check if user has at least one paid course
                $paid_check = $conn->query("SELECT id FROM enrollments WHERE user_id = $user_id AND payment_status = 'completed'");
                if($paid_check->num_rows > 0) $has_access = true;
            }

            if($has_access):
        ?>
        <div class="live-alert" style="background: linear-gradient(135deg, #ef4444, #991b1b); padding: 25px 40px; border-radius: 24px; color: white; display: flex; align-items: center; justify-content: space-between; margin-bottom: 50px; box-shadow: 0 20px 40px rgba(239, 68, 68, 0.3); animation: pulse-live 2s infinite;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-weight: 800; color: white;"><?php echo htmlspecialchars($row['title']); ?> is LIVE NOW! 🔴</h3>
                    <p style="margin: 0; color: rgba(255,255,255,0.8); font-weight: 600;">Join the real-time session and interact with the faculty.</p>
                </div>
            </div>
            <a href="<?php echo $row['meeting_url']; ?>" target="_blank" class="btn btn-white" style="background: white; color: #ef4444 !important; font-weight: 800; padding: 12px 30px; border-radius: 50px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">JOIN LIVE SESSION &rarr;</a>
        </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 3fr; gap: 60px;">
            <!-- Left: Sidebar -->
            <div class="sticky-top" style="top: 120px; height: fit-content;">
                <div style="background: white; padding: 40px; border-radius: 30px; box-shadow: 0 40px 80px rgba(0,0,0,0.05); border: 2px solid var(--border);">
                    <div style="text-align: center; margin-bottom: 40px;">
                        <div style="background: var(--primary); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.22rem; font-weight: 800; margin: 0 auto 20px;">
                            <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                        </div>
                        <h4 style="margin: 0; font-weight: 800;"><?php echo htmlspecialchars($user_name); ?></h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Member ID: #S-<?php echo $user_id; ?></p>
                    </div>
                </div>
            </div>

            <!-- Right: Content -->
            <div>
                <h2 style="font-size: 2.22rem; font-weight: 800; color: var(--dark-bg); margin-bottom: 40px;">Your Premium <span class="text-gradient">Legal Academy</span> Courses</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                    <?php
                    $enrolls = $conn->query("
                        SELECT c.*, e.payment_status, e.enrolled_at 
                        FROM enrollments e 
                        JOIN courses c ON e.course_id = c.id 
                        WHERE e.user_id = $user_id
                        ORDER BY e.enrolled_at DESC
                    ");
                    if ($enrolls && $enrolls->num_rows > 0):
                        while($course = $enrolls->fetch_assoc()):
                    ?>
                    <div class="glass-panel" style="background: white; border-radius: 24px; padding: 25px; border: 1px solid var(--border); transition: 0.4s;">
                        <img src="../<?php echo !empty($course['image']) ? $course['image'] : 'assets/images/hero.png'; ?>" style="width: 100%; height: 160px; object-fit: cover; border-radius: 16px; margin-bottom: 20px;">
                        <h3 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 12px; color: var(--dark-bg);"><?php echo htmlspecialchars($course['title']); ?></h3>
                        
                        <?php if($course['payment_status'] == 'completed'): ?>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill small" style="font-size: 0.8rem; font-weight: 700;">Active Course</span>
                                <a href="course_detail.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm px-4 rounded-pill">Enter Academy &rarr;</a>
                            </div>
                        <?php else: ?>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill small" style="font-size: 0.8rem; font-weight: 700;">Wait for Approval</span>
                                <span style="font-size: 0.85rem; color: #94A3B8; font-weight: 600;">Status: Pending...</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; else: ?>
                        <div style="grid-column: 1/-1; text-align: center; padding: 60px;">
                            <h3 class="text-muted">No premium courses yet. Start your journey!</h3>
                            <a href="courses.php" class="btn btn-primary mt-3 btn-lg rounded-pill px-5">Browse Courses</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
