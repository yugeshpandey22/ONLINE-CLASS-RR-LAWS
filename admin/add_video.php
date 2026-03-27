<?php include '../includes/admin_header.php'; ?>
<?php
// Force large upload limits at runtime
@ini_set('upload_max_filesize', '512M');
@ini_set('post_max_size', '512M');
@ini_set('max_execution_time', '600');
@ini_set('max_input_time', '600');

// db.php already included via admin_header.php -> do NOT include again
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title     = trim($_POST['title']     ?? '');
    $content   = trim($_POST['content']   ?? '');
    $video_url = trim($_POST['video_url'] ?? '');
    $image      = '';
    $video_path = '';
    $pdf_path = '';
    $upload_error = '';
    
    // Validate required fields
    if (empty($title) || empty($content)) {
        $msg = "<div class='alert alert-warning'><i class='bi bi-exclamation-triangle'></i> Title aur Description required hain!</div>";
    } else {
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
                $image = 'uploads/' . $img_name;
            }
        }

        // Handle Video Upload
        if (isset($_FILES['video_file'])) {
            $vf_err = $_FILES['video_file']['error'];
            if ($vf_err == UPLOAD_ERR_OK) {
                $vid_name = time() . '_' . basename($_FILES['video_file']['name']);
                $target_vid = $video_dir . $vid_name;
                if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target_vid)) {
                    $video_path = 'uploads/videos/' . $vid_name;
                    $video_url = $video_path;
                } else {
                    $upload_error = "<div class='alert alert-danger'>Video file move nahi hua.</div>";
                }
            } elseif ($vf_err != UPLOAD_ERR_NO_FILE) {
                $upload_error = "<div class='alert alert-warning'>Video upload error code: " . $vf_err . "</div>";
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

        if (!empty($upload_error)) {
            $msg = $upload_error;
        } else {
            if ($conn) {
                $course_id = !empty($_POST['course_id']) ? intval($_POST['course_id']) : null;
                $stmt = $conn->prepare("INSERT INTO posts (title, content, image, video_url, post_type, pdf_path, course_id) VALUES (?, ?, ?, ?, 'lecture', ?, ?)");
                $stmt->bind_param("sssssi", $title, $content, $image, $video_url, $pdf_path, $course_id);
                if ($stmt->execute()) {
                    $msg = "<div class='alert alert-success'><i class='bi bi-check-circle'></i> Video Lecture successfully publish ho gaya!</div>";
                } else {
                    $msg = "<div class='alert alert-danger'><i class='bi bi-x-circle'></i> Database error: ".$conn->error."</div>";
                }
            }
        }
    }
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Upload Video Lecture</h2>
        <a href="manage_posts.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-arrow-left"></i> Back to List</a>
    </div>
</div>

<?php echo $msg; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="form-label fw-bold"><i class="bi bi-play-btn"></i> Video Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg" required placeholder="Enter Video Lecture Title">
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold"><i class="bi bi-justify-left"></i> Lecture Description <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control" rows="4" required placeholder="Describe the video lecture..."></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-danger"><i class="bi bi-upload"></i> Upload Video Lecture (MP4)</label>
                    <input type="file" name="video_file" class="form-control" accept="video/*">
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-primary"><i class="bi bi-file-earmark-pdf"></i> Upload Study PDF (Notes)</label>
                    <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                    <small class="text-muted">Associating study material PDF with this lecture.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-youtube text-danger"></i> Or Youtube / External Link</label>
                    <input type="text" name="video_url" class="form-control" placeholder="Embed link here...">
                </div>
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-image"></i> Thumbnail (Optional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-bold text-success"><i class="bi bi-mortarboard"></i> Assign to Course</label>
                    <select name="course_id" class="form-select">
                        <option value="">-- Free / No Course --</option>
                        <?php
                        $courses = $conn->query("SELECT id, title FROM courses WHERE active = 1");
                        while($c = $courses->fetch_assoc()):
                        ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['title']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <hr>
            <button type="submit" class="btn btn-danger btn-lg px-5 shadow-sm mt-2"><i class="bi bi-cloud-upload"></i> Publish Video & PDF</button>
        </form>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
