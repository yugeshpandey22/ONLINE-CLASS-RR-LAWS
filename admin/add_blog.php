<?php include '../includes/admin_header.php'; ?>
<?php
// db.php already included via header.php -> do NOT include again
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = '';
    $video_url = '';
    
    // Create uploads dir if not exists
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Create videos dir if not exists
    $video_dir = '../uploads/videos/';
    if (!is_dir($video_dir)) {
        mkdir($video_dir, 0777, true);
    }

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $img_name = time() . '_' . basename($_FILES['image']['name']);
        $target = $upload_dir . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = 'uploads/' . $img_name;
        }
    }

    // Handle Short Video Upload
    if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == UPLOAD_ERR_OK) {
        $vid_name = time() . '_short_' . basename($_FILES['video_file']['name']);
        $target_vid = $video_dir . $vid_name;
        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target_vid)) {
            $video_url = 'uploads/videos/' . $vid_name;
        }
    }

    if($conn) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, image, video_url, post_type) VALUES (?, ?, ?, ?, 'blog')");
        $stmt->bind_param("ssss", $title, $content, $image, $video_url);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>Blog published successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Database error: ".$conn->error."</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>No database connection!</div>";
    }
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Create Blog Post</h2>
        <a href="manage_posts.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-arrow-left"></i> Back to List</a>
    </div>
    <div class="admin-profile">
        <span class="d-none d-md-block text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
        <div class="profile-icon">
            <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
        </div>
    </div>
</div>

<?php echo $msg; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="form-label fw-bold"><i class="bi bi-type-h1"></i> Blog Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg" required placeholder="Enter Blog Title">
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold"><i class="bi bi-justify-left"></i> Content / Article <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control" rows="8" required placeholder="Write your blog content here..."></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-image"></i> Featured Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted d-block mt-1">Upload a cover image for your blog post (JPG, PNG).</small>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-camera-video"></i> Short Video Clip (Optional)</label>
                    <input type="file" name="video_file" class="form-control" accept="video/*">
                    <small class="text-muted d-block mt-1">Upload a short clip for this blog (MP4).</small>
                </div>
            </div>
            
            <hr>
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm mt-2"><i class="bi bi-cloud-upload"></i> Publish Blog</button>
        </form>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
