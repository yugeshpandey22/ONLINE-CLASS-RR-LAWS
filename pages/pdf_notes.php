<?php 
include '../includes/header.php'; 
include '../includes/db.php';
?>

<main>
    <!-- Premium Hero Section -->
    <section class="hero position-relative" style="background: var(--dark-bg); overflow: hidden; padding: 120px 0 80px;">
        <div class="blob-cont">
            <div class="yellow-blob"></div>
            <div class="blue-blob"></div>
        </div>
        
        <div class="container hero-content position-relative z-10" style="text-align: center; max-width: 900px; margin: 0 auto; z-index: 10;">
            <div class="badge-glass" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 25px; border-radius: 50px; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px; border: 1px solid rgba(212, 175, 55, 0.4); background: rgba(255, 255, 255, 0.05); color: var(--primary); animation: slideDown 0.8s ease-out;">
                Study Materials
            </div>
            <h1 class="hero-title" style="font-size: 4rem; line-height: 1.1; margin-bottom: 20px; font-weight: 800; color: white;">
                Lecture <span class="text-gradient">PDF Notes</span>
            </h1>
            <p style="font-size: 1.2rem; color: #94A3B8; margin-bottom: 0; font-weight: 300; max-width: 700px; margin-inline: auto;">
                Access exclusive study material, case law summaries, and lecture notes directly associated with our academic sessions.
            </p>
        </div>
    </section>

    <!-- PDF List Section -->
    <section class="container" style="padding: 80px 0; min-height: 400px;">
        <div class="pdf-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
            <?php
            if($conn) {
                // Show all PDFs - both attached to lectures and independent ones
                $result = $conn->query("SELECT * FROM posts WHERE pdf_path IS NOT NULL AND pdf_path != '' ORDER BY created_at DESC");
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()):
                        $pdf_url = '../' . $row['pdf_path'];
                        $title = htmlspecialchars($row['title']);
                        $content = substr(strip_tags($row['content']), 0, 100) . '...';
                        $date = date('M d, Y', strtotime($row['created_at']));
            ?>
            <div class="glass-panel" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; position: relative; overflow: hidden; transition: 0.3s ease;">
                <div style="font-size: 3rem; color: #ef4444; margin-bottom: 20px; filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.3));">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <h3 style="color: white; font-size: 1.4rem; margin-bottom: 12px; font-weight: 700;"><?php echo $title; ?></h3>
                <p style="color: rgba(255,255,255,0.6); font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px;">
                    <?php echo $content; ?>
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
                    <span style="color: rgba(255,255,255,0.5); font-size: 0.8rem;"><i class="far fa-clock me-1"></i> <?php echo $date; ?></span>
                    <a href="<?php echo $pdf_url; ?>" target="_blank" class="download-link" style="background: var(--primary); color: var(--dark-bg); padding: 8px 20px; border-radius: 50px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-download"></i> View PDF
                    </a>
                </div>
            </div>
            <?php 
                    endwhile;
                } else {
                    echo '<div style="grid-column: 1/-1; text-align: center; padding: 100px 0; border: 2px dashed rgba(255,255,255,0.1); border-radius: 20px;">
                            <i class="fas fa-folder-open" style="font-size: 4rem; color: rgba(255,255,255,0.2); margin-bottom: 20px;"></i>
                            <h3 style="color: rgba(255,255,255,0.5); font-weight: 400;">No PDF notes available yet.</h3>
                            <p style="color: rgba(255,255,255,0.3);">Check back later for updated study materials.</p>
                          </div>';
                }
            }
            ?>
        </div>
    </section>
</main>

<style>
.glass-panel:hover {
    transform: translateY(-10px);
    border-color: var(--primary);
    background: rgba(212, 175, 55, 0.05);
}
.download-link:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
}
@media (max-width: 768px) {
    .hero-title { font-size: 2.5rem !important; }
}
</style>

<?php include '../includes/footer.php'; ?>
