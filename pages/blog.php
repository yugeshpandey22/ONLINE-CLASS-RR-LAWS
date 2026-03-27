<?php 
include '../includes/header.php'; 
include '../includes/db.php';
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Our Insights & Blog</h1>
            <p>Expert legal opinions, insights, and news from our team.</p>
        </div>
    </section>

    <section class="blog-listing container">
        <div class="blog-grid">
            <?php
            // Fetch dynamically from database securely
            $posts = [];
            if(isset($conn) && $conn !== null) {
                try {
                    $result = $conn->query("SELECT * FROM posts WHERE post_type = 'blog' ORDER BY created_at DESC");
                    while($row = $result->fetch_assoc()) {
                        $posts[] = $row;
                    }
                } catch (Exception $e) {
                    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
            
            if(count($posts) > 0):
                foreach($posts as $post):
                    $img_src = !empty($post['image']) ? '../' . $post['image'] : '../assets/images/post1.png';
            ?>
             <article class="blog-card">
                <div class="blog-img" style="position: relative;">
                    <img src="<?php echo htmlspecialchars($img_src); ?>" alt="Thumbnail">
                    <?php if(!empty($post['video_url'])): ?>
                    <span class="badge bg-primary" style="position: absolute; top:10px; right:10px; padding: 4px 10px; border-radius: 4px; color: white;"><i class="fas fa-video"></i> 4-5 min Clip</span>
                    <?php endif; ?>
                </div>
                <div class="blog-content">
                    <span class="blog-category">Blog</span>
                    <h3><a href="single.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <p><?php echo substr(htmlspecialchars(strip_tags($post['content'])), 0, 100); ?>...</p>
                    <a href="single.php?id=<?php echo $post['id']; ?>" class="read-more">Read Full Post &rarr;</a>
                </div>
            </article>
            <?php 
                endforeach;
            else:
            ?>
                <p style="font-size: 1.2rem; margin-top: 20px;">No active articles found right now. Check back soon!</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
