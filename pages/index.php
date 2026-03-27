<?php 
include '../includes/header.php'; 
include '../includes/db.php';
?>

<main>
    <!-- Ultra Premium Hero Section -->
    <section class="hero position-relative" style="background: var(--dark-bg); overflow: hidden; padding: 180px 0 150px;">
        <!-- Animated Background Elements -->
        <div class="blob-cont">
            <div class="yellow-blob"></div>
            <div class="blue-blob"></div>
        </div>
        
        <div class="container hero-content position-relative z-10" style="text-align: center; max-width: 950px; margin: 0 auto; z-index: 10;">
            <div class="badge-glass" style="display: inline-flex; align-items: center; gap: 10px; padding: 8px 25px; border-radius: 50px; font-size: 0.95rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px; border: 1px solid rgba(212, 175, 55, 0.4); background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); color: var(--primary); animation: slideDown 0.8s ease-out;">
                <span style="display:block; width:8px; height:8px; background:var(--primary); border-radius:50%; box-shadow: 0 0 10px var(--primary);"></span> Concept Wallah & Associates
            </div>
            <h1 class="hero-title" style="font-size: 5rem; line-height: 1.1; margin-bottom: 25px; font-weight: 800; color: white; animation: slideUp 1s ease-out;">
                Master the Law.<br><span class="text-gradient">Secure Your Future.</span>
            </h1>
            <p style="font-size: 1.35rem; color: #94A3B8; margin-bottom: 45px; font-weight: 300; max-width: 800px; margin-inline: auto; animation: fadeIn 1.2s ease-out;">
                Elite academic mentorship and aggressive legal advocacy. We bridge the gap between complex theoretical concepts and real-world courtroom triumphs.
            </p>
            <div class="hero-buttons" style="animation: fadeIn 1.5s ease-out;">
                <a href="courses.php" class="btn btn-primary glow-button" style="padding: 18px 45px; font-size: 1.15rem; border-radius: 50px;">Join Academy <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></a>
                <a href="about.php" class="btn btn-outline" style="padding: 18px 45px; font-size: 1.15rem; border-radius: 50px; border: 2px solid rgba(255,255,255,0.7); display: flex; align-items: center; gap: 8px;"><i class="fas fa-play-circle"></i> Explore Methodology</a>
            </div>
        </div>
    </section>

    <!-- Premium Three-Pillar Features -->
    <section class="features container" style="margin-top: -80px; position: relative; z-index: 20;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px;">
            <div class="feature-card glass-panel" style="background: rgba(255,255,255,0.95); padding: 50px 40px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); text-align: center; position: relative; overflow: hidden; transform-style: preserve-3d; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                <div class="card-glow" style="background: rgba(212, 175, 55, 0.15);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.05)); color: var(--primary); font-size: 2.2rem; display: flex; align-items: center; justify-content: center; border-radius: 20px; margin: 0 auto 25px; border: 1px solid rgba(212, 175, 55, 0.3); transform: translateZ(20px);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 style="font-size: 1.6rem; margin-bottom: 15px; color: var(--dark-bg); font-weight: 700; transform: translateZ(15px);">Advanced Pedagogy</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 1.05rem; transform: translateZ(10px);">Deep conceptual clarity designed for absolute mastery in specialized, high-stakes subjects.</p>
            </div>
            
            <div class="feature-card glass-panel" style="background: rgba(255,255,255,0.95); padding: 50px 40px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); text-align: center; position: relative; overflow: hidden; transform-style: preserve-3d; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                <div class="card-glow" style="background: rgba(59, 130, 246, 0.15);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.05)); color: #3b82f6; font-size: 2.2rem; display: flex; align-items: center; justify-content: center; border-radius: 20px; margin: 0 auto 25px; border: 1px solid rgba(59, 130, 246, 0.3); transform: translateZ(20px);">
                    <i class="fas fa-gavel"></i>
                </div>
                <h3 style="font-size: 1.6rem; margin-bottom: 15px; color: var(--dark-bg); font-weight: 700; transform: translateZ(15px);">Elite Advocacy</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 1.05rem; transform: translateZ(10px);">Unmatched legal strategy, top-tier courtroom representation, and critical compliance advisory.</p>
            </div>

            <div class="feature-card glass-panel" style="background: rgba(255,255,255,0.95); padding: 50px 40px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); text-align: center; position: relative; overflow: hidden; transform-style: preserve-3d; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                <div class="card-glow" style="background: rgba(16, 185, 129, 0.15);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.05)); color: #10b981; font-size: 2.2rem; display: flex; align-items: center; justify-content: center; border-radius: 20px; margin: 0 auto 25px; border: 1px solid rgba(16, 185, 129, 0.3); transform: translateZ(20px);">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 style="font-size: 1.6rem; margin-bottom: 15px; color: var(--dark-bg); font-weight: 700; transform: translateZ(15px);">Corporate Advisory</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 1.05rem; transform: translateZ(10px);">Navigating extremely complex legal frameworks for fast-growing businesses and leading entrepreneurs.</p>
            </div>
        </div>
    </section>

    <!-- Introduce The Expert -->
    <section class="container about-grid" style="padding: 120px 0; display: grid; gap: 80px; align-items: center;">
        <div class="position-relative">
            <div style="position: absolute; top:-20px; left:-20px; width:150px; height:150px; background:var(--primary); border-radius:50%; filter:blur(60px); opacity:0.3; z-index:-1;"></div>
            <img src="../assets/images/about.png" alt="Concept Wallah Advocate" style="width: 100%; border-radius: 24px; box-shadow: 0 30px 60px rgba(0,0,0,0.2); transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        </div>
        <div>
            <span style="color: var(--primary); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px; display: inline-block; background:rgba(212,175,55,0.1); padding:8px 16px; border-radius:50px; border:1px solid rgba(212,175,55,0.3);"><i class="fas fa-star me-2"></i> Meet The Expert</span>
            <h2 style="font-size: 3.2rem; line-height: 1.15; margin-bottom: 30px; color: var(--dark-bg); font-weight: 800;">Where Academia Meets The Courtroom.</h2>
            <p style="font-size: 1.15rem; color: var(--text-muted); margin-bottom: 25px; line-height: 1.8;">With years of experience teaching complex curriculum and actively fighting high-stakes cases, Concept Wallah offers a highly specialized structural methodology that is simply unmatched in the industry.</p>
            <p style="font-size: 1.15rem; color: var(--text-muted); margin-bottom: 40px; line-height: 1.8;">Whether you're an ambitious student aiming for top-tier absolute ranks or a corporate client needing a bulletproof legal defense strategy, my methodology is built entirely on absolute clarity and relentless execution.</p>
            <a href="about.php" class="btn btn-outline" style="color: var(--dark-bg); border: 2px solid var(--dark-bg); padding: 15px 35px; border-radius: 50px; font-weight: 700; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease;" onmouseover="this.style.background='var(--dark-bg)'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='var(--dark-bg)';">Read Full Profile <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    <!-- Latest Insights Section -->
    <section class="position-relative" style="background: var(--dark-bg); padding: 120px 0; overflow: hidden; margin-top: -100px; padding-top: 180px;">
        <div style="position: absolute; top: 10%; right: -5%; width: 400px; height: 400px; background: rgba(212, 175, 55, 0.1); border-radius: 50%; filter: blur(80px); z-index: 0; pointer-events: none;"></div>
        <div style="position: absolute; bottom: -5%; left: -5%; width: 500px; height: 500px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; filter: blur(100px); z-index: 0; pointer-events: none;"></div>

        <div class="container position-relative" style="z-index: 10;">
            <div class="section-title" style="text-align: center; margin-bottom: 70px; display: flex; flex-direction: column; align-items: center; border: none; padding-bottom: 0;">
                <div class="badge-glass" style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 20px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; border: 1px solid rgba(212, 175, 55, 0.3); background: rgba(255, 255, 255, 0.05); color: var(--primary);">
                    Knowledge Repository
                </div>
                <h2 style="color: white; font-size: 3rem; margin-bottom: 20px; font-weight: 800;">Latest Insights & Lectures</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 40px;">
                <?php
                $posts = [];
                if(isset($conn) && $conn !== null) {
                    try {
                        $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 2");
                        while($row = $result->fetch_assoc()) {
                            $posts[] = $row;
                        }
                    } catch (Exception $e) {
                         // Fail silently
                    }
                }
                
                if(count($posts) > 0):
                    foreach($posts as $post):
                        $img_src = !empty($post['image']) ? '../' . $post['image'] : '../assets/images/post1.png';
                        $date = date('M d, Y', strtotime($post['created_at']));
                ?>
                 <article class="blog-card glass-panel" style="background: rgba(255,255,255,0.03) !important; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; overflow: hidden; position: relative; transition: all 0.4s ease; transform-style: preserve-3d; color: white;">
                    <div class="card-glow" style="background: rgba(255,255,255,0.05);"></div>
                    <div style="height: 240px; overflow: hidden; position: relative;">
                        <a href="single.php?id=<?php echo $post['id']; ?>" style="display: block; width: 100%; height: 100%;">
                            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);">
                        </a>
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, transparent 50%, rgba(10,15,30,0.9)); pointer-events: none;"></div>
                        <?php if($post['post_type'] == 'lecture'): ?>
                            <span class="badge" style="position: absolute; top:20px; right:20px; padding: 6px 15px; border-radius: 50px; background: rgba(239, 68, 68, 0.9); border: 1px solid #ef4444; color: white; display:flex; align-items:center; gap:6px; font-size: 0.8rem; letter-spacing: 1px;"><i class="fas fa-play-circle"></i> Video Lecture</span>
                        <?php endif; ?>
                    </div>
                    
                    <div style="padding: 35px 30px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <?php if($post['post_type'] == 'lecture'): ?>
                                <span style="background: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 6px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;"><i class="fas fa-play-circle me-1"></i> Full Session</span>
                            <?php else: ?>
                                <span style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; padding: 6px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;"><i class="fas fa-video me-1"></i> Quick Clip</span>
                            <?php endif; ?>
                            <span style="color: rgba(255,255,255,0.5); font-size: 0.85rem;"><i class="far fa-calendar-alt me-1"></i> <?php echo $date; ?></span>
                        </div>
                        
                        <h3 style="font-size: 1.5rem; margin-bottom: 20px; line-height: 1.4; font-weight: 700;">
                            <a href="single.php?id=<?php echo $post['id']; ?>" class="hover-primary" style="color: white; text-decoration: none; transition: 0.3s;"><?php echo htmlspecialchars($post['title']); ?></a>
                        </h3>
                        
                        <p style="color: rgba(255,255,255,0.6); margin-bottom: 25px; line-height: 1.7; font-size: 1.05rem;">
                            <?php echo substr(htmlspecialchars(strip_tags($post['content'])), 0, 100); ?>...
                        </p>
                        
                        <a href="single.php?id=<?php echo $post['id']; ?>" style="color: var(--primary); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px;" onmouseover="this.style.gap='12px'" onmouseout="this.style.gap='8px'">Study Material <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <?php 
                    endforeach;
                else:
                ?>
                    <p style="color: rgba(255,255,255,0.5); font-size: 1.1rem; text-align: center; grid-column: 1 / -1; padding: 40px; border: 1px dashed rgba(255,255,255,0.2); border-radius: 12px;">The knowledge repository is currently being updated. Fresh lectures and insights will be posted soon.</p>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center; margin-top: 60px; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <a href="blog.php" class="btn btn-primary glow-button" style="padding: 16px 45px; font-size: 1.1rem; border-radius: 50px; font-weight: 600;">Latest Blogs <i class="fas fa-file-text ms-2"></i></a>
                <a href="lectures.php" class="btn btn-outline" style="padding: 16px 45px; font-size: 1.1rem; border-radius: 50px; font-weight: 600; color: white; border: 2px solid rgba(255,255,255,0.3);">Video Lectures <i class="fas fa-play-circle ms-2"></i></a>
            </div>
        </div>
    </section>

    </section>
</main>

<style>
/* Hover dynamics */
.feature-card:hover { transform: translateY(-10px) translateZ(30px); }
.blog-card:hover { transform: translateY(-10px); border-color: rgba(212, 175, 55, 0.4) !important; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
.blog-card:hover img { transform: scale(1.08) !important; }
.hover-primary:hover { color: var(--primary) !important; }

/* Responsive adjustments */
@media (max-width: 992px) {
    .hero-title { font-size: 4rem !important; }
}
@media (max-width: 768px) {
    .hero-title { font-size: 2.8rem !important; }
    .about-grid { grid-template-columns: 1fr !important; gap: 40px !important; }
    .hero-content { padding: 0 20px; }
    .badge-glass { font-size: 0.8rem !important; }
}
</style>

<?php include '../includes/footer.php'; ?>
