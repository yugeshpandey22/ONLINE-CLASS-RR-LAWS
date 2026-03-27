<?php include '../includes/admin_header.php'; ?>
<?php
// db.php already included via header.php -> do NOT include again
$msg = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid Request. Content ID is missing.</div>";
    include 'includes/footer.php';
    exit;
}

$id = intval($_GET['id']);

// Fetch existing record
if ($conn) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    
    if (!$post) {
        echo "<div class='alert alert-danger'>No content found with the given ID.</div>";
        include 'includes/footer.php';
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>Database not connected!</div>";
    include 'includes/footer.php';
    exit;
}

$is_video = ($post['post_type'] == 'lecture');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $video_url = trim($_POST['video_url']);
    $image = $post['image']; // retain old image default
    $pdf_path = $post['pdf_path']; // retain old pdf path
    
    // Create folders if not exist
    $upload_dir = '../uploads/';
    $video_dir  = $upload_dir . 'videos/';
    $pdf_dir    = $upload_dir . 'pdfs/';

    foreach ([$upload_dir, $video_dir, $pdf_dir] as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0777, true);
    }

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $img_name = time() . '_' . basename($_FILES['image']['name']);
        $target = $upload_dir . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = 'uploads/' . $img_name; // new image
        }
    }

    // Handle Video Upload
    if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == UPLOAD_ERR_OK) {
        $vid_name = time() . '_' . basename($_FILES['video_file']['name']);
        $target_vid = $video_dir . $vid_name;
        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target_vid)) {
            $video_url = 'uploads/videos/' . $vid_name; // new video
        }
    }

    // Handle PDF Upload
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == UPLOAD_ERR_OK) {
        $pdf_name = time() . '_' . basename($_FILES['pdf_file']['name']);
        $target_pdf = $pdf_dir . $pdf_name;
        if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target_pdf)) {
            $pdf_path = 'uploads/pdfs/' . $pdf_name;
        }
    }

    if($conn) {
        $course_id = !empty($_POST['course_id']) ? intval($_POST['course_id']) : null;
        $update_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ?, video_url = ?, pdf_path = ?, course_id = ? WHERE id = ?");
        $update_stmt->bind_param("sssssii", $title, $content, $image, $video_url, $pdf_path, $course_id, $id);
        
        if ($update_stmt->execute()) {
            $msg = "<div class='alert alert-success'>Content updated successfully!</div>";
            // Refresh post data
            $post['title'] = $title;
            $post['content'] = $content;
            $post['image'] = $image;
            $post['video_url'] = $video_url;
            $post['pdf_path'] = $pdf_path;
            $post['course_id'] = $course_id;
        } else {
            $msg = "<div class='alert alert-danger'>Database error: ".$conn->error."</div>";
        }
    }
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Edit <?php echo $is_video ? 'Video Lecture' : 'Blog Post'; ?></h2>
        <a href="manage_posts.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-arrow-left"></i> Back to List</a>
    </div>
</div>

<?php echo $msg; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="form-label fw-bold"><i class="bi bi-type-h1"></i> <?php echo $is_video ? 'Video Title' : 'Blog Title'; ?> <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold"><i class="bi bi-justify-left"></i> Description / Content <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control" rows="8" required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-image"></i> Update Featured preview</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted d-block mt-1">Leave empty to keep current image.</small>
                    <?php if(!empty($post['image'])): ?>
                        <div class="mt-2">
                            <img src="../<?php echo htmlspecialchars($post['image']); ?>" width="80" class="rounded border">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold <?php echo $is_video ? 'text-danger' : ''; ?>"><i class="bi bi-upload"></i> <?php echo $is_video ? 'Update Video Lecture (MP4)' : 'Update Short Video Clip'; ?></label>
                    <input type="file" name="video_file" class="form-control" accept="video/*">
                    <small class="text-muted d-block mt-1">Leave empty to keep current video.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-bold text-danger"><i class="bi bi-youtube"></i> Youtube / External Link</label>
                    <input type="text" name="video_url" class="form-control" value="<?php echo htmlspecialchars($post['video_url'] ?? ''); ?>" <?php echo !$is_video ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-bold text-success"><i class="bi bi-mortarboard"></i> Course Category</label>
                    <select name="course_id" class="form-select" <?php echo !$is_video ? 'disabled' : ''; ?>>
                        <option value="">-- No Course / Public --</option>
                        <?php
                        $courses = $conn->query("SELECT id, title FROM courses WHERE active = 1");
                        while($c = $courses->fetch_assoc()):
                        ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($post['course_id'] == $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['title']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-primary"><i class="bi bi-file-earmark-pdf"></i> Update Study PDF (Notes)</label>
                    <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                    <small class="text-muted d-block mt-1">Leave empty to keep current PDF.</small>
                    <?php if(!empty($post['pdf_path'])): ?>
                        <div class="mt-2 text-primary">
                            <i class="bi bi-file-pdf"></i> Current: <?php echo basename($post['pdf_path']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <hr>
            <button type="submit" class="btn <?php echo $is_video ? 'btn-danger' : 'btn-primary'; ?> btn-lg px-5 shadow-sm mt-2"><i class="bi bi-save"></i> Save Changes</button>
        </form>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
