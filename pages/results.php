<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>

<main style="background: var(--light-bg); padding: 180px 0 100px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 80px;">
            <div class="badge-glass" style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 20px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; border: 1px solid rgba(212, 175, 55, 0.3); background: rgba(255, 255, 255, 0.05); color: var(--primary);">
                Academic Excellence
            </div>
            <h1 style="font-size: 4rem; font-weight: 800; color: var(--dark-bg); margin-bottom: 25px;">Academy <span class="text-gradient">Hall of Fame</span></h1>
            <p style="color: var(--text-muted); font-size: 1.25rem; max-width: 800px; margin: 0 auto;">Celebrating the extraordinary achievements of our legal scholars. Their hard work and dedication drive our mission forward.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 40px;">
            <?php
            $results = $conn->query("SELECT * FROM student_results ORDER BY created_at DESC");
            if ($results && $results->num_rows > 0):
                while($row = $results->fetch_assoc()):
            ?>
            <article class="glass-panel" style="background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 40px 80px rgba(0,0,0,0.05); border: 1px solid var(--border); transition: 0.4s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="height: 480px; overflow: hidden; position: relative; cursor: pointer;" onclick="window.open('../<?php echo $row['image_path']; ?>', '_blank')">
                    <img src="../<?php echo !empty($row['image_path']) ? $row['image_path'] : 'assets/images/hero.png'; ?>" alt="Result Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 30px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill small" style="margin-bottom: 10px; font-weight: 700; letter-spacing: 1px;"><?php echo htmlspecialchars($row['result_year']); ?></span>
                        <h3 style="color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: 5px;"><?php echo htmlspecialchars($row['student_name']); ?></h3>
                        <p style="color: rgba(255,255,255,0.7); font-weight: 600; margin-bottom: 0;">Exam: <?php echo htmlspecialchars($row['exam_title']); ?></p>
                    </div>
                </div>
                <div style="padding: 25px 30px; text-align: center;">
                    <a href="../<?php echo $row['image_path']; ?>" target="_blank" class="btn btn-outline w-100 rounded-pill py-3 fw-bold" style="border: 2px solid var(--primary); color: var(--primary); font-size: 0.95rem;">View Full Marksheet &rarr;</a>
                </div>
            </article>
            <?php endwhile; else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                    <i class="fas fa-trophy" style="font-size: 5rem; color: #E2E8F0; margin-bottom: 30px;"></i>
                    <h2 class="text-muted">The Hall of Fame is being updated. Next batch of winners coming soon!</h2>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.text-gradient {
    background: linear-gradient(135deg, var(--primary), #947b0a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>

<?php include '../includes/footer.php'; ?>
