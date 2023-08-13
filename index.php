<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
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
    <title>Welcome</title>
</head>

<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Display user-specific data here
            echo "<p>User Data: " . $row['id'] . "</p>";
        }
    } else {
        echo "<p>No user data found.</p>";
    }
    ?>

    <a href="logout.php">Log out</a>
</body>

</html>