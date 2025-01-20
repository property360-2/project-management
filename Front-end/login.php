<?php
// require_once '../../Back-end/Database-connector.php';
require_once 'C:/xampp/htdocs/project_management/Back-end/Database-connector.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the inputs are empty
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Prepare the SQL query
    $query = "SELECT * FROM Users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Redirect based on user role
            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header("Location: http://localhost/project_management/Front-end/navigation-for-admin.php");
                exit;
            } else {
                header("Location: http://localhost/project_management/Front-end/pages/sales/sales.php");
                exit;
            }
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Login">
    </form>
</body>

</html>