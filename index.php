<?php

require_once 'config/database.php';


// Get all blog posts
$sql = "SELECT
            blogPost.id,
            blogPost.title,
            blogPost.content,
            blogPost.created_at,
            blogPost.image,
            user.username
        FROM blogPost
        INNER JOIN user
        ON blogPost.user_id = user.id
        ORDER BY blogPost.created_at DESC";

$result = $conn->query($sql);

?>


<?php include 'includes/header.php'; ?>


<!-- HERO SECTION -->
<section class="hero">

    <div class="hero-content">
        <div class="hero-text">
            <h1>Share Your Stories,<br><span>Inspire the World</span></h1>
            <p>Join thousands of writers who are sharing their ideas, experiences, and creativity on the platform built for storytellers.</p>
            <div class="hero-buttons">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <a href="create_blog.php" class="btn-hero-primary">✍️ Start Writing Free</a>
                <?php else: ?>
                    <a href="register.php" class="btn-hero-primary">🚀 Start Writing Free</a>
                    <a href="login.php" class="btn-hero-secondary">Explore Stories</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-visual">
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="dashboard.php" class="hero-image-link">
                    <div class="hero-image-placeholder">
                        <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?w=600&h=400&fit=crop&crop=center" alt="Writing community" class="hero-image-bg">
                        <div class="hero-image-overlay">
                            <span class="hero-image-icon">✍️</span>
                            <span class="hero-image-label">Go to Dashboard</span>
                        </div>
                    </div>
                </a>
            <?php else: ?>
                <a href="register.php" class="hero-image-link">
                    <div class="hero-image-placeholder">
                        <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?w=600&h=400&fit=crop&crop=center" alt="Writing community" class="hero-image-bg">
                        <div class="hero-image-overlay">
                            <span class="hero-image-icon">📝</span>
                            <span class="hero-image-label">Join Our Community</span>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>

</section>


<!-- FEATURES SECTION -->
<section class="features-section">
    <div class="section-label">
        <span class="badge">Features</span>
    </div>
    <h2 class="section-title">Everything You Need to Share Your Story</h2>
    <p class="section-subtitle">Powerful tools designed to help you write, publish, and grow your audience.</p>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">✍️</div>
            <h3>Create &amp; Write</h3>
            <p>Share your thoughts with the world through beautifully formatted blog posts.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔍</div>
            <h3>Discover Stories</h3>
            <p>Explore content from writers around the world and find new perspectives.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💬</div>
            <h3>Connect &amp; Grow</h3>
            <p>Join a community of writers and readers who share your passions.</p>
        </div>
    </div>
</section>


<!-- HOW IT WORKS -->
<section class="how-it-works" id="how-it-works">
    <div class="section-label">
        <span class="badge">How It Works</span>
    </div>
    <h2 class="section-title">Simple Steps to Start Writing</h2>
    <p class="section-subtitle">Get started in minutes with our simple 3-step process.</p>
    
    <div class="steps-grid">
        <div class="step-item">
            <div class="step-number">1</div>
            <h3>Create an Account</h3>
            <p>Sign up for free and join our community of writers.</p>
        </div>
        <div class="step-item">
            <div class="step-number">2</div>
            <h3>Write Your Story</h3>
            <p>Use our beautiful editor to craft your perfect blog post.</p>
        </div>
        <div class="step-item">
            <div class="step-number">3</div>
            <h3>Share with the World</h3>
            <p>Publish your blog and reach readers around the globe.</p>
        </div>
    </div>
</section>


<!-- BLOG SECTION -->
<section class="blog-section" id="latest-stories">

    <div class="section-heading">
        <h2>Latest Stories</h2>
        <a href="#latest-stories" class="view-all-link">View all →</a>
    </div>

    <div class="blog-grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while ($blog = $result->fetch_assoc()): ?>

                <article class="blog-card">
                    <div class="blog-card-image">
                        <?php if (!empty($blog["image"])): ?>
                            <img src="<?php echo htmlspecialchars($blog["image"]); ?>" alt="<?php echo htmlspecialchars($blog["title"]); ?>" class="blog-card-img">
                        <?php else: ?>
                            <div class="blog-card-img-placeholder">
                                <span class="placeholder-icon">📄</span>
                            </div>
                        <?php endif; ?>
                        <span class="tag">Article</span>
                    </div>
                    <div class="blog-card-body">
                        <div class="blog-card-meta">
                            <span><?php echo date("M j, Y", strtotime($blog["created_at"])); ?></span>
                            <span>•</span>
                            <span>3 min read</span>
                        </div>
                        <h3><?php echo htmlspecialchars($blog["title"]); ?></h3>
                        <p class="excerpt">
                            <?php 
                            // Remove emojis from content
                            $clean_content = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $blog["content"]);
                            $clean_content = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F700}-\x{1F77F}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F780}-\x{1F7FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F800}-\x{1F8FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F100}-\x{1F1FF}]/u', '', $clean_content);
                            
                            $clean_text = strip_tags($clean_content);
                            $clean_text = trim($clean_text);
                            
                            if (strlen($clean_text) > 120) {
                                $preview = substr($clean_text, 0, 120) . '...';
                            } else {
                                $preview = $clean_text;
                            }
                            
                            echo htmlspecialchars($preview);
                            ?>
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