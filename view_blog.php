<?php

require_once 'config/database.php';


// Check whether blog ID exists

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php");

    exit;
}


$blog_id = (int) $_GET["id"];


// Get blog and author information

$sql = "SELECT
            blogPost.id,
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


        <h1>

            <?php
            echo htmlspecialchars($blog["title"]);
            ?>

        </h1>


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