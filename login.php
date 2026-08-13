<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";
        $message_type = "error";

    } else {

        $sql = "SELECT id, username, email, password, role
                FROM user
                WHERE email = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();


            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];


                header("Location: dashboard.php");

                exit;

            } else {

                $message = "Invalid email or password.";
                $message_type = "error";

            }

        } else {

            $message = "Invalid email or password.";
            $message_type = "error";

        }

        $stmt->close();
    }
}

?>


<?php include 'includes/header.php'; ?>


<section class="form-section">

    <div class="form-container">

        <span class="form-icon">👋</span>

        <h2>Welcome Back</h2>

        <p class="form-description">
            Login to manage your blogs.
        </p>


        <?php if (!empty($message)): ?>

            <p class="form-message <?php echo $message_type === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo $message_type === 'success' ? '✅' : '⚠️'; ?>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>


        <form action="login.php" method="POST">

            <div class="form-group">

                <label for="email">
                    📧 Email
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
                    🔒 Password
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
                🔐 Login
            </button>

        </form>


        <p style="margin-top: 24px; text-align: center; color: var(--text-muted);">

            Don't have an account?

            <a href="register.php" style="color: var(--primary); font-weight: 600;">
                Register
            </a>

        </p>

    </div>

</section>


<?php include 'includes/footer.php'; ?>