<?php include '../includes/admin_header.php'; ?>
<?php
$msg = "";
$course_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$course_data = null;

if ($course_id > 0) {
    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $course_data = $stmt->get_result()->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_course'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $active = isset($_POST['active']) ? 1 : 0;
    $image = $course_data['image'] ?? ""; // default old image

    if ($conn) {
        // Image Upload
        if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == UPLOAD_ERR_OK) {
            $img_name = time() . '_' . basename($_FILES['course_image']['name']);
            $target = '../uploads/' . $img_name;
            if (move_uploaded_file($_FILES['course_image']['tmp_name'], $target)) {
                $image = 'uploads/' . $img_name;
            }
        }

        if ($course_id > 0) {
            $stmt = $conn->prepare("UPDATE courses SET title=?, description=?, price=?, image=?, active=? WHERE id=?");
            $stmt->bind_param("ssdsii", $title, $description, $price, $image, $active, $course_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO courses (title, description, price, image, active) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsi", $title, $description, $price, $image, $active);
        }

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success mt-3'>Course saved successfully!</div>";
            if ($course_id > 0) {
                header("Location: manage_courses.php");
                exit;
            }
        }
    }
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0"><?php echo $course_id ? "Edit Content" : "Create Content"; ?> <span>Course Details</span></h2>
        <a href="manage_courses.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
</div>

<?php echo $msg; ?>

<div class="card border-0 shadow-sm" style="border-radius: 24px;">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Course Title (Full Name)</label>
                        <input type="text" name="title" class="form-control form-control-lg border-2" required value="<?php echo htmlspecialchars($course_data['title'] ?? ''); ?>" placeholder="Mastering CrPC & IPC Strategy">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Course Price (INR)</label>
                        <div class="input-group input-group-lg border-2">
                            <span class="input-group-text bg-light border-end-0">₹</span>
                            <input type="number" name="price" step="0.01" class="form-control border-start-0" required value="<?php echo $course_data['price'] ?? '0.00'; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small">Description / Course Syllabus Details</label>
                <textarea name="description" class="form-control border-2" rows="10" required><?php echo htmlspecialchars($course_data['description'] ?? ''); ?></textarea>
            </div>

            <div class="row align-items-center">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted small">Featured Banner (Premium Quality)</label>
                    <input type="file" name="course_image" class="form-control border-2" accept="image/*">
                    <?php if(!empty($course_data['image'])): ?>
                        <img src="../<?php echo $course_data['image']; ?>" class="mt-2 rounded shadow-sm border" width="100">
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="form-check form-switch p-0 mt-3 d-flex align-items-center gap-3">
                        <label class="form-check-label fw-bold" for="activeSwitch">Active / Published</label>
                        <input class="form-check-input ms-0" type="checkbox" id="activeSwitch" name="active" style="width: 50px; height: 26px;" <?php echo (!isset($course_data['active']) || $course_data['active']) ? 'checked' : ''; ?>>
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-5">
            <div class="d-flex gap-3 mt-4">
                <button type="submit" name="save_course" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                    <i class="bi bi-cloud-upload"></i> <?php echo $course_id ? "Update Assets" : "Publish Course"; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
