<?php
include 'db.php';

if (!isset($_GET['isbn'])) {
    die("Book not found.");
}

$isbn = $_GET['isbn'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];

    $sql = "UPDATE books SET title = ?, author = ?, genre = ? WHERE isbn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $author, $genre, $isbn);
    $stmt->execute();

    header("Location: book_dashboard.php?isbn=" . urlencode($isbn));
    exit();
}

$sql = "SELECT * FROM books WHERE isbn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>


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
            width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: rgb(248, 95, 6);
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
        }

        input {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            background-color: rgb(248, 95, 6);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: white;
            color: rgb(248, 95, 6);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .dashboard-btn {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            padding: 10px 15px;
            color: white;
            background-color: rgb(248, 95, 6);
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .dashboard-btn:hover {
            background-color: white;
            color: rgb(248, 95, 6);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.9);
        }
    </style>

</head>
<body>

<div class="card">
    <h2>Edit Book</h2>
    <form method="post">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required><br>
        
        <label>Author:</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required><br>
        
        <label>Genre:</label>
        <input type="text" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" required><br>
        
        <button type="submit">Update Book</button>
    </form>

    </div>
</body>
</html>
