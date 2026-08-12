<?php

require_once 'config/database.php';
require_once 'includes/auth.php';


// Check if user is logged in

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


// Check blog ID

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php");

    exit;
}


$blog_id = (int) $_GET["id"];

$user_id = $_SESSION["user_id"];


// Delete only if blog belongs to logged-in user

$sql = "DELETE FROM blogPost
        WHERE id = ?
        AND user_id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $blog_id,
    $user_id
);


$stmt->execute();


if ($stmt->affected_rows > 0) {

    header("Location: index.php");

    exit;

} else {

    echo "You are not authorized to delete this blog.";

}


$stmt->close();

?>