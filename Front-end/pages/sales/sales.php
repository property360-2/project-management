<?php
// Database connection
require_once 'C:/xampp/htdocs/project_management/Back-end/Database-connector.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier UI</title>
    <style>
        /* Styling for the tab buttons */
        .tab-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 5px;
        }

        .tab-btn.active {
            background-color: #0056b3;
        }

        .tab-content {
            display: none;
            padding: 15px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .tab-content.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .btn-danger {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <h2>Cashier UI</h2>

    <!-- Tab Buttons -->
    <div>
        <button class="tab-btn active" data-tab="current-sale">Current Sale</button>
        <button class="tab-btn" data-tab="sales-history">Sales History</button>
    </div>

    <!-- Tab Content: Current Sale -->
    <div id="current-sale" class="tab-content active">
        <h3>Current Sale</h3>
        <a href="create-Sale.php" class="btn btn-primary" role="button">Create Sale</a>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price per Unit</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM Inventory";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                            <tr>
                                <td>{$row['name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>{$row['price']}</td>
                                <td>" . ($row['price'] * $row['quantity']) . "</td>
                                <td><button class='btn btn-danger'>Remove</button></td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Tab Content: Sales History -->
    <div id="sales-history" class="tab-content">
        <h3>Sales History</h3>
        <table>
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>User ID</th>
                    <th>Sale Date</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM Sales";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                            <tr>
                                <td>{$row['sale_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['sale_date']}</td>
                                <td>{$row['total_amount']}</td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='4'>No sales found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript for tab switching
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all tabs and buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to the clicked button and its corresponding content
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
