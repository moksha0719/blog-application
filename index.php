<?php

require_once 'config/database.php';


// Get all blog posts

$sql = "SELECT
            blogPost.id,
            blogPost.title,
            blogPost.content,
            blogPost.created_at,
            user.username
        FROM blogPost
        INNER JOIN user
        ON blogPost.user_id = user.id
        ORDER BY blogPost.created_at DESC";


$result = $conn->query($sql);

?>


<?php include 'includes/header.php'; ?>


<!-- HERO - FULLY CENTERED -->
<section class="hero">
    <div class="hero-content">
        <h1>Write. <span>Share.</span> Inspire.</h1>
        <p>A simple place to share your ideas, stories and experiences with the world.</p>
        
        <div class="hero-buttons">
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="create_blog.php" class="btn-primary">✍️ Start Writing</a>
            <?php else: ?>
                <a href="register.php" class="btn-primary">🚀 Get Started</a>
                <a href="login.php" class="btn-secondary">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- FEATURES - CENTERED 3 COLUMN -->
<section class="features-section">
    <div class="features-grid">
        <div class="feature-item">
            <span class="feature-icon">✍️</span>
            <h3>Create &amp; Write</h3>
            <p>Share your thoughts with the world through beautifully formatted blog posts.</p>
        </div>
        <div class="feature-item">
            <span class="feature-icon">🔍</span>
            <h3>Discover Stories</h3>
            <p>Explore content from writers around the world and find new perspectives.</p>
        </div>
        <div class="feature-item">
            <span class="feature-icon">💬</span>
            <h3>Connect &amp; Grow</h3>
            <p>Join a community of writers and readers who share your passions.</p>
        </div>
    </div>
</section>


<!-- BLOG SECTION -->
<section class="blog-section">

    <div class="section-heading">
        <h2>Latest Stories</h2>
        <a href="#">View all →</a>
    </div>

    <div class="blog-grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while ($blog = $result->fetch_assoc()): ?>

                <article class="blog-card">
                    <div class="blog-card-image">
                        <?php 
                        $icons = ['📚', '💡', '🌟', '🎯', '🚀', '🌈', '🎨', '📝'];
                        echo $icons[array_rand($icons)];
                        ?>
                        <span class="tag">Article</span>
                    </div>
                    <div class="blog-card-body">
                        <div class="blog-card-meta">
                            <span><?php echo date("M j, Y", strtotime($blog["created_at"])); ?></span>
                            <span class="dot">•</span>
                            <span>3 min read</span>
                        </div>
                        <h3><?php echo htmlspecialchars($blog["title"]); ?></h3>
                        <p>
                            <?php 
                            $preview = substr(strip_tags($blog["content"]), 0, 140);
                            echo htmlspecialchars($preview);
                            ?>...
                        </p>
                        <div class="blog-card-footer">
                            <div class="blog-author">
                                <span class="blog-avatar">
                                    <?php echo strtoupper(substr($blog["username"], 0, 1)); ?>
                                </span>
                                <span class="blog-author-name"><?php echo htmlspecialchars($blog["username"]); ?></span>
                            </div>
                            <a href="view_blog.php?id=<?php echo $blog["id"]; ?>" class="read-link">Read →</a>
                        </div>
                    </div>
                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <p style="text-align:center; color:var(--text-muted); padding:40px 0;">
                No blogs published yet. Be the first to share your story!
            </p>

        <?php endif; ?>

    </div>

</section>


<?php include 'includes/footer.php'; ?>