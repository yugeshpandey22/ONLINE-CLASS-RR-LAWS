<?php 
session_start();
include '../includes/header.php'; 
include '../includes/db.php';

$post = null;
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(isset($conn) && $conn !== null) {
    try {
        $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
    } catch(Exception $e) {}
}

if(!$post) {
    echo "<div class='container' style='padding: 100px 0; text-align: center;'><h2>Content not found!</h2></div>";
    include '../includes/footer.php';
    exit;
}
?>

<main>
    <section class="page-header" style="padding: 100px 0 40px;">
        <div class="container">
            <h1 style="font-size: 2.5rem; max-width: 900px; margin: 0 auto;"><?php echo htmlspecialchars($post['title']); ?></h1>
            <p style="margin-top:20px; color: #cbd5e1;">Posted on: <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
        </div>
    </section>

    <section class="single-content container" style="max-width: 900px; padding: 60px 20px;">
        
        <?php if($post['post_type'] == 'lecture'): ?>
            <div class="lecture-badge" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px 20px; border-radius: 50px; font-weight: 700; font-size: 0.9rem; margin-bottom: 25px; border: 1px solid rgba(239, 68, 68, 0.2);">
                <span style="display: block; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 10px #ef4444; animation: pulse 2s infinite;"></span> Full Classroom Session (2h+)
            </div>
        <?php elseif(!empty($post['video_url'])): ?>
            <div class="blog-video-badge" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 10px 20px; border-radius: 50px; font-weight: 700; font-size: 0.9rem; margin-bottom: 25px; border: 1px solid rgba(59, 130, 246, 0.2);">
                <i class="fas fa-play-circle"></i> Quick Insight Clip (4-5 min)
            </div>
        <?php endif; ?>

        <?php if(!empty($post['video_url'])): ?>
            <?php 
                $is_lecture = ($post['post_type'] == 'lecture'); 
                $theater_style = $is_lecture ? 'background: #000; padding: 15px; box-shadow: 0 40px 100px rgba(0,0,0,0.4); max-width: 1000px; margin-left: -50px; width: calc(100% + 100px);' : 'max-width: 100%; box-shadow: var(--shadow-lg);';
            ?>
            <?php if(strpos($post['video_url'], 'uploads/videos/') === 0): ?>
                <div class="video-container" style="border-radius: 12px; margin-bottom: 40px; border: 2px solid var(--border); overflow: hidden; <?php echo $theater_style; ?>">
                    <video controls style="width: 100%; display: block;" <?php echo !empty($post['image']) ? 'poster="../'.htmlspecialchars($post['image']).'"' : ''; ?>>
                        <source src="../<?php echo htmlspecialchars($post['video_url']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php else: ?>
                <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; margin-bottom: 40px; border: 2px solid var(--border); <?php echo $theater_style; ?>">
                    <iframe src="<?php echo htmlspecialchars($post['video_url']); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0;" allowfullscreen></iframe>
                </div>
            <?php endif; ?>
        <?php elseif(!empty($post['image'])): ?>
            <img src="../<?php echo htmlspecialchars($post['image']); ?>" style="width:100%; border-radius:12px; box-shadow: var(--shadow-lg); margin-bottom: 40px;">
        <?php endif; ?>

        <div class="content-body" style="font-size: 1.15rem; line-height: 1.8; color: var(--text-main);">
            <?php echo nl2br(htmlspecialchars($post['content'] ?? '')); ?>
        </div>

        <?php if(!empty($post['pdf_path'])): ?>
            <div class="pdf-download-card" style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 16px; padding: 25px; margin: 40px 0; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: #ef4444; color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2); flex-shrink: 0;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem; color: #1e293b; font-weight: 700;">Study Material Attached</h4>
                        <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Download the detailed PDF notes for this lecture.</p>
                    </div>
                </div>
                <a href="../<?php echo htmlspecialchars($post['pdf_path']); ?>" target="_blank" class="btn btn-primary" style="padding: 10px 25px; border-radius: 50px; font-weight: 700; background: #3b82f6; border-color: #3b82f6; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2); text-decoration: none; color: white; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-download"></i> View PDF Notes
                </a>
            </div>
        <?php endif; ?>
        
        
        <div style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 30px;">
            <?php if($post['post_type'] == 'lecture'): ?>
                <a href="lectures.php" class="btn btn-outline" style="color:var(--dark-bg)!important; border-color:var(--dark-bg);"><i class="fas fa-arrow-left"></i> Back to Lectures</a>
            <?php else: ?>
                <a href="blog.php" class="btn btn-outline" style="color:var(--dark-bg)!important; border-color:var(--dark-bg);"><i class="fas fa-arrow-left"></i> Back to Insights</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
