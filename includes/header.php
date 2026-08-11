<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Blog</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="navbar">

    <div class="container nav-container">

        <a href="index.php" class="logo">
            My Blog
        </a>

        <nav>

    <a href="index.php">Home</a>


    <?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    ?>


    <?php if (isset($_SESSION["user_id"])): ?>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="create_blog.php">
            Create Blog
        </a>

        <a href="logout.php">
            Logout
        </a>

    <?php else: ?>

        <a href="login.php">
            Login
        </a>

        <a href="register.php">
            Register
        </a>

    <?php endif; ?>

</nav>

    </div>

</header>

<main>