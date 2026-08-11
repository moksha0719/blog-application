<?php include 'includes/header.php'; ?>

<section class="form-section">

    <div class="form-container">

        <h2>Create Account</h2>

        <p class="form-description">
            Register to start creating your own blogs.
        </p>


        <form action="#" method="POST">

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    required
                >

            </div>


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
                    placeholder="Create a password"
                    required
                >

            </div>


            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    required
                >

            </div>


            <button type="submit" class="btn">
                Register
            </button>

        </form>


        <p class="form-footer">

            Already have an account?

            <a href="login.php">
                Login here
            </a>

        </p>

    </div>

</section>


<?php include 'includes/footer.php'; ?>