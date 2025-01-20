<?php
require_once 'C:/xampp/htdocs/project_management/Back-end/Database-connector.php';

// Check if a search term is set
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$rows_per_page = isset($_GET['rows']) ? (int)$_GET['rows'] : 5; // Default to 5 rows per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1

// Prepare the base SQL query
$sql = "SELECT * FROM users WHERE (username LIKE ? OR name LIKE ?)";
$search_term_with_wildcards = "%" . $search_term . "%";
$params = ["ss", $search_term_with_wildcards, $search_term_with_wildcards]; // Initial parameters

// Apply role filter if specified
if (!empty($role_filter)) {
    $sql .= " AND role = ?";
    $params[0] .= "s"; // Add string type for binding
    $params[] = $role_filter; // Add role to parameters
}

// Handle pagination
if ($rows_per_page != -1) {
    $offset = ($current_page - 1) * $rows_per_page;
    $sql .= " LIMIT ?, ?";
    $params[0] .= "ii"; // Add integer types for binding
    $params[] = $offset;
    $params[] = $rows_per_page;
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters dynamically
$stmt->bind_param(...$params);

// Execute and fetch results
$stmt->execute();
$result = $stmt->get_result();
$total_users = $result->num_rows;

// Calculate total pages
$total_pages = $rows_per_page == -1 ? 1 : ceil($total_users / $rows_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link rel="stylesheet" href="/Front-end/styles/Accounts/account-management.css?v=1.0">
</head>

<body>
    <div class="container">
        <h2>Account Management</h2>

        <!-- Search form -->
        <form action="Account-Management.php" method="get">
            <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search_term); ?>" />
            <button type="submit">Search</button>
        </form>

        <!-- Filters for role and rows per page -->
        <form action="Account-Management.php" method="get">
            <label for="role">Role:</label>
            <select name="role" id="role">
                <option value="">All</option>
                <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="cashier" <?php echo $role_filter === 'cashier' ? 'selected' : ''; ?>>Cashier</option>
            </select>

            <label for="rows">Rows per page:</label>
            <select name="rows" id="rows">
                <option value="5" <?php echo $rows_per_page == 5 ? 'selected' : ''; ?>>5</option>
                <option value="10" <?php echo $rows_per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="15" <?php echo $rows_per_page == 15 ? 'selected' : ''; ?>>15</option>
                <option value="20" <?php echo $rows_per_page == 20 ? 'selected' : ''; ?>>20</option>
                <option value="-1" <?php echo $rows_per_page == -1 ? 'selected' : ''; ?>>Show All</option>
            </select>

            <button type="submit">Apply</button>
            <button type="submit" name="reset" value="true">Reset</button>
        </form>

        <!-- Success message -->
        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] == 'user_added'): ?>
                <p class="success">User added successfully!</p>
            <?php elseif ($_GET['success'] == 'user_updated'): ?>
                <p class="success">User updated successfully!</p>
            <?php elseif ($_GET['success'] == 'user_deleted'): ?>
                <p class="success">User deleted successfully!</p>
            <?php endif; ?>
        <?php endif; ?>

        <a href="add-user.php" class="btn btn-primary" role="button">New Client</a>
        <br><br>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from the database with pagination and role filter
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                            <tr>
                                <td>{$row['user_id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['role']}</td>
                                <td>
                                    <a href='edit.php?id={$row['user_id']}' class='btn btn-primary btn-sm' role='button'>Edit</a>
                                    <a href='javascript:void(0);' onclick='showModal({$row['user_id']})' class='btn btn-danger btn-sm' role='button'>Delete</a>
                                </td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='6'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div>
            <p>Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></p>
            <?php if ($current_page > 1): ?>
                <a href="?page=<?php echo $current_page - 1; ?>&search=<?php echo $search_term; ?>&role=<?php echo $role_filter; ?>&rows=<?php echo $rows_per_page; ?>">Previous</a>
            <?php endif; ?>
            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?php echo $current_page + 1; ?>&search=<?php echo $search_term; ?>&role=<?php echo $role_filter; ?>&rows=<?php echo $rows_per_page; ?>">Next</a>
            <?php endif; ?>
        </div>

    </div>

    <!-- Modal for confirmation -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Deletion</h3>
            </div>
            <div class="modal-body">
                <p>Confirm Delete?</p>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button class="confirm-btn" onclick="deleteUser()">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        var userIdToDelete;

        function showModal(userId) {
            userIdToDelete = userId;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function deleteUser() {
            window.location.href = 'delete-user.php?id=' + userIdToDelete;
        }
    </script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
