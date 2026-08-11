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


<section class="hero">

    <div class="container">

        <h1>
            Welcome to My Blog
        </h1>

        <p>
            Discover interesting stories, ideas and experiences.
        </p>


        <?php if (isset($_SESSION["user_id"])): ?>

            <a
                href="create_blog.php"
                class="btn"
            >
                Create a Blog
            </a>

        <?php else: ?>

            <a
                href="register.php"
                class="btn"
            >
                Get Started
            </a>

        <?php endif; ?>

    </div>

</section>



<section class="blogs-section">

    <div class="container">

        <h2>
            Latest Blogs
        </h2>


        <div class="blog-grid">


            <?php if ($result->num_rows > 0): ?>


                <?php while ($blog = $result->fetch_assoc()): ?>


                    <article class="blog-card">


                        <h3>

                            <?php

                            echo htmlspecialchars(
                                $blog["title"]
                            );

                            ?>

                        </h3>


                        <p class="blog-info">

                            By

                            <?php

                            echo htmlspecialchars(
                                $blog["username"]
                            );

                            ?>

                            |

                            <?php

                            echo date(
                                "F j, Y",
                                strtotime(
                                    $blog["created_at"]
                                )
                            );

                            ?>

                        </p>


                        <p>

                            <?php

                            $preview = substr(
                                strip_tags(
                                    $blog["content"]
                                ),
                                0,
                                150
                            );

                            echo htmlspecialchars(
                                $preview
                            );

                            ?>

                            <?php

                            if (
                                strlen(
                                    strip_tags(
                                        $blog["content"]
                                    )
                                ) > 150
                            ) {

                                echo "...";

                            }

                            ?>

                        </p>


                        <a
                            href="view_blog.php?id=<?php echo $blog["id"]; ?>"
                            class="read-more"
                        >
                            Read More →
                        </a>


                    </article>


                <?php endwhile; ?>


            <?php else: ?>


                <p class="empty-message">
        No blogs have been published yet.
    </p>


            <?php endif; ?>


        </div>

    </div>

</section>


<?php include 'includes/footer.php'; ?>