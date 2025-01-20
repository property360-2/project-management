<?php
// Include the database connection
// require_once '../../Back-end/Database-connector.php';
require_once 'C:/xampp/htdocs/project_management/Back-end/Database-connector.php';



// Enable exception mode for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the values from the form
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Simple password hashing for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user into the Users table
        $sql = "INSERT INTO Users (username, name, password, role) VALUES ('$username', '$name', '$hashed_password', '$role')";

        // Execute the query
        $conn->query($sql);

        // Redirect to the account-management page with success parameter
        header("Location: Account-Management.php?success=user_added");
        exit(); // Ensure no further code is executed
    }
} catch (mysqli_sql_exception $e) {
    // Handle duplicate entry or other SQL errors
    if ($e->getCode() == 1062) { // Error code for duplicate entry
        echo "Error: The username '$username' already exists. Please choose a different username.";
    } else {
        // Generic error message
        echo "An error occurred: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register New User</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin">Admin</option>
            <option value="cashier">Cashier</option>
        </select>
        <br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php
$conn->close();
?>
