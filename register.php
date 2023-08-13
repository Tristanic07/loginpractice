<?php
require_once 'config.php';

$name = "";
$username = "";
$password = "";
$email = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
}

if ($name == "" || $username == "" || $password == "" || $email == "") {
    $errorMessage = "All Fields are Required";
} else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare('INSERT INTO account(name, username, password, email) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $name, $username, $hashedPassword, $email);
        $stmt->execute();
        $stmt->close();



        echo "<script>alert('Created Successfully!!');</script>";
    } catch (Exception $e) {
        $errorMessage = "Registration Failed: " . $e->getMessage();
    }


    $name = "";
    $email = "";
    $phone = "";
    $address = "";
}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>

<body>
    <h1>Registration Form</h1>
    <?php
    if ($errorMessage != "") {
        echo "<h2>$errorMessage</h2>";
    } else if ($successMessage != "") {
        echo "<h2>$successMessage</h2>";
    }
    ?>
    <form method="post">
        <label for="name">Name :</label>
        <input type="text" name="name"><br><br>
        <label for="username">Username :</label>
        <input type="text" name="username"><br><br>
        <label for="password">Password :</label>
        <input type="password" name="password"><br><br>
        <label for="email">Email :</label>
        <input type="text" name="email"><br><br>
        <button type="submit">Submit</button>
    </form>

    <a href="login.php">Log-in</a>
</body>

</html>