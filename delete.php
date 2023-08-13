<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if the post_id is provided as a query parameter
if (!isset($_GET['post_id'])) {
    header("Location: index.php");
    exit();
}

// Get the post ID from the query parameter
$postId = $_GET['post_id'];

// Retrieve the post and check if it belongs to the logged-in user
$stmt = $conn->prepare('SELECT * FROM post WHERE post_id = ? AND user_id = ?');
$stmt->bind_param('ii', $postId, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Delete the post
    $deleteStmt = $conn->prepare('DELETE FROM post WHERE post_id = ?');
    $deleteStmt->bind_param('i', $postId);
    $deleteStmt->execute();
}

// Redirect back to the dashboard
header("Location: index.php");
exit();
