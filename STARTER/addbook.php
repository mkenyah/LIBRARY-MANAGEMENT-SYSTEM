<?php
include 'db.php';

$title = $author = $isbn = $genre = "";
$cover_image = null;
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);
    $isbn = trim($_POST["isbn"]);
    $genre = trim($_POST["genre"]);

    // Handle file upload
    if (!empty($_FILES["cover_image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $valid_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $valid_extensions)) {
            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                $cover_image = $target_file;
            } else {
                $error_message = "Error uploading the file.";
            }
        } else {
            $error_message = "Invalid file format. Only JPG, JPEG, PNG & GIF allowed.";
        }
    }

    // Insert into database
    if (empty($error_message)) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, genre, cover_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $author, $isbn, $genre, $cover_image);
        
        if ($stmt->execute()) {
            $success_message = "Book added successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->

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

.card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    width: 380px;
    text-align: center;
}

h2 {
    color: rgb(248, 95, 6);
    margin-bottom: 20px;
}

.form-group {
    text-align: left;
    margin-bottom: 15px;
}

label {
    display: block;
    font-weight: bold;
    /* color: rgb(248, 95, 6); */
}

input {
    width: 96%;
    padding: 10px;
    margin-top: 5px;
    /* border: 2px solid rgb(248, 95, 6); */
    border-radius: 5px;
    font-size: 16px;
}

button {
    width: 100%;
    padding: 12px;
    background: rgb(248, 95, 6);
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

button:hover {
    background: rgb(220, 80, 5);
}

.success {
    color: green;
    font-weight: bold;
}

.error {
    color: red;
    font-weight: bold;
}

#backbtn{
    color: rgb(220, 80, 5);
    background: rgb(255, 255, 255);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.9);
}

    </style>
</head>
<body>

<div class="card">

<a href="books.php">
    <button id="backbtn" style="margin-top: 10px;">Go to Dashboard</button>
</a>

    <h2>Add a New Book</h2>

    <?php if ($success_message) { echo "<p class='success'>$success_message</p>"; } ?>
    <?php if ($error_message) { echo "<p class='error'>$error_message</p>"; } ?>

    <form action="addbook.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Title:</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Author:</label>
            <input type="text" name="author" required>
        </div>

        <div class="form-group">
            <label>ISBN:</label>
            <input type="text" name="isbn" required>
        </div>

        <div class="form-group">
            <label>Genre:</label>
            <input type="text" name="genre">
        </div>

        <div class="form-group">
            <label>Cover Image:</label>
            <input type="file" name="cover_image" accept="image/*">
        </div>

        <button type="submit">Add Book</button>
    </form>
</div>

</body>
</html>
