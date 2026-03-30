    <footer class="footer">
        <div class="container footer-container">
            <!-- Column 1: Brand & Social -->
            <div class="footer-about">
                <a href="index.php"><img src="../assets/images/logo2.png" alt="Integrated Classes" style="height: 100px; object-fit: contain;"></a>
                <p>Integrated Classes Sasaram: Empowering students with deep conceptual clarity (100/100 in Maths) and defending rights with elite legal advocacy by our expert Advocate mentor.</p>
                <div class="footer-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Navigation -->
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Mentor</a></li>
                    <li><a href="blog.php">Latest Blog</a></li>
                    <li><a href="lectures.php">Video Lectures</a></li>
                    <li><a href="courses.php">All Courses</a></li>
                </ul>
            </div>

            <!-- Column 2: Resources -->
            <div class="footer-links">
                <h4>Support</h4>
                <ul>
                    <li><a href="results.php">Our Results</a></li>
                    <li><a href="pdf_notes.php">Free PDF Notes</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact Info -->
            <div class="footer-links">
                <h4>Get In Touch</h4>
                <ul class="footer-contact-list">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Premchand path,<br>Sasaram, Bihar</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>hello@integratedclasses.com</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>+91 91225 05252</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright © <?php echo date('Y'); ?> Integrated Classes Sasaram. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="../assets/js/script.js"></script>

<!-- GSAP Global Animations -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        gsap.registerPlugin(ScrollTrigger);

        // 1. Hero Content Entrance
        gsap.from(".hero-content .reveal-text", {
            y: 100, 
            opacity: 0, 
            duration: 1.2, 
            ease: "power4.out",
            stagger: 0.2
        });

        // 2. Global Fade-In Elements
        gsap.utils.toArray('.gsap-reveal').forEach((el) => {
            gsap.from(el, {
                scrollTrigger: {
                    trigger: el,
                    start: "top 85%",
                    toggleActions: "play none none none"
                },
                y: 50,
                opacity: 0,
                duration: 1,
                ease: "power2.out"
            });
        });

        // 3. Staggered Course/Blog Cards
        gsap.utils.toArray('.gsap-stagger').forEach((container) => {
            gsap.from(container.children, {
                scrollTrigger: {
                    trigger: container,
                    start: "top 80%"
                },
                y: 60,
                opacity: 0,
                duration: 1,
                stagger: 0.15,
                ease: "power3.out"
            });
        });
    });
</script>

</body>
</html>
