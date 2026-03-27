<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
include '../includes/db.php';

if (isset($_GET['id']) && $conn) {
    $id = intval($_GET['id']);
    
    // get image, video, and pdf to delete files
    $stmt = $conn->prepare("SELECT image, video_url, pdf_path FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    
    if ($post) {
        if (!empty($post['image'])) {
            $imgPath = '../' . $post['image'];
            if (file_exists($imgPath)) unlink($imgPath);
        }
        
        if (!empty($post['video_url']) && strpos($post['video_url'], 'uploads/videos/') === 0) {
            $vidPath = '../' . $post['video_url'];
            if (file_exists($vidPath)) unlink($vidPath);
        }

        if (!empty($post['pdf_path'])) {
            $pdfPath = '../' . $post['pdf_path'];
            if (file_exists($pdfPath)) unlink($pdfPath);
        }
    }
    
    $del = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
}

header('Location: manage_posts.php');
exit;
