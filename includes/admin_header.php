<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
$_SESSION['admin_name'] = "Yugesh Pandey";
// Include DB once here so all admin pages share it
include_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - RR LAWS</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-bg: #0f172a; /* Slate 900 */
            --sidebar-hover: #1e293b; /* Slate 800 */
            --sidebar-active: #3b82f6; /* Blue 500 */
            --sidebar-text: #94a3b8; /* Slate 400 */
            --main-bg: #f8fafc; /* Slate 50 */
            --header-bg: rgba(255, 255, 255, 0.8);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --primary-accent: #6366f1; /* Indigo 500 */
        }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--main-bg);
            font-size: 0.95rem;
            color: #1e293b;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles - Ultra Premium */
        .sidebar { 
            width: 280px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text); 
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 30px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-brand {
            padding: 40px 25px;
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }
        .sidebar-brand .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--sidebar-active), var(--primary-accent));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }
        
        .sidebar-nav {
            padding: 0 15px;
            flex-grow: 1;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar li {
            margin-bottom: 5px;
        }
        .sidebar li a { 
            color: var(--sidebar-text); 
            text-decoration: none; 
            display: flex; 
            align-items: center;
            padding: 14px 20px; 
            border-radius: 12px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 14px;
        }
        .sidebar li a:hover { 
            background: var(--sidebar-hover); 
            color: #fff; 
            transform: translateX(5px);
        }
        .sidebar li a.active { 
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #ffffff; 
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25);
        }
        .sidebar li a i {
            font-size: 1.25rem;
            margin-right: 15px;
            opacity: 0.8;
        }
        
        /* Submenu refinement */
        .sidebar .collapse ul {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            margin: 5px 0 10px 10px;
            padding: 5px;
        }
        .sidebar .collapse li a {
            padding: 10px 15px 10px 45px;
            font-size: 13px;
        }

        /* Logout button */
        .logout-wrapper {
            padding: 25px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
        }

        /* Main Content & Header */
        .main-content { 
            flex-grow: 1;
            padding: 40px; 
            min-width: 0;
            background: var(--main-bg);
        }
        
        .page-header {
            background: var(--header-bg);
            backdrop-filter: blur(12px);
            padding: 20px 30px;
            border-radius: 20px;
            border: 1px solid #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            box-shadow: var(--card-shadow);
        }
        .page-header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .page-header h2 span {
            color: var(--sidebar-active);
        }
        
        /* Profile Component */
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 8px 15px;
            background: #fff;
            border-radius: 100px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .profile-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }
        
        /* Stats Cards - Modernized */
        .stat-card {
            border: none;
            padding: 30px;
            border-radius: 24px;
            color: white;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
        }
        .stat-card i {
            position: absolute;
            top: -20px;
            right: -10px;
            font-size: 100px;
            opacity: 0.1;
            transition: all 0.4s;
        }
        .stat-card:hover i {
            transform: rotate(-15deg) scale(1.1);
            opacity: 0.2;
        }
        
        /* Modern Buttons */
        .btn-modern {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            padding: 12px 25px;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-modern:hover {
            background: #fff;
            color: #0f172a;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon"><i class="bi bi-shield-lock"></i></div>
            <span>RR LAWS</span>
        </div>
        <div class="sidebar-nav">
            <ul>
                <li>
                    <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="bi bi-house-door-fill"></i> Dashboard
                    </a>
                </li>
                
                <!-- Blogs section -->
                <li>
                    <a href="#blogsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex justify-content-between align-items-center <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add_blog.php']) || (basename($_SERVER['PHP_SELF']) == 'manage_posts.php' && ($_GET['type'] ?? '') == 'blog') ? '' : 'collapsed'; ?>">
                        <span><i class="bi bi-rss"></i> Blogs</span>
                        <i class="bi bi-chevron-down ms-auto" style="font-size: 0.85rem; margin-right: 0;"></i>
                    </a>
                    <ul class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add_blog.php']) || (basename($_SERVER['PHP_SELF']) == 'manage_posts.php' && ($_GET['type'] ?? '') == 'blog') ? 'show' : ''; ?> list-unstyled" id="blogsSubmenu" style="background: rgba(0,0,0,0.15);">
                        <li>
                            <a href="manage_posts.php?type=blog" style="padding: 10px 20px 10px 52px; font-size: 14px; border:none; <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_posts.php' && ($_GET['type'] ?? '') == 'blog') ? 'color: #3b82f6;' : ''; ?>">Manage Blogs</a>
                        </li>
                        <li>
                            <a href="add_blog.php" style="padding: 10px 20px 10px 52px; font-size: 14px; border:none; <?php echo basename($_SERVER['PHP_SELF']) == 'add_blog.php' ? 'color: #3b82f6;' : ''; ?>">Add New Blog</a>
                        </li>
                    </ul>
                </li>
                
                <!-- Videos section -->
                <li>
                    <a href="#videosSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex justify-content-between align-items-center <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add_video.php']) || (basename($_SERVER['PHP_SELF']) == 'manage_posts.php' && ($_GET['type'] ?? '') == 'lecture') ? '' : 'collapsed'; ?>">
                        <span><i class="bi bi-play-btn"></i> Lectures</span>
                        <i class="bi bi-chevron-down ms-auto" style="font-size: 0.85rem; margin-right: 0;"></i>
                    </a>
                    <ul class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add_video.php']) || (basename($_SERVER['PHP_SELF']) == 'manage_posts.php' && ($_GET['type'] ?? '') == 'lecture') ? 'show' : ''; ?> list-unstyled" id="videosSubmenu" style="background: rgba(0,0,0,0.15);">
                        <li>
                            <a href="manage_posts.php?type=lecture" style="padding: 10px 20px 10px 52px; font-size: 14px; border:none; <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_posts.php' && ($_GET['type'] ?? '') == 'lecture') ? 'color: #3b82f6;' : ''; ?>">Manage Lectures</a>
                        </li>
                        <li>
                            <a href="add_video.php" style="padding: 10px 20px 10px 52px; font-size: 14px; border:none; <?php echo basename($_SERVER['PHP_SELF']) == 'add_video.php' ? 'color: #3b82f6;' : ''; ?>">Upload Video</a>
                        </li>
                    </ul>
                </li>

                <!-- PDF section -->
                <li>
                    <a href="manage_resources.php" class="d-flex justify-content-between align-items-center <?php echo basename($_SERVER['PHP_SELF']) == 'manage_resources.php' ? 'active' : ''; ?>">
                        <span><i class="bi bi-file-earmark-pdf"></i> PDF Notes</span>
                    </a>
                </li>

                <!-- Courses section -->
                <li>
                    <a href="manage_courses.php" class="d-flex justify-content-between align-items-center <?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_courses.php', 'add_course.php']) ? 'active' : ''; ?>">
                        <span><i class="bi bi-mortarboard"></i> Courses</span>
                    </a>
                </li>

                <!-- Enrollments section -->
                <li>
                    <a href="manage_enrollments.php" class="d-flex justify-content-between align-items-center <?php echo basename($_SERVER['PHP_SELF']) == 'manage_enrollments.php' ? 'active' : ''; ?>">
                        <span><i class="bi bi-person-check"></i> Enrollments</span>
                    </a>
                </li>

                <!-- Live Class section -->
                <li>
                    <a href="manage_live.php" class="d-flex justify-content-between align-items-center <?php echo basename($_SERVER['PHP_SELF']) == 'manage_live.php' ? 'active' : ''; ?>">
                        <span><i class="bi bi-broadcast text-danger"></i> Live Classes</span>
                    </a>
                </li>

                <li style="margin-top: 30px; margin-bottom: 10px; padding-left: 22px; font-size: 11px; text-transform: uppercase; color: #5b6b7a; font-weight: 700; letter-spacing: 1px;">External</li>
                
                <li>
                    <a href="../index.php" target="_blank">
                        <i class="bi bi-globe"></i> View Website
                    </a>
                </li>
            </ul>
        </div>
        <div class="logout-wrapper">
            <a href="logout.php" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
