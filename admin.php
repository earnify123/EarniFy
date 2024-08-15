<?php
// Start session
session_start();

// Check if the user is logged in and has admin privileges (implement your own admin check)
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    die("Access denied. Please log in as an admin.");
}

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

// Fetch all users
$sql = "SELECT username, email, points FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['points']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="user.php">Back to User Page</a>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
