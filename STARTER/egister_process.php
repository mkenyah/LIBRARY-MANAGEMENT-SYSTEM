<?php
include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strlen($password) < 8) {
        $message = "Password must be at least 8 characters long.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or email exists
        $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
        $checkStmt->bind_param("ss", $email, $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message = "Username or email already exists!";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $username, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
}

$conn->close();
?>