<?php
session_start();
include __DIR__ . '/../includes/db.php';

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if($conn) {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Database connection error!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RR LAWS</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #0d324d, #7f5a83);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .login-wrapper {
            position: relative;
            z-index: 10;
        }
        .login-box { 
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px; 
            border-radius: 20px; 
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            width: 100%; 
            max-width: 420px; 
        }
        .login-title {
            color: #ffffff;
            font-weight: 600;
            font-size: 28px;
            letter-spacing: 1px;
        }
        .login-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-bottom: 30px;
        }
        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 12px 15px;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .btn-login {
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-top: 15px;
            color: #fff;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 242, 254, 0.3);
            color: #fff;
        }
        .input-group-text {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-right: none;
            color: #fff;
        }
        .form-control {
            border-left: none;
        }
        .shape {
            position: absolute;
            filter: blur(80px);
            z-index: 1;
        }
        .shape-1 {
            width: 300px;
            height: 300px;
            background: #4facfe;
            top: -100px;
            left: -100px;
            border-radius: 50%;
        }
        .shape-2 {
            width: 400px;
            height: 400px;
            background: #7f5a83;
            bottom: -150px;
            right: -100px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-wrapper w-100 d-flex justify-content-center">
        <div class="login-box">
            <div class="text-center">
                <div class="mb-3">
                    <i class="bi bi-shield-lock-fill" style="font-size: 3rem; color: #4facfe;"></i>
                </div>
                <h3 class="login-title">RR LAWS</h3>
                <p class="login-subtitle">Secure Admin Authentication</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #ffcccc; border-radius: 10px;"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-login w-100">Access Dashboard <i class="bi bi-arrow-right-short"></i></button>
            </form>
        </div>
    </div>
</body>
</html>
