<?php

require_once 'config/database.php';
require_once 'includes/auth.php';


// Check whether user is logged in

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    $user_id = $_SESSION["user_id"];


    // Validate fields

    if (empty($title) || empty($content)) {

        $message = "Please fill in all fields.";

    } else {

        // Insert blog into database

        $sql = "INSERT INTO blogPost
                (user_id, title, content)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iss",
            $user_id,
            $title,
            $content
        );


        if ($stmt->execute()) {

            // Get the newly created blog ID

            $blog_id = $stmt->insert_id;

            header(
                "Location: view_blog.php?id=" . $blog_id
            );

            exit;

        } else {

            $message = "Failed to create blog.";

        }

        $stmt->close();
    }
}

?>


<?php include 'includes/header.php'; ?>


<section class="form-section">

    <div class="form-container">

        <h2>Create New Blog</h2>

        <p class="form-description">
            Share your thoughts with the world.
        </p>


        <?php if (!empty($message)): ?>

            <p class="form-message error-message">

                <?php echo htmlspecialchars($message); ?>

            </p>

        <?php endif; ?>


        <form
            action="create_blog.php"
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
                    placeholder="Enter blog title"
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
                    placeholder="Write your blog here..."
                    required
                ></textarea>

            </div>


            <button
                type="submit"
                class="btn"
            >
                Publish Blog
            </button>


        </form>

    </div>

</section>


<?php include 'includes/footer.php'; ?>