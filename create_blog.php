<?php

require_once 'config/database.php';
require_once 'includes/auth.php';


// Check whether user is logged in

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


$message = "";
$message_type = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $image = "";

    $user_id = $_SESSION["user_id"];


    // Validate fields

    if (empty($title) || empty($content)) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } else {

        // Handle image upload
        if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['blog_image']['name'];
            $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filesize = $_FILES['blog_image']['size'];
            
            if (in_array($filetype, $allowed)) {
                if ($filesize <= 5000000) {
                    $upload_dir = 'uploads/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $new_filename = time() . '_' . uniqid() . '.' . $filetype;
                    $upload_path = $upload_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['blog_image']['tmp_name'], $upload_path)) {
                        $image = $upload_path;
                    } else {
                        $message = "Failed to upload image.";
                        $message_type = "error";
                    }
                } else {
                    $message = "Image size must be less than 5MB.";
                    $message_type = "error";
                }
            } else {
                $message = "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
                $message_type = "error";
            }
        }

        if (empty($message)) {
            $sql = "INSERT INTO blogPost
                    (user_id, title, content, image)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "isss",
                $user_id,
                $title,
                $content,
                $image
            );


            if ($stmt->execute()) {

                $blog_id = $stmt->insert_id;
                header("Location: view_blog.php?id=" . $blog_id);
                exit;

            } else {

                $message = "Failed to create blog.";
                $message_type = "error";

            }

            $stmt->close();
        }
    }
}

?>


<?php include 'includes/header.php'; ?>


<section class="create-blog-section">

    <div class="create-blog-container">

        <!-- Page Header -->
        <div class="create-blog-header">
            <div class="create-blog-header-left">
                <span class="create-blog-icon"><i class="fas fa-pen-fancy"></i></span>
                <div>
                    <h1>Create New Blog</h1>
                    <p class="create-blog-subtitle">Share your thoughts with the world.</p>
                </div>
            </div>
            <a href="dashboard.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <div class="form-message <?php echo $message_type === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo $message_type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>'; ?>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Create Blog Form -->
        <form action="create_blog.php" method="POST" class="create-blog-form" enctype="multipart/form-data">

            <div class="form-group">
                <label for="title">
                    <i class="fas fa-heading"></i> Blog Title
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Enter blog title"
                    required
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label for="blog_image">
                    <i class="fas fa-image"></i> Featured Image
                </label>
                <input
                    type="file"
                    id="blog_image"
                    name="blog_image"
                    accept="image/*"
                    class="form-input-file"
                >
                <p class="file-hint"><i class="fas fa-info-circle"></i> Supported formats: JPG, PNG, GIF, WEBP (Max 5MB)</p>
            </div>

            <div class="form-group">
                <label for="content">
                    <i class="fas fa-file-alt"></i> Blog Content
                </label>
                <textarea
                    id="content"
                    name="content"
                    rows="14"
                    placeholder="Write your blog here..."
                    required
                    class="form-textarea"
                ></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-publish">
                    <i class="fas fa-paper-plane"></i> Publish Blog
                </button>
                <a href="dashboard.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>

        </form>

    </div>

</section>


<?php include 'includes/footer.php'; ?>