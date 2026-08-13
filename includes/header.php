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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="site-header">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <span class="logo-icon">B</span>
            Blog<span>Space</span>
        </a>

        <nav class="main-nav">
            <a href="index.php">Home</a>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="create_blog.php">Write</a>
                <a href="logout.php" class="btn-nav">Logout</a>
            <?php else: ?>
                <a href="login.php">Sign In</a>
                <a href="register.php" class="btn-nav">Get Started</a>
            <?php endif; ?>
        </nav>
    </div>
</header>