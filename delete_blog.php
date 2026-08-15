<?php

require_once 'config/database.php';
require_once 'includes/auth.php';


// Check if user is logged in

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


// Check blog ID

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php");

    exit;
}


$blog_id = (int) $_GET["id"];

$user_id = $_SESSION["user_id"];


// Delete only if blog belongs to logged-in user

$sql = "DELETE FROM blogPost
        WHERE id = ?
        AND user_id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $blog_id,
    $user_id
);


$stmt->execute();


if ($stmt->affected_rows > 0) {

    header("Location: index.php");

    exit;

} else {

    // Show professional error page
    ?>
    <?php include 'includes/header.php'; ?>
    
    <section style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
        <div style="text-align: center; max-width: 500px; background: #FFFFFF; padding: 50px; border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-md);">
            <div style="font-size: 64px; color: #EF4444; margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h2 style="font-size: 28px; font-weight: 800; color: var(--text-primary); margin-bottom: 12px;">
                Access Denied
            </h2>
            <p style="color: var(--text-secondary); font-size: 16px; line-height: 1.7; margin-bottom: 30px;">
                You are not authorized to delete this blog. Please contact the blog owner.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="index.php" class="btn" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-home"></i> Go to Home
                </a>
                <a href="dashboard.php" class="btn" style="display: inline-flex; align-items: center; gap: 8px; background: var(--bg); color: var(--text-primary); box-shadow: none;">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    <?php

}


$stmt->close();

?>