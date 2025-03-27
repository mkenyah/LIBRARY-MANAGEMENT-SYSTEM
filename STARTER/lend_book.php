<?php
include 'db.php';

if (!isset($_GET['isbn'])) {
    die("Book not found.");
}

$isbn = $_GET['isbn'];

$sql = "UPDATE books SET status = 'Lent' WHERE isbn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $isbn);
$stmt->execute();

header("Location: book_dashboard.php?isbn=" . urlencode($isbn));
exit();
?>
