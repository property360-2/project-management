<?php
// Include the database connection
require_once '../../Back-end/Database-connector.php';

// Enable exception mode for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    try {
        // SQL query to delete the user
        $sql = "DELETE FROM users WHERE user_id = $user_id";
        
        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Redirect to the account management page with a success message
            header("Location: Account-Management.php?success=user_deleted");
            exit(); // Ensure no further code is executed
        } else {
            // In case of failure, show an error message
            echo "Error: Unable to delete user.";
        }
    } catch (mysqli_sql_exception $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If no ID is provided, redirect back to the account management page
    header("Location: Account-Management.php");
    exit();
}

$conn->close();
?>
