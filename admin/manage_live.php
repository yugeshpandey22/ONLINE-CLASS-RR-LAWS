<?php include '../includes/admin_header.php'; ?>
<?php
$msg = "";
// Handle Addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_live'])) {
    $title = trim($_POST['title']);
    $url = trim($_POST['meeting_url']);
    $time = $_POST['scheduled_at'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    $stmt = $conn->prepare("INSERT INTO live_classes (title, meeting_url, scheduled_at, is_public) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $url, $time, $is_public);
    if($stmt->execute()) $msg = "<div class='alert alert-success'>Live class scheduled! 🔴</div>";
}

// Handle Access Toggle
if (isset($_GET['access']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $access = intval($_GET['access']);
    $conn->query("UPDATE live_classes SET is_public = $access WHERE id = $id");
    header("Location: manage_live.php");
    exit;
}

// Handle Status Toggle
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];
    $conn->query("UPDATE live_classes SET status = '$status' WHERE id = $id");
    header("Location: manage_live.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM live_classes WHERE id = $id");
    header("Location: manage_live.php");
    exit;
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h2 id="page-title" class="m-0">Live Class <span>Scheduler</span></h2>
        <span class="badge bg-danger pulse-dot border-0 rounded-pill px-3 py-2"><i class="bi bi-broadcast"></i> Control Hub</span>
    </div>
</div>

<?php echo $msg; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4">
                <h4 class="mb-4 fw-bold">Schedule Class</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Session Topic</label>
                        <input type="text" name="title" class="form-control border-2" required placeholder="Ex: IPC Section 302 Deep Dive">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Meeting Link (Zoom/Google/YouTube)</label>
                        <input type="url" name="meeting_url" class="form-control border-2" required placeholder="https://meet.google.com/xxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Date & Time</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control border-2" required>
                    </div>
                    <div class="mb-4">
                        <div class="form-check form-switch p-0 d-flex align-items-center gap-3">
                            <label class="form-check-label fw-bold small">Open for Everyone? (Public)</label>
                            <input class="form-check-input ms-0" type="checkbox" name="is_public" style="width: 40px; height: 20px;">
                        </div>
                    </div>
                    <button type="submit" name="add_live" class="btn btn-danger w-100 py-2 rounded-pill shadow-sm fw-bold">Schedule Now 🚀</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">Scheduled Sessions</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4 py-3">Topic / Info</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM live_classes ORDER BY scheduled_at DESC");
                            if ($res && $res->num_rows > 0):
                                while($row = $res->fetch_assoc()):
                                    $s_color = ['upcoming' => 'bg-info-subtle text-info', 'live' => 'bg-danger-subtle text-danger', 'ended' => 'bg-secondary-subtle text-secondary'][$row['status']];
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <small class="text-primary"><?php echo $row['meeting_url']; ?></small>
                                </td>
                                <td>
                                    <div class="small fw-bold"><?php echo date('M d, Y', strtotime($row['scheduled_at'])); ?></div>
                                    <div class="text-muted small"><?php echo date('h:i A', strtotime($row['scheduled_at'])); ?></div>
                                </td>
                                <td>
                                    <span class="badge <?php echo $s_color; ?> border rounded-pill px-3"><?php echo strtoupper($row['status']); ?></span>
                                    <div class="mt-1 small fw-bold <?php echo $row['is_public'] ? 'text-success' : 'text-primary'; ?>">
                                        <i class="bi <?php echo $row['is_public'] ? 'bi-unlock' : 'bi-lock'; ?>"></i> <?php echo $row['is_public'] ? 'Public (Free)' : 'Paid Only'; ?>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-white border shadow-sm rounded-3 dropdown-toggle" data-bs-toggle="dropdown">Phase</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="?status=live&id=<?php echo $row['id']; ?>">Set LIVE NOW 🔴</a></li>
                                            <li><a class="dropdown-item" href="?status=ended&id=<?php echo $row['id']; ?>">Mark ENDED 🏁</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="?access=<?php echo $row['is_public']?0:1; ?>&id=<?php echo $row['id']; ?>">Make <?php echo $row['is_public']?'PAID ONLY':'PUBLIC (FREE)'; ?></a></li>
                                            <li><a class="dropdown-item text-danger" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete session?')">Delete Permanent</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No live classes scheduled. Start teaching live!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pulse-dot i { animation: pulse-live 1.5s infinite; }
@keyframes pulse-live { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
</style>

<?php include '../includes/admin_footer.php'; ?>
