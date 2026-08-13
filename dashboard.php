<?php

require_once 'includes/auth.php';

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}

$first_letter = strtoupper(substr($_SESSION["username"], 0, 1));

?>

<?php include 'includes/header.php'; ?>


<section class="dashboard-section">

    <div class="dashboard-header">

        <h1>
            <span class="dashboard-avatar"><?php echo $first_letter; ?></span>
            Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!
        </h1>

        <a href="create_blog.php" class="btn">
            ✍️ Create New Blog
        </a>

    </div>

    <div class="dashboard-card">
        <p style="color: var(--text-secondary); font-size: 16px;">
            You are successfully logged in. Start writing and sharing your ideas with the world!
        </p>
    </div>

    <div style="margin-top: 30px; display: flex; gap: 16px; flex-wrap: wrap;">
        <a href="create_blog.php" class="btn">
            📝 Write a Blog
        </a>
        <a href="index.php" class="btn" style="background: var(--text-muted); box-shadow: none;">
            📖 View All Blogs
        </a>
        <a href="logout.php" class="btn delete-btn">
            🚪 Logout
        </a>
    </div>

</section>


<?php include 'includes/footer.php'; ?>