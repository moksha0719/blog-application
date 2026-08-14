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
    
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="site-header">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <span class="logo-icon">B</span>
            <span class="logo-text">Blog<span>Space</span></span>
        </a>

        <nav class="main-nav">
            <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="index.php#how-it-works" class="nav-link">How It Works</a>
            
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="create_blog.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'create_blog.php' ? 'active' : ''; ?>">Write</a>
                <div class="nav-auth">
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
            <?php else: ?>
                <div class="nav-auth">
                    <a href="login.php" class="btn-login <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">Login</a>
                    <a href="register.php" class="btn-signup <?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : ''; ?>">Sign Up</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>