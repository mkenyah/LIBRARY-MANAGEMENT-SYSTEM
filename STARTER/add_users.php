<?php 
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $insert_query = "INSERT INTO users (full_name, email, username, password, role) VALUES ('$full_name', '$email', '$username', '$password', '$role')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('User added successfully!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Error adding user!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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
        .container {
            width: 40%;
            margin: 50px auto;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            position: relative;
        }
        h2 {
            text-align: center;
            color: rgb(248, 95, 6);
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 90%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            width: 96%;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }
        .save-btn {
            background-color: rgb(248, 95, 6);
            color: white;
            border: none;
        }
        .save-btn:hover {
            background-color: white;
            color: rgb(248, 95, 6);
            border: 2px solid rgb(248, 95, 6);
        }
        .cancel-btn {
            background-color: black;
            color: white;
            border: none;
        }
        .cancel-btn:hover {
            background-color: white;
            color: black;
            border: 2px solid black;
        }
        .dashboard-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
        }
        .dashboard-btn:hover {
            background-color: white;
            color: black;
            border: 2px solid black;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- <a href="dashboard.php" class="dashboard-btn">Back to Dashboard</a> -->
        <h2>Add New User</h2>
        <form action="add_users.php" method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" required>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="username">Username</label>
            <input type="text" name="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="role">Role</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="Patron">Patron</option>
            </select>

            <button type="submit" class="btn save-btn">Add User</button>
            <a href="users.php" class="btn cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>
