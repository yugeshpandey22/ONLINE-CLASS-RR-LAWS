<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>

<main>
    <section class="page-header" style="background: var(--dark-bg); padding: 100px 0 60px; text-align: center; color: white;">
        <div class="container">
            <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Premium <span class="text-gradient">Legal Courses</span></h1>
            <p style="color: #94A3B8; font-size: 1.2rem; max-width: 700px; margin: 0 auto;">Deep dive into specialized law subjects with our structured curriculum and expert advocacy blueprints.</p>
        </div>
    </section>

    <div class="container" style="padding: 80px 20px;">
        <div class="course-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 40px;">
            <?php
            $res = $conn->query("SELECT * FROM courses WHERE active = 1 ORDER BY created_at DESC");
            if($res && $res->num_rows > 0):
                while($row = $res->fetch_assoc()):
                    $img = !empty($row['image']) ? '../' . $row['image'] : '../assets/images/hero.png';
            ?>
            <div class="course-card glass-panel" style="background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.05); transition: all 0.4s ease; border: 1px solid var(--border);">
                <div style="height: 220px; overflow: hidden; position: relative;">
                    <img src="<?php echo $img; ?>" alt="Course" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; top: 20px; right: 20px; background: var(--primary); color: white; padding: 6px 15px; border-radius: 50px; font-weight: 700; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);">
                        ₹<?php echo number_format($row['price'], 0); ?>
                    </div>
                </div>
                <div style="padding: 30px;">
                    <h3 style="font-size: 1.6rem; margin-bottom: 15px; color: var(--dark-bg);"><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 25px; line-height: 1.6;">
                        <?php echo substr(strip_tags($row['description']), 0, 120); ?>...
                    </p>
                    <div style="display: flex; gap: 15px;">
                        <a href="course_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-outline" style="flex: 1; border: 2px solid var(--dark-bg); color: var(--dark-bg) !important; text-align: center; border-radius: 12px; font-weight: 700;">View Syllabus</a>
                        <a href="buy_course.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="flex: 1; text-align: center; border-radius: 12px; font-weight: 700;">Buy Now</a>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                <h3 class="text-muted">New premium courses are launching soon. Stay tuned!</h3>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.course-card:hover { transform: translateY(-10px); border-color: var(--primary); box-shadow: 0 40px 80px rgba(0,0,0,0.1); }
</style>

<?php include '../includes/footer.php'; ?>
