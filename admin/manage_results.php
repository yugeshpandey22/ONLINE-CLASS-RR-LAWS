<?php include '../includes/admin_header.php'; ?>
<?php
// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM student_results WHERE id = $id");
    header("Location: manage_results.php?deleted=1");
    exit;
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Hall of Fame - Manage Student Results</h2>
        <a href="add_result.php" class="btn btn-sm btn-primary rounded-pill px-3"><i class="bi bi-plus-lg"></i> Add New Result</a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="ps-4">Student Info</th>
                        <th>Exam / Course</th>
                        <th>Year</th>
                        <th>Result Card</th>
                        <th>Date Uploaded</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT * FROM student_results ORDER BY created_at DESC");
                    if ($res && $res->num_rows > 0):
                        while($row = $res->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['student_name']); ?></div>
                        </td>
                        <td><span class="fw-bold text-primary"><?php echo htmlspecialchars($row['exam_title']); ?></span></td>
                        <td><span class="badge bg-primary text-white p-2 rounded-lg"><?php echo htmlspecialchars($row['result_year']); ?></span></td>
                        <td>
                            <?php if(!empty($row['image_path'])): ?>
                                <a href="../<?php echo $row['image_path']; ?>" target="_blank" class="text-primary font-weight-bold" style="font-size: 0.9rem;"><i class="bi bi-image"></i> View Marksheet</a>
                            <?php else: ?>
                                <span class="text-muted small">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td class="text-end pe-4">
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm rounded-3 px-3 text-danger" onclick="return confirm('Delete this result permanently?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">No student results uploaded yet. Use the button to post new results!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
