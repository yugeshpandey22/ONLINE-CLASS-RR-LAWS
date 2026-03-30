<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'blog_db';

$conn = null;

try {
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
    
    // Ensure database exists
    $conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db($dbname);
    
    // --- TABLES SETUP ---

    // 1. Posts (Blogs & Lectures)
    $conn->query("
        CREATE TABLE IF NOT EXISTS posts (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255),
            video_url VARCHAR(255),
            post_type VARCHAR(50) DEFAULT 'blog',
            pdf_path VARCHAR(255),
            course_id INT(11) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    
    // 2. Courses
    $conn->query("
        CREATE TABLE IF NOT EXISTS courses (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10,2) DEFAULT 0.00,
            image VARCHAR(255),
            active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    // 3. System Users (Students)
    $conn->query("
        CREATE TABLE IF NOT EXISTS users (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            registration_number VARCHAR(50) NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    // 4. Enrollments
    $conn->query("
        CREATE TABLE IF NOT EXISTS enrollments (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11),
            course_id INT(11),
            payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
            transaction_id VARCHAR(255),
            enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
        ) ENGINE=InnoDB
    ");
    
    // 5. Student Results (Achievement Showcase)
    $conn->query("
        CREATE TABLE IF NOT EXISTS student_results (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            student_name VARCHAR(255) NOT NULL,
            exam_title VARCHAR(255) NOT NULL,
            score VARCHAR(50) DEFAULT '100/100',
            result_year VARCHAR(50),
            image_path VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    // 6. Admins
    $conn->query("
        CREATE TABLE IF NOT EXISTS admins (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    // 7. Password Reset Tokens
    $conn->query("
        CREATE TABLE IF NOT EXISTS password_reset_tokens (
            email VARCHAR(255) NOT NULL,
            token VARCHAR(255) NOT NULL,
            expires_at DATETIME NOT NULL,
            PRIMARY KEY (email)
        ) ENGINE=InnoDB
    ");

    // Default Admin Seeds
    $result = $conn->query('SELECT COUNT(*) as count FROM admins');
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$hash')");
    }

} catch (Exception $e) {
    $conn = null;
    // Log or handle error quietly
}
?>
