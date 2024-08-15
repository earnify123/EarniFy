<?php
// Start session
session_start();

// Database connection
$servername = "localhost";  // Update as needed
$username = "root";         // Update as needed
$password = "";             // Update as needed
$dbname = "earnify";        // Update as needed

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Handle user registration
if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Registration successful! Please log in.";
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle user login
if (isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['points'] = $user['points'];
            $_SESSION['success'] = "Login successful!";
        } else {
            $_SESSION['error'] = "Invalid password.";
        }
    } else {
        $_SESSION['error'] = "No user found with that username.";
    }
}

// Handle user logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: user.php");
    exit();
}

// Display registration, login, and user info
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <?php
    // Display error or success messages
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<p class='success'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    }

    // Check if user is logged in
    if (isset($_SESSION['username'])) {
        echo "<p>Welcome, " . $_SESSION['username'] . "! You have " . $_SESSION['points'] . " points.</p>";
        echo '<a href="user.php?logout=1">Logout</a>';
    } else {
        // Registration form
        echo '<h2>Register</h2>';
        echo '<form action="user.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Register</button>
              </form>';
        
        // Login form
        echo '<h2>Login</h2>';
        echo '<form action="user.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
              </form>';
    }
    ?>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
