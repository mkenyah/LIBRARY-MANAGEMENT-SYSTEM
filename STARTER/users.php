<?php
session_start();
include 'db.php'; // Ensure you have a database connection file

// Fetch all users from the database
$query = "SELECT user_id, full_name, email, username, created_at, role FROM users";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $delete_query = "DELETE FROM users WHERE user_id = '$user_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('User deleted successfully!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- <link rel="stylesheet" href="styles.css"> Link to external CSS file -->
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: whitesmoke;
            color: black;
            text-align: center;
            margin: 0;
            padding: 0;
        }


        
header {
    background-color: rgb(247, 242, 240);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
    text-align: center;
    padding: 1px 0;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
}
        h2 {
            background-color: rgb(248, 95, 6);
            color: white;
            padding: 15px;
            margin: 0;
        }
        table {
            width: 80%;
            margin: 150px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgb(248, 95, 6);
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
    display: inline-block;
    padding: 8px 15px;
    margin: 3px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease-in-out;
    border: none;
    cursor: pointer;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
}

.edit-btn {
    background-color: black;
    color: white;
    border: 2px solid black;
}

.edit-btn:hover {
    background-color: white;
    color: black;
    border: 2px solid black;
    box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
}

.delete-btn {
    background-color: rgb(248, 95, 6);
    color: white;
    border: 2px solid rgb(248, 95, 6);
}

.delete-btn:hover {
    background-color: white;
    color: rgb(248, 95, 6);
    border: 2px solid rgb(248, 95, 6);
    box-shadow: 0px 0px 8px rgba(248, 95, 6, 0.5);
}


.dashboard-container {
    display: flex;
    justify-content: end; /* Center horizontally */
    align-items: center; /* Center vertically if needed */
    
}

.dashboard-btn {
    display: inline-block;
    padding: 12px 24px;
    font-size: 18px;
    font-weight: bold;
    color:   rgb(230, 139, 3);
    background-color:white;
    /* background: linear-gradient(45deg,rgb(230, 139, 3),rgb(0, 1, 2)); Gradient blue */
    border: none;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.9);
}

.dashboard-btn:hover {
    color:  white ;
    background-color:rgb(230, 139, 3);
    transform: scale(1.05); /* Slight zoom effect */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}


    </style>
</head>
<body>




<header>
<div class="dashboard-container">
    <!-- <a href="addbook.php" class="dashboard-btn" id="adb">Add books</a> -->
    <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
   
</div>
        <h1>Users</h1>
        
      
    </header>
    <!-- <h2>Users</h2> -->
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                    <a href="editusers.php?id=<?php echo $row['user_id']; ?>" class="action-btn edit-btn">Edit</a>
                        <a href="users.php?delete=<?php echo $row['user_id']; ?>" class="action-btn delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this user?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
