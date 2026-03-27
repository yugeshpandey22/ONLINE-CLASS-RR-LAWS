<?php include '../includes/admin_header.php'; ?>
<?php
// db.php already included via header.php -> do NOT include again
$blogCount = 0;
$lectureCount = 0;
$pdfCount = 0;
$recentActivity = [];

if($conn) {
    // 1. Get Blog Count
    $res = $conn->query("SELECT COUNT(*) as count FROM posts WHERE post_type = 'blog'");
    if($res) $blogCount = $res->fetch_assoc()['count'];
    
    // 2. Get Lecture Count
    $res = $conn->query("SELECT COUNT(*) as count FROM posts WHERE post_type = 'lecture'");
    if($res) $lectureCount = $res->fetch_assoc()['count'];

    // 3. Get PDF Count
    $res = $conn->query("SELECT COUNT(*) as count FROM posts WHERE post_type = 'pdf'");
    if($res) $pdfCount = $res->fetch_assoc()['count'];

    // 4. Get Recent Activity
    $res = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
    if($res) {
        while($row = $res->fetch_assoc()) {
            $recentActivity[] = $row;
        }
    }
}
?>
<div class="page-header">
    <h2 id="page-title"><span><i class="bi bi-grid-1x2-fill"></i></span> Dashboard Overview</h2>
    <div class="admin-profile">
        <span class="d-none d-md-block text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
        <div class="profile-icon">
            <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Blog Stats Card -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
            <i class="bi bi-file-earmark-text"></i>
            <h5 class="fw-normal opacity-75 mb-1">Total Blog Posts</h5>
            <h2 class="display-5 fw-bold mb-4"><?php echo $blogCount; ?></h2>
            <a href="manage_posts.php?type=blog" class="btn-modern">Manage Blogs <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    <!-- Lecture Stats Card -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);">
            <i class="bi bi-play-btn"></i>
            <h5 class="fw-normal opacity-75 mb-1">Video Lectures</h5>
            <h2 class="display-5 fw-bold mb-4"><?php echo $lectureCount; ?></h2>
            <a href="manage_posts.php?type=lecture" class="btn-modern">Manage Lectures <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="col-md-12 col-lg-3">
        <div class="stat-card h-100" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 25px;">
            <i class="bi bi-lightning-charge"></i>
            <h5 class="fw-normal opacity-75 mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="add_blog.php" class="btn-modern bg-white text-dark py-2"><i class="bi bi-plus-circle"></i> New Blog</a>
                <a href="add_video.php" class="btn-modern bg-white text-dark py-2"><i class="bi bi-upload"></i> Upload Lecture</a>
                <a href="manage_resources.php" class="btn-modern bg-white text-dark py-2"><i class="bi bi-file-earmark-pdf"></i> Upload PDF</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Table -->
<div class="card border-0 shadow-sm" style="border-radius: 24px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 fw-bold"><i class="bi bi-clock-history text-primary"></i> Recent Activity</h4>
            <a href="manage_posts.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All History</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th class="text-end">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($recentActivity) > 0): ?>
                        <?php foreach($recentActivity as $post): ?>
                        <tr>
                            <td>
                                <?php if($post['image']): ?>
                                    <img src="../<?php echo htmlspecialchars($post['image']); ?>" width="50" height="35" class="rounded object-fit-cover shadow-sm">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:35px;"><i class="bi bi-image text-muted"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($post['title']); ?></div>
                                <small class="text-muted">ID: #<?php echo $post['id']; ?></small>
                            </td>
                            <td>
                                <?php if($post['post_type'] == 'lecture'): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Lecture</span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">Blog</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-muted">
                                <?php echo date('M d, H:i', strtotime($post['created_at'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No recent activity found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
