<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

// Get all blog posts - CHANGE blogPost TO blogpost
$sql = "SELECT
            blogpost.id,
            blogpost.title,
            blogpost.content,
            blogpost.created_at,
            blogpost.image,
            user.username
        FROM blogpost
        INNER JOIN user
        ON blogpost.user_id = user.id
        ORDER BY blogpost.created_at DESC";

$result = $conn->query($sql);

// Get counts for stats - CHANGE blogPost TO blogpost
$count_sql = "SELECT COUNT(*) as total FROM blogpost";
$count_result = $conn->query($count_sql);
$total_blogs = $count_result->fetch_assoc()['total'];

$user_sql = "SELECT COUNT(*) as total FROM user";
$user_result = $conn->query($user_sql);
$total_users = $user_result->fetch_assoc()['total'];

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Share Your Stories,<br><span>Inspire the World</span></h1>
            <p>Join thousands of writers who are sharing their ideas, experiences, and creativity on the platform built for storytellers.</p>
            <div class="hero-buttons">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <a href="create_blog.php" class="btn-hero-primary"><i class="fas fa-pen-fancy"></i> Start Writing Free</a>
                <?php else: ?>
                    <a href="register.php" class="btn-hero-primary"><i class="fas fa-rocket"></i> Start Writing Free</a>
                    <a href="login.php" class="btn-hero-secondary"><i class="fas fa-compass"></i> Explore Stories</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-visual">
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="dashboard.php" class="hero-image-link">
                    <div class="hero-image-placeholder">
                        <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?w=600&h=400&fit=crop&crop=center" alt="Writing community" class="hero-image-bg">
                        <div class="hero-image-overlay">
                            <span class="hero-image-icon"><i class="fas fa-pencil-alt"></i></span>
                            <span class="hero-image-label">Go to Dashboard</span>
                        </div>
                    </div>
                </a>
            <?php else: ?>
                <a href="register.php" class="hero-image-link">
                    <div class="hero-image-placeholder">
                        <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?w=600&h=400&fit=crop&crop=center" alt="Writing community" class="hero-image-bg">
                        <div class="hero-image-overlay">
                            <span class="hero-image-icon"><i class="fas fa-users"></i></span>
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
        <span class="badge"><i class="fas fa-star"></i> Features</span>
    </div>
    <h2 class="section-title">Everything You Need to Share Your Story</h2>
    <p class="section-subtitle">Powerful tools designed to help you write, publish, and grow your audience.</p>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-pen-nib"></i></div>
            <h3>Create &amp; Write</h3>
            <p>Share your thoughts with the world through beautifully formatted blog posts.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-search"></i></div>
            <h3>Discover Stories</h3>
            <p>Explore content from writers around the world and find new perspectives.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-comments"></i></div>
            <h3>Connect &amp; Grow</h3>
            <p>Join a community of writers and readers who share your passions.</p>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works" id="how-it-works">
    <div class="section-label">
        <span class="badge"><i class="fas fa-lightbulb"></i> How It Works</span>
    </div>
    <h2 class="section-title">Simple Steps to Start Writing</h2>
    <p class="section-subtitle">Get started in minutes with our simple 3-step process.</p>
    
    <div class="steps-grid">
        <div class="step-item">
            <div class="step-number">1</div>
            <h3><i class="fas fa-user-plus"></i> Create an Account</h3>
            <p>Sign up for free and join our community of writers.</p>
        </div>
        <div class="step-item">
            <div class="step-number">2</div>
            <h3><i class="fas fa-feather"></i> Write Your Story</h3>
            <p>Use our beautiful editor to craft your perfect blog post.</p>
        </div>
        <div class="step-item">
            <div class="step-number">3</div>
            <h3><i class="fas fa-globe"></i> Share with the World</h3>
            <p>Publish your blog and reach readers around the globe.</p>
        </div>
    </div>
</section>

<!-- BLOG SECTION -->
<section class="blog-section" id="latest-stories">
    <div class="section-heading">
        <h2><i class="fas fa-newspaper"></i> Latest Stories</h2>
        <a href="#latest-stories" class="view-all-link">View all <i class="fas fa-arrow-right"></i></a>
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
                                <span class="placeholder-icon"><i class="fas fa-file-alt"></i></span>
                            </div>
                        <?php endif; ?>
                        <span class="tag"><i class="fas fa-bookmark"></i> Article</span>
                    </div>
                    <div class="blog-card-body">
                        <div class="blog-card-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo date("M j, Y", strtotime($blog["created_at"])); ?></span>
                            <span><i class="far fa-clock"></i> 3 min read</span>
                        </div>
                        <h3><?php echo htmlspecialchars($blog["title"]); ?></h3>
                        <p class="excerpt">
                            <?php 
                            $clean_content = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $blog["content"]);
                            $clean_content = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $clean_content);
                            $clean_content = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $clean_content);
                            
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
                                <span class="blog-author-name"><i class="fas fa-user"></i> <?php echo htmlspecialchars($blog["username"]); ?></span>
                            </div>
                            <a href="view_blog.php?id=<?php echo $blog["id"]; ?>" class="read-link">Read <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:var(--text-muted); padding:40px 0;">
                <i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 16px;"></i>
                No blogs published yet. Be the first to share your story!
            </p>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>