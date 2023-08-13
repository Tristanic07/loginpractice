<?php
session_start();
require_once 'config.php';

$id = "";
$name = "";
$username = "";
$password = "";
$email = "";

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (!isset($_GET['id'])) {
        header('location: index.php');
        exit();
    }

    $id = $_SESSION['id'];

    $stmt = $conn->prepare('SELECT * FROM account WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header('location: index.php');
        exit();
    }

    $name = $row['name'];
    $username = $row['username'];
    $password = $row['password'];
    $email = $row['email'];
} else {
    $id = $_POST['id'];
    $name = ['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if ($name == "" || $username == "" || $password == "" || $email == "") {
        $errorMessage = "All Fields are Required";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    try {
        $stmt = $conn->prepare('UPDATE account SET name = ?, password = ?, email = ? WHERE id = ?');
        $stmt->bind_param('sssi', $name, $hashedPassword, $email, $id);
        $stmt->execute();
        header("location: index.php");
        exit;
    } catch (Exception $e) {
        $errorMessage = "Edit Failed: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>

<?php
if ($errorMessage != "") {
    echo "<h2>$errorMessage</h2>";
}
?>

<body>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <label for="name">Name :</label>
        <input type="text" name="name" value="<?php echo $name ?>"><br><br>
        <label for=" username">Username :</label>
        <input type="text" name="username" value="<?php echo $username ?>" readonly><br><br>
        <label for=" password">Password :</label>
        <input type="password" name="password" value="<?php echo $password ?>"><br><br>
        <label for=" email">Email :</label>
        <input type="text" name="email" value="<?php echo $email ?>"><br><br>
        <button type=" submit">Submit</button>
    </form>
    <a href="index.php">Cancel</a>
</body>

</html>