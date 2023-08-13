<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
// Handle user post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postContent = $_POST['post_content'];

    // Ensure the content is not empty
    if (!empty($postContent)) {
        // Insert the post into the database
        $stmt = $conn->prepare('INSERT INTO post (user_id, content, timestamp) VALUES (?, ?, NOW())');
        $stmt->bind_param('is', $_SESSION['id'], $postContent);
        $stmt->execute();
        // Redirect to refresh the page after the post is submitted
        header("Location: index.php");
        exit();
    }
}

// Fetch and display user-specific data from the database
$userId = $_SESSION['id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare('SELECT * FROM account WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();

$result = $stmt->get_result();

// Display user data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Display user-specific data here
            echo "<p>User Data: " . $row['name'] . "</p>";
            echo "<p>User Data: " . $row['id'] . "</p>";
            echo "<p>User Data: " . $row['password'] . "</p>";
            echo "<p>User Data: " . $row['email'] . "</p>";
            echo "<p>User Data: " . $row['username'] . "</p>";
        }
    } else {
        echo "<p>No user data found.</p>";
    }
    ?>

    <h1>POST:</h1>
    <?php
    $postQuery = $conn->prepare('SELECT * FROM post WHERE user_id = ? ORDER BY timestamp DESC');
    $postQuery->bind_param('i', $userId);
    $postQuery->execute();
    $postResult = $postQuery->get_result();

    if ($postResult->num_rows > 0) {
        while ($postRow = $postResult->fetch_assoc()) {
            echo "<p>Post: " . $postRow['content'] . " " . "<a href='delete.php?post_id={$postRow['post_id']}'>Delete</a></p>";
        }
    } else {
        echo "<p>No posts found.</p>";
    }
    ?>

    <form method="post">
        <input type="text" name="post_content" placeholder="Write your post...">
        <button type="submit">Post</button>
    </form>

    <a href="logout.php">Log out</a>
    <a href="edit.php?id=$row['id']">Edit</a>
</body>

</html>