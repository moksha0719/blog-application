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


// Get the blog

$sql = "SELECT id, user_id, title, content
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


// Update blog

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);


    if (empty($title) || empty($content)) {

        $message = "Please fill in all fields.";

    } else {

        $update_sql = "UPDATE blogPost
                       SET title = ?, content = ?
                       WHERE id = ?
                       AND user_id = ?";

        $update_stmt = $conn->prepare($update_sql);

        $update_stmt->bind_param(
            "ssii",
            $title,
            $content,
            $blog_id,
            $user_id
        );


        if ($update_stmt->execute()) {

            header(
                "Location: view_blog.php?id=" . $blog_id
            );

            exit;

        } else {

            $message = "Failed to update blog.";

        }

        $update_stmt->close();
    }
}

?>


<?php include 'includes/header.php'; ?>


<section class="form-section">

    <div class="form-container">

        <h2>Edit Blog</h2>

        <p class="form-description">
            Update your blog post.
        </p>


        <?php if (!empty($message)): ?>

            <p class="form-message error-message">

                <?php echo htmlspecialchars($message); ?>

            </p>

        <?php endif; ?>


        <form
            action="edit_blog.php?id=<?php echo $blog_id; ?>"
            method="POST"
        >


            <div class="form-group">

                <label for="title">
                    Blog Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo htmlspecialchars($blog["title"]); ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="content">
                    Blog Content
                </label>

                <textarea
                    id="content"
                    name="content"
                    rows="12"
                    required
                ><?php echo htmlspecialchars($blog["content"]); ?></textarea>

            </div>


            <button
                type="submit"
                class="btn"
            >
                Update Blog
            </button>


        </form>

    </div>

</section>


<?php include 'includes/footer.php'; ?>