<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'register') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            $_SESSION['user'] = ['name' => $name, 'email' => $email];
            header('Location: dashboard.php');
        } else {
            echo "Registration failed!";
        }

    } elseif ($action == 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = ['name' => $user['name'], 'email' => $user['email']];
            header('Location: dashboard.php');
        } else {
            echo "Login failed!";
        }

    }
}

if ($_GET['action'] == 'logout') {
    session_destroy();
    header('Location: login.html');
}
?>
