<?php
// Include the database connection
require_once '../../Back-end/Database-connector.php';

// Check if the user ID is provided in the URL
if (!isset($_GET['id'])) {
    die("Error: User ID not provided.");
}

$user_id = $_GET['id'];

// Fetch the user data from the database
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Error: User not found.");
}

$user = $result->fetch_assoc();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Update the password only if a new one is provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET name = '$name', username = '$username', password = '$hashed_password', role = '$role' WHERE user_id = $user_id";
    } else {
        $update_sql = "UPDATE users SET name = '$name', username = '$username', role = '$role' WHERE user_id = $user_id";
    }

    // Update the user in the database
    if ($conn->query($update_sql)) {
        // Redirect to the account management page with a success message
        header("Location: Account-Management.php?success=user_updated");
        exit();
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>

<body>
    <h1>Edit User</h1>
    <form action="edit.php?id=<?php echo $user_id; ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
        <br><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        <br><br>

        <label for="password">Password (leave blank to keep current):</label>
        <input type="password" id="password" name="password">
        <br><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="cashier" <?php if ($user['role'] == 'cashier') echo 'selected'; ?>>Cashier</option>
        </select>
        <br><br>

        <input type="submit" value="Update">
        <a href="Account-Management.php">Cancel</a>
    </form>
</body>

</html>

<?php
$conn->close();
?>