<?php include '../includes/header.php'; ?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Get In Touch</h1>
            <p>Connect with Concept Wallah Raushan Sir for premium educational mentorship and legal advocacy.</p>
        </div>
    </section>

    <section class="contact-section container">
        <div class="contact-grid">
            
            <div style="border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                <img src="../assets/images/raushan.png" alt="Concept Wallah - Raushan Sir" style="width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 16px;">
            </div>
            
            <div class="contact-form-wrapper">
                <h3>Send a Message</h3>
                <p>We'll get back to you within 24 business hours.</p>
                <form action="" method="post">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="John Doe" required>
                    
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="john@example.com" required>
                    
                    <label>Inquiry Type</label>
                    <select name="subject">
                        <option value="legal">Legal Consultation & Advocacy</option>
                        <option value="education">Academic Mentorship & Coaching</option>
                        <option value="partnership">Business Partnership</option>
                    </select>

                    <label>Your Message</label>
                    <textarea name="message" rows="5" placeholder="How can we help you?" required></textarea>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 15px; margin-top: 20px;">Send Inquiry</button>
                </form>
            </div>

        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
