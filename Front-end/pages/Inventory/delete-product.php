<?php
// Include the database connection
// require_once '../../Back-end/Database-connector.php';
require_once 'C:/xampp/htdocs/project_management/Back-end/Database-connector.php';

// Enable exception mode for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if a product ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        // SQL query to delete the product
        $sql = "DELETE FROM Inventory WHERE product_id = $product_id";
        
        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Redirect to the inventory management page with a success message
            header("Location: Inventory-Management.php?success=product_deleted");
            exit(); // Ensure no further code is executed
        } else {
            // In case of failure, show an error message
            echo "Error: Unable to delete product.";
        }
    } catch (mysqli_sql_exception $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If no ID is provided, redirect back to the inventory management page
    header("Location: Inventory-Management.php");
    exit();
}

$conn->close();
?>
