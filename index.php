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

    <h1>
        Write. Share. Inspire.
    </h1>

    <p>
        A simple place to share your ideas,
        stories and experiences with the world.
    </p>


    <?php if (isset($_SESSION["user_id"])): ?>

        <a
            href="create_blog.php"
            class="hero-button"
        >
            + Create a Blog
        </a>

    <?php else: ?>

        <a
            href="register.php"
            class="hero-button"
        >
            Get Started
        </a>

    <?php endif; ?>

</section>



<section class="blog-section">


    <div class="section-heading">

        <h2>
            Latest Stories
        </h2>

    </div>


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

                        •

                        <?php

                        echo date(
                            "M j, Y",
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
                            180
                        );

                        echo htmlspecialchars(
                            $preview
                        );

                        ?>

                        ...

                    </p>


                    <a
                        href="view_blog.php?id=<?php echo $blog["id"]; ?>"
                        class="read-more"
                    >
                        Read article →
                    </a>


                </article>


            <?php endwhile; ?>


        <?php else: ?>


            <p class="empty-message">

                No blogs have been published yet.

            </p>


        <?php endif; ?>


    </div>


</section>


<?php include 'includes/footer.php'; ?>