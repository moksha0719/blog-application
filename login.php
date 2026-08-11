<?php include 'includes/header.php'; ?>

<section class="form-section">

    <div class="form-container">

        <h2>Login</h2>

        <p class="form-description">
            Login to manage your blogs.
        </p>

        <form action="#" method="POST">

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button type="submit" class="btn">
                Login
            </button>

        </form>


        <p class="form-footer">

            Don't have an account?

            <a href="register.php">
                Register here
            </a>

        </p>

    </div>

</section>


<?php include 'includes/footer.php'; ?>