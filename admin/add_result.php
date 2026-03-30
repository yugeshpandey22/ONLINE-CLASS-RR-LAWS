<?php include '../includes/admin_header.php'; ?>
<?php
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = trim($_POST['student_name']);
    $exam_title = trim($_POST['exam_title']);
    $result_year = trim($_POST['result_year']);
    $image_path = '';
    
    // Create uploads dir if not exists
    $upload_dir = '../uploads/results/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle Image Upload
    if (isset($_FILES['result_image']) && $_FILES['result_image']['error'] == UPLOAD_ERR_OK) {
        $img_name = time() . '_result_' . basename($_FILES['result_image']['name']);
        $target = $upload_dir . $img_name;
        if (move_uploaded_file($_FILES['result_image']['tmp_name'], $target)) {
            $image_path = 'uploads/results/' . $img_name;
        }
    }

    if($conn) {
        $stmt = $conn->prepare("INSERT INTO student_results (student_name, exam_title, result_year, image_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $student_name, $exam_title, $result_year, $image_path);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>Result uploaded successfully! 🏆</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
        }
    }
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Upload Student Result</h2>
        <a href="manage_results.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-arrow-left"></i> Manage All Results</a>
    </div>
</div>

<?php echo $msg; ?>

<div class="card shadow-sm border-0" style="border-radius: 20px;">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label class="form-label fw-bold small text-muted">Student Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="student_name" class="form-control form-control-lg border-2" required placeholder="Enter student name" style="border-radius: 12px;">
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold small text-muted">Examination / Course Title <span class="text-danger">*</span></label>
                    <input type="text" name="exam_title" class="form-control form-control-lg border-2" required placeholder="e.g. UPSC Law / LLB Sem 1" style="border-radius: 12px;">
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold small text-muted">Result Year <span class="text-danger">*</span></label>
                    <input type="text" name="result_year" class="form-control form-control-lg border-2" required placeholder="e.g. 2024" style="border-radius: 12px;">
                </div>

                <div class="col-md-12 mb-4">
                    <label class="form-label fw-bold small text-muted">Result Marksheet / Image <span class="text-danger">*</span></label>
                    <input type="file" name="result_image" class="form-control form-control-lg border-2" required accept="image/*" style="border-radius: 12px;">
                    <small class="text-muted">Upload a high-quality screenshot or photo of the result card.</small>
                </div>
            </div>
            
            <hr class="opacity-10 my-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm rounded-pill"><i class="bi bi-trophy"></i> Upload to Hall of Fame</button>
        </form>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
