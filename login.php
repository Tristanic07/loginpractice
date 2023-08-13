<?php
session_start();
require_once 'config.php';

$emailusername = "";
$password = "";

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $emailusername = $_POST['emailusername'];
    $password = $_POST['password'];

    if ($emailusername == "" || $password == "") {
        $errorMessage = "Fill up all field!!!";
    } else {
        $stmt = $conn->prepare('SELECT * FROM account WHERE username = ? OR email = ? ');
        $stmt->bind_param('ss', $emailusername,  $emailusername);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Compare the stored password with the provided password
            if (password_verify($password, $storedPassword)) {
                // Password is correct, proceed with authentication
                $_SESSION['login'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                echo "Login successful. Redirecting...";
                header("Location: index.php"); // Redirect to dashboard or another page
                exit();
            } else {
                $errorMessage = "Invalid username or password!";
            }
        } else {
            $errorMessage = "User not found!";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
</head>

<body>
    <?php
    if ($errorMessage != "") {
        echo "<h2>$errorMessage</h2>";
    }
    ?>
    <form method="post">
        <label for="emailusername">Username or Email :</label>
        <input type="text" name="emailusername"><br><br>
        <label for="password">Password :</label>
        <input type="password" name="password"><br><br>
        <button type="submit">Log-in</button>
    </form>

    <a href="register.php">Register</a>
</body>

</html>