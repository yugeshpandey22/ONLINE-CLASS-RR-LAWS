<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>
<?php 
$course_id = intval($_GET['id'] ?? 0);
$course = null;
if ($conn && $course_id > 0) {
    $res = $conn->query("SELECT * FROM courses WHERE id = $course_id AND active = 1");
    $course = $res->fetch_assoc();
}

if (!$course) {
    header("Location: courses.php");
    exit;
}

// Check enrollment
$is_enrolled = false; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id']) && $conn) {
    $uid = $_SESSION['user_id'];
    $check_enroll = $conn->query("SELECT id FROM enrollments WHERE user_id = $uid AND course_id = $course_id AND payment_status = 'completed'");
    if ($check_enroll && $check_enroll->num_rows > 0) {
        $is_enrolled = true;
    }
}
?>

<main>
    <section class="page-header" style="background: var(--dark-bg); padding: 120px 0 80px; text-align: left; color: white;">
        <div class="container">
            <span style="color: var(--primary); font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Premium Program</span>
            <h1 style="font-size: 4rem; line-height: 1.1; margin: 20px 0 30px; font-weight: 800; color: white;"><?php echo htmlspecialchars($course['title']); ?></h1>
            <div style="display: flex; align-items: center; gap: 30px; margin-top: 40px;">
                <h3 style="font-size: 2.8rem; margin: 0; color: var(--primary); font-weight: 850;">₹<?php echo number_format($course['price'], 0); ?></h3>
                <?php if($is_enrolled): ?>
                    <div style="padding: 15px 40px; border-radius: 50px; background: #E8F5E9; color: #1B5E20; display: flex; align-items: center; gap: 10px; font-weight: 850;">
                        <i class="fas fa-check-circle"></i> YOU ARE ENROLLED
                    </div>
                <?php else: ?>
                    <a href="buy_course.php?id=<?php echo $course_id; ?>" class="btn btn-primary" style="padding: 15px 50px; border-radius: 50px; font-weight: 700; font-size: 1.2rem; box-shadow: 0 15px 30px rgba(54, 75, 197, 0.3);">Enroll Now &middot; Start Learning</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="container" style="padding: 80px 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 80px;">
            <!-- Syllabus & Curriculum -->
            <div>
                <h2 style="font-size: 2.2rem; margin-bottom: 30px; font-weight: 700; display: flex; align-items: center; gap: 15px;"><i class="fas fa-book-open text-primary"></i> Curriculum Overview</h2>
                <div style="background: white; padding: 40px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                    <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                </div>

                <h2 style="font-size: 2.2rem; margin: 60px 0 30px; font-weight: 700; display: flex; align-items: center; gap: 15px;"><i class="fas fa-play-circle text-danger"></i> Course Modules</h2>
                <div style="display: grid; gap: 15px;">
                    <?php
                    $lessons = $conn->query("SELECT * FROM posts WHERE course_id = $course_id ORDER BY created_at ASC");
                    if($lessons && $lessons->num_rows > 0):
                        $count = 1;
                        while($lesson = $lessons->fetch_assoc()):
                    ?>
                    <div style="background: <?php echo $is_enrolled ? 'white' : 'rgba(0,0,0,0.03)'; ?>; padding: 25px 35px; border-radius: 16px; border: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; transition: 0.3s; opacity: <?php echo $is_enrolled ? '1' : '0.7'; ?>;">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: rgba(0,0,0,0.1);"><?php echo sprintf("%02d", $count++); ?></span>
                            <h4 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--dark-bg);"><?php echo htmlspecialchars($lesson['title']); ?></h4>
                        </div>
                        <?php if($is_enrolled): ?>
                            <a href="single.php?id=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-primary px-4 rounded-pill">Watch Module &rarr;</a>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(0,0,0,0.05); color: #94A3B8; padding: 8px 15px; border-radius: 50px; font-size: 0.85rem; font-weight: 600;"><i class="fas fa-lock me-1"></i> Locked Content</span>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; else: ?>
                    <div style="text-align: center; padding: 40px;">
                        <p class="text-muted">Modules are currently being uploaded by the administrator.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sticky Enrollment Card -->
            <div class="sticky-top" style="top: 120px; height: fit-content;">
                <div style="background: white; padding: 40px; border-radius: 30px; box-shadow: 0 40px 90px rgba(0,0,0,0.12); border: 1px solid var(--primary);">
                    <img src="../<?php echo !empty($course['image']) ? $course['image'] : 'assets/images/hero.png'; ?>" style="width: 100%; border-radius: 20px; margin-bottom: 25px; box-shadow: 0 15px 40px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 1.5rem; margin-bottom: 15px; font-weight: 800;">Join the Masterclass</h3>
                    <ul style="list-style: none; padding: 0; margin-bottom: 30px;">
                        <li style="margin-bottom: 12px; display: flex; gap: 10px; color: var(--text-muted); font-size: 1rem;"><i class="fas fa-check-circle text-success" style="margin-top: 4px;"></i> Intensive Curriculum</li>
                        <li style="margin-bottom: 12px; display: flex; gap: 10px; color: var(--text-muted); font-size: 1rem;"><i class="fas fa-check-circle text-success" style="margin-top: 4px;"></i> Certificate on Completion</li>
                        <li style="margin-bottom: 12px; display: flex; gap: 10px; color: var(--text-muted); font-size: 1rem;"><i class="fas fa-check-circle text-success" style="margin-top: 4px;"></i> Downloadable Study PDF</li>
                        <li style="margin-bottom: 12px; display: flex; gap: 10px; color: var(--text-muted); font-size: 1rem;"><i class="fas fa-check-circle text-success" style="margin-top: 4px;"></i> Legal Case Study Access</li>
                    </ul>
                    <a href="buy_course.php?id=<?php echo $course_id; ?>" class="btn btn-primary w-100 py-3 rounded-pill fw-800 shadow-sm" style="font-size: 1.15rem;">Buy Course ₹<?php echo number_format($course['price'], 0); ?></a>
                    <p style="text-align: center; font-size: 0.85rem; color: #94A3B8; margin-top: 15px;"><i class="fas fa-shield-alt"></i> Secure 256-bit payment encrypted.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
