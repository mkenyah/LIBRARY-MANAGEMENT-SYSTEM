<?php
include 'db.php';

if (!isset($_GET['isbn'])) {
    die("Book not found.");
}

$isbn = $_GET['isbn'];

$sql = "DELETE FROM books WHERE isbn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $isbn);
$stmt->execute();

header("Location: books.php");
exit();
?>
