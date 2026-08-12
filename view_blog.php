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

?>


<?php include 'includes/header.php'; ?>


<section class="single-blog-section">

    <div class="single-blog">


        <!-- Blog Title -->

        <h1>

            <?php

            echo htmlspecialchars($blog["title"]);

            ?>

        </h1>


        <!-- Blog Author and Date -->

        <p class="blog-info">

            By

            <?php

            echo htmlspecialchars($blog["username"]);

            ?>

            |

            <?php

            echo date(
                "F j, Y",
                strtotime($blog["created_at"])
            );

            ?>

        </p>


        <!-- Edit and Delete buttons -->

        <?php if (
            isset($_SESSION["user_id"]) &&
            $_SESSION["user_id"] == $blog["user_id"]
        ): ?>

            <div class="blog-actions">


                <!-- Edit button -->

                <a
                    href="edit_blog.php?id=<?php echo $blog["id"]; ?>"
                    class="btn"
                >
                    Edit Blog
                </a>


                <!-- Delete button -->

                <a
                    href="delete_blog.php?id=<?php echo $blog["id"]; ?>"
                    class="btn delete-btn"
                    onclick="return confirm('Are you sure you want to delete this blog?');"
                >
                    Delete Blog
                </a>


            </div>

        <?php endif; ?>


        <!-- Blog Content -->

        <div class="blog-content-full">

            <?php

            // Convert line breaks into paragraphs

            $paragraphs = preg_split(
                "/\r\n|\r|\n/",
                $blog["content"]
            );


            foreach ($paragraphs as $paragraph) {

                if (trim($paragraph) !== "") {

                    echo "<p>";

                    echo nl2br(
                        htmlspecialchars($paragraph)
                    );

                    echo "</p>";
                }

            }

            ?>

        </div>


    </div>

</section>


<?php include 'includes/footer.php'; ?>