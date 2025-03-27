<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long'); window.location.href='register.php';</script>";
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or email exists
        $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
        $checkStmt->bind_param("ss", $email, $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('Username or email already exists!'); window.location.href='register.php';</script>";
            exit();
        } else {
            // Insert new user with role automatically set to 'Patron'
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password, role) VALUES (?, ?, ?, ?, 'Patron')");
            $stmt->bind_param("ssss", $full_name, $email, $username, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Registration Successful! Redirecting to login page...'); window.location.href='login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='register.php';</script>";
                exit();
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | WINSP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: rgb(248, 95, 6);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: rgb(248, 95, 6);
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            color: rgb(0, 0, 0);
        }
        p {
            margin-top: 15px;
            font-size: 14px;
        }
        p a {
            color: rgb(248, 95, 6);
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
        #message {
            margin-top: 10px;
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="register.php" method="POST">
            <h2>Create an Account</h2>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password (Min. 8 characters)" required>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
