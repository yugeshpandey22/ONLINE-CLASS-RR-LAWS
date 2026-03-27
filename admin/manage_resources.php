<?php include '../includes/admin_header.php'; ?>
<?php
// db.php already included via header.php
$msg = "";
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$edit_data = null;

if ($edit_id > 0) {
    $res = $conn->query("SELECT * FROM posts WHERE id = $edit_id AND post_type = 'pdf'");
    $edit_data = $res->fetch_assoc();
}

// Handle Upload / Update
if (isset($_POST['save_resource'])) {
    $title = trim($_POST['title']);
    $id = intval($_POST['id'] ?? 0);
    $pdf_path = "";
    
    // Maintain old path if not uploading new
    if ($id > 0) {
        $res = $conn->query("SELECT pdf_path FROM posts WHERE id = $id");
        $row = $res->fetch_assoc();
        $pdf_path = $row['pdf_path'];
    }

    $upload_dir = "../uploads/pdfs/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == UPLOAD_ERR_OK) {
        $file_name = time() . "_" . basename($_FILES["pdf_file"]["name"]);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_file)) {
            $pdf_path = "uploads/pdfs/" . $file_name;
        }
    }

    if (!empty($title) && !empty($pdf_path)) {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, pdf_path = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $pdf_path, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO posts (title, pdf_path, post_type, content) VALUES (?, ?, 'pdf', 'Independent PDF Study Material')");
            $stmt->bind_param("ss", $title, $pdf_path);
        }

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success mt-3'>PDF Note saved successfully!</div>";
            if ($id > 0) {
                // Clear edit mode after save
                header("Location: manage_resources.php?success=1");
                exit;
            }
        } else {
            $msg = "<div class='alert alert-danger mt-3'>Database Error: " . $conn->error . "</div>";
        }
    } else {
        $msg = "<div class='alert alert-warning mt-3'>Title and PDF File are required!</div>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT pdf_path FROM posts WHERE id = $id AND post_type = 'pdf'");
    if($row = $res->fetch_assoc()) {
        $filePath = '../' . $row['pdf_path'];
        if(file_exists($filePath)) unlink($filePath);
    }
    $conn->query("DELETE FROM posts WHERE id = $id AND post_type = 'pdf'");
    header("Location: manage_resources.php?deleted=1");
    exit;
}

if (isset($_GET['success'])) $msg = "<div class='alert alert-success mt-3'>PDF Note updated successfully!</div>";
if (isset($_GET['deleted'])) $msg = "<div class='alert alert-info mt-3'>PDF Note deleted!</div>";

?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Manage <span>PDF Notes</span></h2>
    </div>
</div>

<?php echo $msg; ?>

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4">
                <h4 class="mb-4 fw-bold"><?php echo $edit_id > 0 ? 'Edit PDF Note' : 'Upload New PDF Note'; ?></h4>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Title (e.g. IPC Section Notes)</label>
                        <input type="text" name="title" class="form-control form-control-lg border-2" value="<?php echo htmlspecialchars($edit_data['title'] ?? ''); ?>" required placeholder="Enter PDF title">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Select PDF File</label>
                        <input type="file" name="pdf_file" class="form-control border-2" accept=".pdf" <?php echo $edit_id > 0 ? '' : 'required'; ?>>
                        <?php if ($edit_id > 0 && !empty($edit_data['pdf_path'])): ?>
                            <small class="text-primary d-block mt-2"><i class="bi bi-file-pdf"></i> Current: <?php echo basename($edit_data['pdf_path']); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="save_resource" class="btn btn-primary btn-lg rounded-pill px-4 flex-grow-1 shadow-sm">
                            <i class="bi bi-cloud-upload"></i> <?php echo $edit_id > 0 ? 'Update PDF' : 'Save PDF'; ?>
                        </button>
                        <?php if($edit_id > 0): ?>
                            <a href="manage_resources.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">Library</h4>
                    <span class="badge bg-light text-dark border rounded-pill px-3">Only Independent PDFs</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4 py-3">Note Details</th>
                                <th class="py-3 text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM posts WHERE post_type = 'pdf' ORDER BY created_at DESC");
                            if ($res && $res->num_rows > 0) {
                                while($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-3 p-2" style="font-size: 1.5rem;">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['title']); ?></div>
                                            <small class="text-muted">Added: <?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="../<?php echo $row['pdf_path']; ?>" target="_blank" class="btn btn-sm btn-white border shadow-sm rounded-3 px-3" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="manage_resources.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm rounded-3 px-3 text-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <a href="manage_resources.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm rounded-3 px-3 text-danger" onclick="return confirm('Delete this PDF?');" title="Delete"><i class="bi bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            } else {
                                echo '<tr><td colspan="2" class="text-center py-5 text-muted">No independent PDF notes found. Upload your first one!</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
