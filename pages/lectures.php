<?php 
include '../includes/header.php'; 
include '../includes/db.php';
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Video Lectures</h1>
            <p>Comprehensive video lectures and live class recordings.</p>
        </div>
    </section>

    <section class="blog-listing container">
        <div class="blog-grid">
            <?php
            // Fetch dynamically from database securely
            $posts = [];
            if(isset($conn) && $conn !== null) {
                try {
                    $result = $conn->query("SELECT * FROM posts WHERE post_type = 'lecture' ORDER BY created_at DESC");
                    while($row = $result->fetch_assoc()) {
                        $posts[] = $row;
                    }
                } catch (Exception $e) {
                    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
            
            if(count($posts) > 0):
                foreach($posts as $post):
                    $img_src = !empty($post['image']) ? '../' . $post['image'] : '../assets/images/post2.png';
            ?>
             <article class="blog-card">
                <div class="blog-img" style="position: relative;">
                    <img src="<?php echo htmlspecialchars($img_src); ?>" alt="Thumbnail">
                    <span class="badge bg-danger" style="position: absolute; top:10px; right:10px; padding: 4px 10px; border-radius: 4px; color: white;"><i class="fas fa-play-circle"></i> Full Class (2h+)</span>
                </div>
                <div class="blog-content">
                    <span class="blog-category" style="color: #ef4444;"><i class="fas fa-video"></i> Recorded Class</span>
                    <h3><a href="single.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <p><?php echo substr(htmlspecialchars(strip_tags($post['content'])), 0, 100); ?>...</p>
                    <div style="display: flex; align-items: center; gap: 15px; margin-top: 15px;">
                        <a href="single.php?id=<?php echo $post['id']; ?>" class="read-more" style="color: #ef4444; text-decoration: none; font-weight: 700;">Watch Video &rarr;</a>
                        <?php if(!empty($post['pdf_path'])): ?>
                            <a href="../<?php echo $post['pdf_path']; ?>" target="_blank" style="color: #3b82f6; font-size: 0.9rem; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-file-pdf"></i> Study Notes
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php 
                endforeach;
            else:
            ?>
                <p style="font-size: 1.2rem; margin-top: 20px;">No video lectures found right now. Check back soon!</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
