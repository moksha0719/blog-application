<?php

require_once 'config/database.php';
require_once 'includes/auth.php';


// Check if user is logged in

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


// Check if blog ID exists

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php");

    exit;
}


$blog_id = (int) $_GET["id"];

$user_id = $_SESSION["user_id"];

$message = "";
$message_type = "";


// Get the blog

$sql = "SELECT id, user_id, title, content, image
        FROM blogPost
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $blog_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    echo "Blog not found.";

    exit;
}


$blog = $result->fetch_assoc();


// Check ownership

if ($blog["user_id"] != $user_id) {

    echo "You are not authorized to edit this blog.";

    exit;
}

// Check where user came from - set return destination
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

if (strpos($referer, 'index.php') !== false) {
    $_SESSION['return_to'] = 'latest-stories';
} elseif (strpos($referer, 'dashboard.php') !== false) {
    $_SESSION['return_to'] = 'dashboard';
} else {
    $_SESSION['return_to'] = 'latest-stories';
}

// Auto-crop function to 16:9 ratio
function autoCropImage($image_path) {
    $info = getimagesize($image_path);
    $width = $info[0];
    $height = $info[1];
    
    $target_ratio = 16/9;
    $current_ratio = $width / $height;
    
    if ($current_ratio > $target_ratio) {
        $new_width = $height * $target_ratio;
        $new_height = $height;
        $x = ($width - $new_width) / 2;
        $y = 0;
    } else {
        $new_width = $width;
        $new_height = $width / $target_ratio;
        $x = 0;
        $y = ($height - $new_height) / 2;
    }
    
    $ext = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
    switch($ext) {
        case 'jpg':
        case 'jpeg':
            $src = imagecreatefromjpeg($image_path);
            break;
        case 'png':
            $src = imagecreatefrompng($image_path);
            break;
        case 'gif':
            $src = imagecreatefromgif($image_path);
            break;
        case 'webp':
            $src = imagecreatefromwebp($image_path);
            break;
        default:
            return $image_path;
    }
    
    $cropped = imagecrop($src, ['x' => $x, 'y' => $y, 'width' => $new_width, 'height' => $new_height]);
    
    if ($cropped !== false) {
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($cropped, $image_path, 90);
                break;
            case 'png':
                imagepng($cropped, $image_path, 9);
                break;
            case 'gif':
                imagegif($cropped, $image_path);
                break;
            case 'webp':
                imagewebp($cropped, $image_path, 90);
                break;
        }
        imagedestroy($cropped);
    }
    imagedestroy($src);
    
    return $image_path;
}


// Handle image delete
if (isset($_POST['delete_image'])) {
    if (!empty($blog["image"]) && file_exists($blog["image"])) {
        unlink($blog["image"]);
    }
    
    $update_sql = "UPDATE blogPost SET image = NULL WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $blog_id, $user_id);
    
    if ($update_stmt->execute()) {
        $blog["image"] = NULL;
        $message = "Image deleted successfully!";
        $message_type = "success";
    }
    $update_stmt->close();
}


// Update blog

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_image'])) {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $image = $blog["image"];


    if (empty($title) || empty($content)) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } else {

        // Handle image upload with cropping
        if (isset($_POST['cropped_image']) && !empty($_POST['cropped_image'])) {
            $cropped_data = $_POST['cropped_image'];
            $cropped_data = str_replace('data:image/png;base64,', '', $cropped_data);
            $cropped_data = str_replace(' ', '+', $cropped_data);
            $image_data = base64_decode($cropped_data);
            
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $filename = time() . '_' . uniqid() . '.png';
            $filepath = $upload_dir . $filename;
            
            if (file_put_contents($filepath, $image_data)) {
                if (!empty($blog["image"]) && file_exists($blog["image"])) {
                    unlink($blog["image"]);
                }
                $image = $filepath;
            } else {
                $message = "Failed to save cropped image.";
                $message_type = "error";
            }
        }
        else if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] == 0) {
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
                        if (!empty($blog["image"]) && file_exists($blog["image"])) {
                            unlink($blog["image"]);
                        }
                        $image = autoCropImage($upload_path);
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
            $update_sql = "UPDATE blogPost
                           SET title = ?, content = ?, image = ?
                           WHERE id = ?
                           AND user_id = ?";

            $update_stmt = $conn->prepare($update_sql);

            $update_stmt->bind_param(
                "sssii",
                $title,
                $content,
                $image,
                $blog_id,
                $user_id
            );


            if ($update_stmt->execute()) {

                header("Location: view_blog.php?id=" . $blog_id);
                exit;

            } else {

                $message = "Failed to update blog.";
                $message_type = "error";

            }

            $update_stmt->close();
        }
    }
}

?>


<?php include 'includes/header.php'; ?>


<section class="edit-blog-section">

    <div class="edit-blog-container">

        <!-- Stylish Back Button - Goes to Blog View -->
        <div class="single-blog-back">
            <a href="view_blog.php?id=<?php echo $blog_id; ?>" class="btn-back-article">
                <span class="back-arrow"><i class="fas fa-arrow-left"></i></span>
                <span class="back-text">Back to Blog</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="edit-blog-header">
            <div class="edit-blog-header-left">
                <span class="edit-blog-icon"><i class="fas fa-edit"></i></span>
                <div>
                    <h1>Edit Blog</h1>
                    <p class="edit-blog-subtitle"><i class="fas fa-pen"></i> Update your blog post.</p>
                </div>
            </div>
        </div>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <div class="form-message <?php echo $message_type === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo $message_type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>'; ?>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Edit Blog Form -->
        <form action="edit_blog.php?id=<?php echo $blog_id; ?>" method="POST" class="create-blog-form" enctype="multipart/form-data" id="editBlogForm">

            <div class="form-group">
                <label for="title">
                    <i class="fas fa-heading"></i> Blog Title
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo htmlspecialchars($blog["title"]); ?>"
                    required
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label for="blog_image">
                    <i class="fas fa-image"></i> Featured Image (16:9 Recommended)
                </label>
                <?php if (!empty($blog["image"])): ?>
                    <div class="current-image">
                        <p><i class="fas fa-check-circle" style="color: var(--success);"></i> Current Image:</p>
                        <img src="<?php echo htmlspecialchars($blog["image"]); ?>" alt="Current image" class="current-image-preview">
                        <div class="image-actions">
                            <button type="submit" name="delete_image" class="btn-delete-image" onclick="return confirm('Are you sure you want to delete this image?');">
                                <i class="fas fa-trash-alt"></i> Delete Image
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 10px;">
                        <i class="fas fa-info-circle"></i> No image uploaded
                    </p>
                <?php endif; ?>
                <input
                    type="file"
                    id="blog_image"
                    name="blog_image"
                    accept="image/*"
                    class="form-input-file"
                    onchange="previewImage(event)"
                >
                <p class="file-hint"><i class="fas fa-info-circle"></i> Leave empty to keep current image. Upload new to replace. Supported: JPG, PNG, GIF, WEBP (Max 5MB)</p>
                
                <!-- Image Preview & Crop Container -->
                <div id="imagePreviewContainer" style="display: none; margin-top: 15px;">
                    <div class="image-preview-wrapper">
                        <img id="imagePreview" src="#" alt="Image preview" style="max-width: 100%;">
                    </div>
                    <div class="crop-controls" style="margin-top: 12px;">
                        <button type="button" class="btn-crop" onclick="cropImage()">
                            <i class="fas fa-crop-alt"></i> Crop Image
                        </button>
                        <button type="button" class="btn-cancel-crop" onclick="cancelCrop()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                    <div id="croppedPreviewContainer" style="display: none; margin-top: 12px;">
                        <p style="font-weight: 600; font-size: 14px;">
                            <i class="fas fa-check-circle" style="color: var(--success);"></i> Cropped Preview (16:9):
                        </p>
                        <img id="croppedPreview" style="max-width: 100%; border-radius: 8px; border: 2px solid var(--primary);">
                        <input type="hidden" id="cropped_image" name="cropped_image" value="">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="content">
                    <i class="fas fa-file-alt"></i> Blog Content
                </label>
                <textarea
                    id="content"
                    name="content"
                    rows="14"
                    required
                    class="form-textarea"
                ><?php echo htmlspecialchars($blog["content"]); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-publish">
                    <i class="fas fa-save"></i> Update Blog
                </button>
                <a href="view_blog.php?id=<?php echo $blog_id; ?>" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>

        </form>

    </div>

</section>


<script>
let cropper = null;
let croppedImageData = null;

function previewImage(event) {
    const input = event.target;
    const container = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    const croppedContainer = document.getElementById('croppedPreviewContainer');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
            croppedContainer.style.display = 'none';
            document.getElementById('cropped_image').value = '';
            croppedImageData = null;
            
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            
            preview.onload = function() {
                cropper = new Cropper(preview, {
                    aspectRatio: 16/9,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.9,
                    background: false,
                    guides: true,
                    center: true,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            };
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function cropImage() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 450,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        if (canvas) {
            const croppedPreview = document.getElementById('croppedPreview');
            croppedPreview.src = canvas.toDataURL('image/png');
            document.getElementById('croppedPreviewContainer').style.display = 'block';
            
            croppedImageData = canvas.toDataURL('image/png');
            document.getElementById('cropped_image').value = croppedImageData;
        }
    }
}

function cancelCrop() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('imagePreview').src = '';
    document.getElementById('croppedPreviewContainer').style.display = 'none';
    document.getElementById('cropped_image').value = '';
    document.getElementById('blog_image').value = '';
    croppedImageData = null;
}
</script>

<style>
.image-preview-wrapper {
    max-width: 600px;
    margin: 0 auto;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid var(--border);
}

.crop-controls {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-crop {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--primary-gradient);
    color: #FFFFFF;
    padding: 8px 24px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}

.btn-crop:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(108, 92, 231, 0.3);
}

.btn-cancel-crop {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EF4444;
    color: #FFFFFF;
    padding: 8px 24px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}

.btn-cancel-crop:hover {
    background: #DC2626;
    transform: translateY(-2px);
}

/* Cropper.js Overrides */
.cropper-view-box {
    border-radius: 8px;
}
.cropper-face {
    background-color: rgba(0,0,0,0.3);
}
</style>


<?php include 'includes/footer.php'; ?>