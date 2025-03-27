<?php 
ob_start();  // Optional: start output buffering
session_start();
include("db.php");

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the user exists
    $stmt = $conn->prepare("SELECT user_id, full_name, password, role FROM users WHERE username = ?");
    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $full_name, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $hashed_password)) {
            // Normalize the role value to lowercase
            $role = strtolower(trim($role));

            $_SESSION['user_id'] = $user_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = $role;

            // Redirect based on role
            if ($role === 'admin') {
                header("Location: dashboard.php");
                exit();
            } elseif ($role === 'patron') {
                header("Location: user_dashboard.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}

$conn->close();
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - eBook Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            color: rgb(248, 95, 6);
        }
        a {
            color: rgb(248, 95, 6);
        }
        p {
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 350px;
        }
        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
    </style>
</head>
<body>
    <div class="container">
        <form action="login.php" method="POST">
            <h2>Login</h2>
            <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</body>
</html>
