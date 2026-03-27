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
    $conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $conn->select_db($dbname);
    
    
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
        )
    ");
    
    // Soft alter: only add column if it doesn't exist (PHP 8.1+ throws on duplicate)
    $col_check = $conn->query("SHOW COLUMNS FROM posts LIKE 'post_type'");
    if ($col_check && $col_check->num_rows == 0) {
        $conn->query("ALTER TABLE posts ADD COLUMN post_type VARCHAR(50) DEFAULT 'blog'");
    }

    $col_check_pdf = $conn->query("SHOW COLUMNS FROM posts LIKE 'pdf_path'");
    if ($col_check_pdf && $col_check_pdf->num_rows == 0) {
        $conn->query("ALTER TABLE posts ADD COLUMN pdf_path VARCHAR(255)");
    }

    $col_check_course = $conn->query("SHOW COLUMNS FROM posts LIKE 'course_id'");
    if ($col_check_course && $col_check_course->num_rows == 0) {
        $conn->query("ALTER TABLE posts ADD COLUMN course_id INT(11) DEFAULT NULL");
    }
    
    
    $conn->query("
        CREATE TABLE IF NOT EXISTS courses (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10,2) DEFAULT 0.00,
            image VARCHAR(255),
            active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $conn->query("
        CREATE TABLE IF NOT EXISTS users (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $conn->query("
        CREATE TABLE IF NOT EXISTS enrollments (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11),
            course_id INT(11),
            payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
            transaction_id VARCHAR(255),
            enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (course_id) REFERENCES courses(id)
        )
    ");
    
    $conn->query("
        CREATE TABLE IF NOT EXISTS live_classes (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            meeting_url VARCHAR(555) NOT NULL,
            scheduled_at DATETIME NOT NULL,
            status ENUM('upcoming', 'live', 'ended') DEFAULT 'upcoming',
            is_public TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $col_check_live = $conn->query("SHOW COLUMNS FROM live_classes LIKE 'is_public'");
    if ($col_check_live && $col_check_live->num_rows == 0) {
        $conn->query("ALTER TABLE live_classes ADD COLUMN is_public TINYINT(1) DEFAULT 0");
    }

    $conn->query("
        CREATE TABLE IF NOT EXISTS admins (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $result = $conn->query('SELECT COUNT(*) as count FROM admins');
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$hash')");
    }

} catch (Exception $e) {
    $conn = null;
    // Uncomment below line only for local debugging:
    // die("DB Error: " . $e->getMessage());
}
?>
