<?php include '../includes/admin_header.php'; ?>
<?php
// Handle simple toggle
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $conn->query("UPDATE courses SET active = 1 - active WHERE id = $id");
    header("Location: manage_courses.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Check if course has lessons attached
    $check = $conn->query("SELECT COUNT(*) as count FROM posts WHERE course_id = $id");
    if ($check->fetch_assoc()['count'] == 0) {
        $conn->query("DELETE FROM courses WHERE id = $id");
        $msg = "<div class='alert alert-success'>Course deleted successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Cannot delete course with attached lessons!</div>";
    }
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Manage <span>Premium Courses</span></h2>
        <a href="add_course.php" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-plus-circle"></i> Create New Course</a>
    </div>
</div>

<?php if(isset($msg)) echo $msg; ?>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="ps-4">Course Info</th>
                        <th>Price</th>
                        <th>Students</th>
                        <th>Lessons</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("
                        SELECT c.*, 
                        (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id AND payment_status='completed') as student_count,
                        (SELECT COUNT(*) FROM posts p WHERE p.course_id = c.id) as lesson_count
                        FROM courses c ORDER BY created_at DESC
                    ");
                    if($res && $res->num_rows > 0):
                        while($row = $res->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <?php if($row['image']): ?>
                                    <img src="../<?php echo $row['image']; ?>" width="60" height="40" class="rounded object-fit-cover shadow-sm">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:60px; height:40px;"><i class="bi bi-mortarboard text-muted"></i></div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <small class="text-muted">Launch: <?php echo date('M Y', strtotime($row['created_at'])); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="fw-bold text-primary">₹<?php echo number_format($row['price'], 2); ?></span></td>
                        <td><span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3"><?php echo $row['student_count']; ?> Enrolled</span></td>
                        <td><span class="badge bg-light text-dark border rounded-pill px-3"><?php echo $row['lesson_count']; ?> Lessons</span></td>
                        <td>
                            <a href="?toggle=<?php echo $row['id']; ?>" class="badge <?php echo $row['active'] ? 'bg-success' : 'bg-secondary'; ?> text-white text-decoration-none rounded-pill px-3">
                                <?php echo $row['active'] ? 'Active' : 'Draft'; ?>
                            </a>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="add_course.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm rounded-3 px-3 text-primary"><i class="bi bi-pencil-square"></i></a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm rounded-3 px-3 text-danger" onclick="return confirm('Delete this course? Only possible if no lessons are attached.')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">No courses launched yet. <a href="add_course.php">Create your first one!</a></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
