<?php
require_once '../../Back-end/Database-connector.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <style>
        .success {
            color: green;
            margin-bottom: 15px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
        }

        .btn-primary {
            background-color: blue;
            color: white;
        }

        .btn-danger {
            background-color: red;
            color: white;
        }

        .btn-sm {
            font-size: 0.9em;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }

        .modal-header,
        .modal-footer {
            text-align: center;
        }

        .modal-footer button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }

        .modal-footer .cancel-btn {
            background-color: gray;
            color: white;
        }

        .modal-footer .confirm-btn {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Account Management</h2>

        <!-- Success message  -->
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
                // Fetch data from the database
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);
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
                    echo "<tr><td colspan='5'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for confirmation -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Deletion</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
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
            window.location.href = 'delete.php?id=' + userIdToDelete;
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>