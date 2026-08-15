<?php

require_once 'config/database.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username) || empty($email) || empty($password)) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } else {

        $check_sql = "SELECT id FROM user WHERE email = ?";

        $check_stmt = $conn->prepare($check_sql);

        $check_stmt->bind_param("s", $email);

        $check_stmt->execute();

        $check_stmt->store_result();


        if ($check_stmt->num_rows > 0) {

            $message = "Email already exists.";
            $message_type = "error";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = "user";


            $sql = "INSERT INTO user
                    (username, email, password, role)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssss",
                $username,
                $email,
                $hashed_password,
                $role
            );


            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";
                $message_type = "success";

            } else {

                $message = "Registration failed. Please try again.";
                $message_type = "error";

            }

            $stmt->close();
        }

        $check_stmt->close();
    }
}

?>


<?php include 'includes/header.php'; ?>


<section class="form-section">

    <div class="form-container">

        <span class="form-icon"><i class="fas fa-user-plus"></i></span>

        <h2>Create Account</h2>

        <p class="form-description">
            Register to start creating your own blogs.
        </p>


        <?php if (!empty($message)): ?>

            <p class="form-message <?php echo $message_type === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo $message_type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>'; ?>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>


        <form action="register.php" method="POST">

            <div class="form-group">

                <label for="username">
                    <i class="fas fa-user"></i> Username
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
                    <i class="fas fa-envelope"></i> Email
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
                    <i class="fas fa-lock"></i> Password
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
                    <i class="fas fa-check-double"></i> Confirm Password
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
                <i class="fas fa-user-plus"></i> Create Account
            </button>

        </form>


        <p style="margin-top: 24px; text-align: center; color: var(--text-muted);">

            Already have an account?

            <a href="login.php" style="color: var(--primary); font-weight: 600;">
                Login here
            </a>

        </p>

    </div>

</section>


<?php include 'includes/footer.php'; ?>