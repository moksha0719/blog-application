<?php

require_once 'config/database.php';
require_once 'includes/auth.php';


// Check whether blog ID exists
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$blog_id = (int) $_GET["id"];

// Get blog and author information
$sql = "SELECT
            blogPost.id,
            blogPost.user_id,
            blogPost.title,
            blogPost.content,
            blogPost.created_at,
            blogPost.updated_at,
            blogPost.image,
            user.username
        FROM blogPost
        INNER JOIN user
        ON blogPost.user_id = user.id
        WHERE blogPost.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if blog exists
if ($result->num_rows == 0) {
    echo "Blog not found.";
    exit;
}

$blog = $result->fetch_assoc();

// Function to remove emojis from text
function removeEmojis($text) {
    $text = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text);
    $text = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $text);
    $text = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $text);
    $text = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $text);
    $text = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $text);
    $text = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $text);
    $text = preg_replace('/[\x{1F700}-\x{1F77F}]/u', '', $text);
    $text = preg_replace('/[\x{1F780}-\x{1F7FF}]/u', '', $text);
    $text = preg_replace('/[\x{1F800}-\x{1F8FF}]/u', '', $text);
    $text = preg_replace('/[\x{1F100}-\x{1F1FF}]/u', '', $text);
    return $text;
}

// Get the referring page URL
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
if (strpos($referer, 'dashboard.php') !== false) {
    $back_url = 'dashboard.php';
} elseif (strpos($referer, 'index.php') !== false) {
    $back_url = 'index.php#latest-stories';
} else {
    $back_url = 'index.php';
}

?>

<?php include 'includes/header.php'; ?>


<section class="single-blog-section">

    <div class="single-blog">

        <!-- Stylish Back Button -->
        <div class="single-blog-back">
            <a href="<?php echo $back_url; ?>" class="btn-back-article">
                <span class="back-arrow">←</span>
                <span class="back-text">Back</span>
            </a>
        </div>

        <div class="single-blog-header">
            <span class="tag">📝 Article</span>
            <h1><?php echo htmlspecialchars($blog["title"]); ?></h1>
            <div class="single-blog-meta">
                <div class="author">
                    <span class="avatar">
                        <?php echo strtoupper(substr($blog["username"], 0, 1)); ?>
                    </span>
                    <span class="name"><?php echo htmlspecialchars($blog["username"]); ?></span>
                </div>
                <span class="date"><?php echo date("F j, Y", strtotime($blog["created_at"])); ?></span>
                <span class="date">• 3 min read</span>
            </div>
        </div>

        <!-- Blog Image -->
        <div class="single-blog-image">
            <?php if (!empty($blog["image"])): ?>
                <img src="<?php echo htmlspecialchars($blog["image"]); ?>" alt="<?php echo htmlspecialchars($blog["title"]); ?>" class="blog-featured-image">
            <?php else: ?>
                <div class="image-placeholder">
                    📚
                </div>
            <?php endif; ?>
        </div>

        <div class="single-blog-content">
            <?php
            // Convert line breaks into paragraphs - REMOVE EMOJIS
            $clean_content = removeEmojis($blog["content"]);
            $paragraphs = preg_split("/\r\n|\r|\n/", $clean_content);

            foreach ($paragraphs as $paragraph) {
                if (trim($paragraph) !== "") {
                    echo "<p>" . nl2br(htmlspecialchars($paragraph)) . "</p>";
                }
            }
            ?>
            
            <!-- Edit & Delete Buttons - AT THE BOTTOM -->
            <?php if (
                isset($_SESSION["user_id"]) &&
                $_SESSION["user_id"] == $blog["user_id"]
            ): ?>
                <div class="blog-actions">
                    <a href="edit_blog.php?id=<?php echo $blog["id"]; ?>" class="btn">
                        ✏️ Edit
                    </a>
                    <a href="delete_blog.php?id=<?php echo $blog["id"]; ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this blog?');">
                        🗑️ Delete
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>

</section>


<?php include 'includes/footer.php'; ?>