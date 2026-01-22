<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit & View Users</title>
    <link rel="stylesheet" href="managerView.css">
</head>
<body>
    <div class="container">
        <h1>User Management System</h1>
        <?php require_once '../db.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // update user role
            if (isset($_POST['action']) && $_POST['action'] === 'update') {
                $userId=$conn->real_escape_string($_POST['user_id']);
                $newRole= $conn->real_escape_string($_POST['user_role']);
                $sql = "UPDATE User SET userRole = '$newRole' WHERE UserID = $userId";
                if ($conn->query($sql) === TRUE)
                    echo '<div class="message success">User role updated successfully</div>';
                else
                    echo '<div class="message error">Error updating user: ' . htmlspecialchars($conn->error) . '</div>';
            }
            
            // delete user from db completely
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $userId= $conn->real_escape_string($_POST['user_id']);
                $sql="DELETE FROM User WHERE UserID = $userId";
                
                if ($conn->query($sql) === TRUE)
                    echo '<div class="message success">User deleted successfully</div>';
                else
                    echo '<div class="message error">Error deleting user: ' . htmlspecialchars($conn->error) . '</div>';
            }
        }
        
        // get all users from the db table -> display thm
        $sql="SELECT * FROM User ORDER BY UserID";
        $result =$conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>User ID</th>';
            echo '<th>First Name</th>';
            echo '<th>Last Name</th>';
            echo '<th>Email</th>';
            echo '<th>Phone</th>';
            echo '<th>Address</th>';
            echo '<th>Date of Birth</th>';
            echo '<th>Role</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            while ($user =$result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($user['UserID']) . '</td>';
                echo '<td>' . htmlspecialchars($user['firstName']) .'</td>';
                echo '<td>' . htmlspecialchars($user['lastName']) . '</td>';
                echo '<td>' . htmlspecialchars($user['email']). '</td>';
                echo '<td>' . htmlspecialchars($user['phoneNum']) . '</td>';
                echo '<td>' . htmlspecialchars($user['address']) .'</td>';
                echo '<td>' . htmlspecialchars($user['dob']) . '</td>';
                echo '<td>';
                echo '<form method="POST" style="display: inline;">';
                echo '<select name="user_role">';
                echo '<option value="Employee"'. ($user['userRole'] === 'Employee' ? ' selected' : '') . '>Employee</option>';
                echo '<option value="Adopter"' . ($user['userRole'] === 'Adopter' ? ' selected' : '') . '>Adopter</option>';
                echo '</select>';
                echo '</td>';
                echo '<td>';
                echo '<input type="hidden" name="user_id" value="'. $user['UserID'] . '">';
                echo '<input type="hidden" name="action" value="update">';
                echo '<button type="submit" class="btn-update">Update</button>';
                echo '</form>';
                echo '<form method="POST" style="display: inline;" onsubmit="return confirm(\'Are you sure you want to delete this user?\');">';
                echo '<input type="hidden" name="user_id" value="' .$user['UserID'] . '">';
                echo '<input type="hidden" name="action" value="delete">';
                echo '<button type="submit" class="btn-delete">Delete</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';

        } 
        
        else        // user table was empty
            echo '<div class="no-users">No users were found in the database.</div>';
        
        // Close connection
        $conn->close();
        ?>
    </div>
    <button class = "standard" onclick = "window.location.href = 'managerView.php'">Return back to Manager View</button>
</body>
</html>