<?php

require_once 'includes/auth.php';

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}

?>


<?php include 'includes/header.php'; ?>


<section class="form-section">

    <div class="form-container">

        <h2>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["username"]); ?>!
        </h2>

        <p class="form-description">

            You are successfully logged in.

        </p>


        <a href="create_blog.php" class="btn">
            Create New Blog
        </a>


        <br><br>


        <a href="logout.php" class="btn">
            Logout
        </a>

    </div>

</section>


<?php include 'includes/footer.php'; ?>