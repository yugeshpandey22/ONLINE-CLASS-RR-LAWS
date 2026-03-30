<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>

<main>
    <section class="page-header" style="background: var(--dark-bg); padding: 100px 0 60px; text-align: center; color: white;">
        <div class="container">
            <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Premium <span class="text-gradient">Legal Courses</span></h1>
            <p style="color: #94A3B8; font-size: 1.2rem; max-width: 700px; margin: 0 auto;">Deep dive into specialized law subjects with our structured curriculum and expert advocacy blueprints.</p>
        </div>
    </section>

    <div class="container" style="padding: 100px 20px;">
        <div class="course-grid gsap-stagger" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 50px;">
            <?php
            $res = $conn->query("SELECT * FROM courses WHERE active = 1 ORDER BY created_at DESC");
            if($res && $res->num_rows > 0):
                while($row = $res->fetch_assoc()):
                    $img = !empty($row['image']) ? '../' . $row['image'] : '../assets/images/hero.png';
                    $price = floatval($row['price']);
                    $old_price = $price * 1.5; // Simulate discount for premium feel
            ?>
            <!-- PREMIUM DARK CARD -->
            <div class="premium-card" style="background: #0A0A0A; border-radius: 30px; padding: 25px; border: 1px solid #1A1A1A; transition: 0.4s; box-shadow: 0 40px 100px rgba(0,0,0,0.5);">
                <!-- Thumbnail -->
                <div style="position: relative; border-radius: 20px; overflow: hidden; margin-bottom: 25px; height: 260px; border: 1px solid #222;">
                    <img src="<?php echo $img; ?>" alt="Course" style="width: 100%; height: 100%; object-fit: cover;">
                    <!-- Live Badge -->
                    <div style="position: absolute; top: 15px; right: 15px; background: white; color: #EF4444; padding: 5px 15px; border-radius: 8px; font-weight: 800; display: flex; align-items: center; gap: 8px; font-size: 0.85rem; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                        <span style="display:block; width:8px; height:8px; background:#EF4444; border-radius:50%; animation: pulse 2s infinite;"></span> LIVE
                    </div>
                </div>

                <!-- Category Pills -->
                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                    <span style="background: #151515; color: #94A3B8; padding: 8px 18px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; border: 1px solid #222;">Integrated Mentorship</span>
                    <span style="background: #151515; color: #94A3B8; padding: 8px 18px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; border: 1px solid #222;">Sasaram Special</span>
                </div>

                <!-- Title -->
                <h3 style="font-size: 2rem; font-weight: 850; color: white; margin-bottom: 30px; line-height: 1.3;">
                    <?php echo htmlspecialchars($row['title']); ?>
                </h3>

                <!-- Pricing Section -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 35px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span style="color: #666; font-size: 1.5rem; font-weight: 600; text-decoration: line-through;">Rs.<?php echo number_format($old_price, 0); ?></span>
                        <span style="color: white; font-size: 2.2rem; font-weight: 850; letter-spacing: -1px;">Price <span style="color: #FFA511;">Rs.<?php echo number_format($price, 0); ?></span></span>
                    </div>
                    <div style="background: white; color: black; padding: 5px 15px; border-radius: 8px; font-weight: 850; font-size: 0.9rem;">
                        50% OFF
                    </div>
                </div>

                <!-- Bottom Button -->
                <div style="border-top: 1px solid #151515; padding-top: 25px; display: flex; gap: 15px;">
                    <a href="course_detail.php?id=<?php echo $row['id']; ?>" class="btn-check-course" style="display: flex; align-items: center; justify-items: center; color: white !important; font-size: 1.15rem; font-weight: 800; text-decoration: none; border: 1px solid #222; padding: 15px 30px; border-radius: 12px; transition: 0.3s; width: 100%; text-align: center; justify-content: center;">
                        Check Course &nbsp; &rarr;
                    </a>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                <h3 style="color: #64748B;">New academy courses are launching soon. Stay tuned!</h3>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.course-card:hover { transform: translateY(-10px); border-color: var(--primary); box-shadow: 0 40px 80px rgba(0,0,0,0.1); }
</style>

<?php include '../includes/footer.php'; ?>
