<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['user']['name']; ?>!</h1>
    <p>Email: <?php echo $_SESSION['user']['email']; ?></p>
    <a href="auth.php?action=logout">Logout</a>
</body>
</html>
