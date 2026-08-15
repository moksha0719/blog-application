<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];
$first_letter = strtoupper(substr($username, 0, 1));

// CHANGE blogPost TO blogpost
$blog_count_sql = "SELECT COUNT(*) as total FROM blogpost WHERE user_id = ?";
$stmt = $conn->prepare($blog_count_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$blog_count_result = $stmt->get_result();
$blog_count = $blog_count_result->fetch_assoc()['total'];

// CHANGE blogPost TO blogpost
$recent_sql = "SELECT id, title, created_at FROM blogpost WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($recent_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_blogs = $stmt->get_result();

include __DIR__ . '/includes/header.php';
?>

<section class="dashboard-section">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="dashboard-header-left">
            <div class="dashboard-avatar-large">
                <?php echo $first_letter; ?>
            </div>
            <div>
                <h1><i class="fas fa-wave-square"></i> Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                <p class="dashboard-subtitle"><i class="fas fa-chart-line"></i> Here's what's happening with your blogs</p>
            </div>
        </div>
        <a href="create_blog.php" class="btn-dashboard-primary">
            <i class="fas fa-plus-circle"></i> Create New Blog
        </a>
    </div>

    <!-- Welcome Card -->
    <div class="dashboard-welcome">
        <div class="welcome-icon"><i class="fas fa-celebrate"></i></div>
        <div class="welcome-content">
            <h3>You're all set!</h3>
            <p>You are successfully logged in. Start writing and sharing your ideas with the world!</p>
        </div>
    </div>

    <!-- Recent Blogs -->
    <div class="dashboard-recent">
        <div class="recent-header">
            <h3><i class="fas fa-history"></i> Your Recent Blogs</h3>
        </div>
        
        <?php if ($recent_blogs->num_rows > 0): ?>
            <div class="recent-list">
                <?php while ($blog = $recent_blogs->fetch_assoc()): ?>
                    <div class="recent-item">
                        <div class="recent-item-left">
                            <span class="recent-item-icon"><i class="fas fa-file-alt"></i></span>
                            <div>
                                <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="recent-item-title">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </a>
                                <span class="recent-item-date">
                                    <i class="far fa-calendar-alt"></i> <?php echo date("M j, Y", strtotime($blog['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="recent-item-actions">
                            <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" class="btn-sm btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="btn-sm btn-view"><i class="fas fa-eye"></i> View</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <span class="empty-icon"><i class="fas fa-file-alt" style="font-size: 48px;"></i></span>
                <p>You haven't written any blogs yet.</p>
                <a href="create_blog.php" class="btn-empty"><i class="fas fa-plus-circle"></i> Create Your First Blog</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-actions">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="actions-grid">
            <a href="create_blog.php" class="action-card">
                <span class="action-icon"><i class="fas fa-pen"></i></span>
                <span class="action-label">Write a Blog</span>
            </a>
            <a href="index.php#latest-stories" class="action-card">
                <span class="action-icon"><i class="fas fa-book-open"></i></span>
                <span class="action-label">View All Blogs</span>
            </a>
            <a href="logout.php" class="action-card action-logout">
                <span class="action-icon"><i class="fas fa-sign-out-alt"></i></span>
                <span class="action-label">Logout</span>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>