<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogSpace</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 (Free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="site-header">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <span class="logo-icon"><i class="fas fa-blog"></i></span>
            <span class="logo-text">Blog<span>Space</span></span>
        </a>

        <nav class="main-nav">
            <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a>
            <a href="index.php#how-it-works" class="nav-link"><i class="fas fa-info-circle"></i> How It Works</a>
            
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
                <a href="create_blog.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'create_blog.php' ? 'active' : ''; ?>"><i class="fas fa-pen-to-square"></i> Write</a>
                <div class="nav-auth">
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            <?php else: ?>
                <div class="nav-auth">
                    <a href="login.php" class="btn-login <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="register.php" class="btn-signup <?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : ''; ?>"><i class="fas fa-user-plus"></i> Sign Up</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>