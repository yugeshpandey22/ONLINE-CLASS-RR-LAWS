<?php include '../includes/admin_header.php'; ?>
<?php
// Handle Approval
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE enrollments SET payment_status = 'completed' WHERE id = $id");
    header("Location: manage_enrollments.php?success=1");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM enrollments WHERE id = $id");
    header("Location: manage_enrollments.php?deleted=1");
    exit;
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Course Enrollment Portal</h2>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2"><i class="bi bi-shield-lock"></i> Premium Access Control</span>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="ps-4">Student Info</th>
                        <th>Course</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("
                        SELECT e.*, u.name as user_name, u.email as user_email, c.title as course_title 
                        FROM enrollments e 
                        JOIN users u ON e.user_id = u.id 
                        JOIN courses c ON e.course_id = c.id 
                        ORDER BY e.enrolled_at DESC
                    ");
                    if ($res && $res->num_rows > 0):
                        while($row = $res->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['user_name']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($row['user_email']); ?></small>
                        </td>
                        <td><span class="fw-bold text-primary"><?php echo htmlspecialchars($row['course_title']); ?></span></td>
                        <td><code class="px-2 py-1 bg-light border rounded"><?php echo htmlspecialchars($row['transaction_id']); ?></code></td>
                        <td>
                            <?php if($row['payment_status'] == 'completed'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">Pending Verification</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?php echo date('M d, Y', strtotime($row['enrolled_at'])); ?></td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <?php if($row['payment_status'] != 'completed'): ?>
                                    <a href="?approve=<?php echo $row['id']; ?>" class="btn btn-sm btn-success rounded-3 px-3 shadow-sm" onclick="return confirm('Verify Payment & Enroll Student?')"><i class="bi bi-check-lg"></i> Approve</a>
                                <?php endif; ?>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm rounded-3 px-3 text-danger"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">No enrollment attempts found. Waiting for students to join!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
