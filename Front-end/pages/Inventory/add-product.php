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
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        // SQL query to insert the new product into the Inventory table
        $sql = "INSERT INTO Inventory (name, description, price, quantity) VALUES ('$name', '$description', '$price', '$quantity')";

        // Execute the query
        $conn->query($sql);

        // Redirect to the inventory management page with success parameter
        header("Location: Inventory-Management.php?success=product_added");
        exit(); // Ensure no further code is executed
    }
} catch (mysqli_sql_exception $e) {
    // Handle SQL errors
    echo "An error occurred: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
</head>
<body>
    <h1>Add New Product</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>
        <br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <br><br>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <br><br>

        <input type="submit" value="Add Product">
    </form>
</body>
</html>

<?php
$conn->close();
?>
