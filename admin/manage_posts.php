<?php include '../includes/admin_header.php'; ?>
<?php
// db.php already included via header.php -> do NOT include again
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Manage Content</h2>
        <a href="add_blog.php" class="btn btn-sm btn-primary rounded-pill px-3"><i class="bi bi-file-earmark-text"></i> Add Blog</a>
        <a href="add_video.php" class="btn btn-sm btn-danger rounded-pill px-3"><i class="bi bi-play-btn"></i> Add Video</a>
    </div>
    <div class="admin-profile">
        <span class="d-none d-md-block text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
        <div class="profile-icon">
            <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Date Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($conn) {
                        $filter_type = isset($_GET['type']) ? $_GET['type'] : '';
                        
                        $sql = "SELECT * FROM posts";
                        if ($filter_type == 'blog' || $filter_type == 'lecture') {
                            $sql .= " WHERE post_type = '$filter_type'";
                        }
                        $sql .= " ORDER BY created_at DESC";
                        
                        $result = $conn->query($sql);
                        if($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="text-muted fw-bold">#<?php echo $row['id']; ?></td>
                        <td>
                            <?php if($row['image']): ?>
                                <img src="../<?php echo htmlspecialchars($row['image']); ?>" width="60" class="rounded-3 shadow-sm border object-fit-cover" style="height:40px;">
                            <?php else: ?>
                                <div class="bg-light rounded-3 text-center text-muted border d-flex align-items-center justify-content-center" style="width:60px; height:40px; font-size:10px;">N/A</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div class="text-muted small">ID: <?php echo $row['id']; ?></div>
                        </td>
                        <td>
                            <?php if($row['post_type'] == 'lecture'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3"><i class="bi bi-play-circle me-1"></i> Lecture</span>
                                <?php if(!empty($row['pdf_path'])): ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2" title="PDF Notes Attached"><i class="bi bi-file-earmark-pdf"></i> PDF</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3"><i class="bi bi-file-text me-1"></i> Blog</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm text-primary rounded-3"><i class="bi bi-pencil-square"></i></a>
                                <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm text-danger rounded-3" onclick="return confirm('Are you sure you want to delete this content?');"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                            endwhile;
                        } else {
                            echo '<tr><td colspan="6" class="text-center text-muted py-4">No content found. <a href="add_blog.php">Add your first blog!</a> or <a href="add_video.php">Upload a video!</a></td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center text-danger py-4">Database not connected. Please start MySQL from XAMPP Control Panel.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
