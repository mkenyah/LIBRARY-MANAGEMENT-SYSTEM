<?php
session_start();
include 'db.php'; // Ensure your database connection file is included

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Process return action if provided
if (isset($_GET['action']) && $_GET['action'] === 'return' && isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    $returnQuery = "DELETE FROM borrowed_books WHERE book_id = '$book_id' AND user_id = '$user_id'";
    if (mysqli_query($conn, $returnQuery)) {
        echo "<script>alert('Book returned successfully!'); window.location='checks.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error returning book!');</script>";
    }
}

// Fetch the books borrowed by the current user by joining borrowed_books and books
$query = "SELECT b.title, b.author, b.cover_image, b.edition_key, b.isbn, bb.borrowed_at, bb.due_date, bb.book_id 
          FROM borrowed_books bb
          JOIN books b ON bb.book_id = b.id
          WHERE bb.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Borrowed Books</title>

  <style>
  
    /* Global Styles */
    body {
      font-family: Arial, sans-serif;
      background-color: whitesmoke;
      margin: 0;
      padding: 0;
      color: #333;
    }
    /* Fixed Header */
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: rgb(247, 242, 240);
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
      padding: 20px;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
    }
    header h1 {
      flex: 1;
      text-align: center;
      margin: 0;
      font-size: 24px;
      color: rgb(248, 95, 6);
    }
    .dashboard-nav {
      display: flex;
      justify-content: flex-end;
      margin-right: 40px;
    }
    .dashboard-nav a {
      padding: 10px 20px;
      background-color: black;
      color: white;
      border-radius: 5px;
      font-weight: bold;
      transition: 0.3s;
    }
    .dashboard-nav a:hover {
      background-color: white;
      color: black;
      border: 2px solid black;
    }
    /* Main Container */
    .container {
      width: 90%;
      margin: 100px auto 20px; /* Top margin added to account for fixed header */
    }
    /* Card Styles */
    .card {
      background: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .card img {
      width: 120px;
      height: auto;
      border-radius: 5px;
    }
    .card-details {
      flex: 1;
    }
    .card-details h3 {
      margin: 0;
      color: rgb(248, 95, 6);
    }
    .card-details p {
      margin: 5px 0;
      font-size: 14px;
    }
    .return-btn, .read-btn {
      padding: 10px 20px;
      background-color: black;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 5px;
      display: block;
      text-align: center;
    }
    .return-btn:hover, .read-btn:hover {
      background-color: white;
      color: black;
      border: 2px solid black;
    }
    .actions {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    a{
        text-decoration: none;
    }
    
  </style>
</head>
<body>
  <header>
    <h1>Your Borrowed Books</h1>
    <div class="dashboard-nav">
      <a href="user_dashboard.php">Dashboard</a>
    </div>
  </header>
  
  <div class="container">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)):
        // Generate read link: if edition_key exists, use that; otherwise, fall back to ISBN.
        if (!empty($row['edition_key'])) {
          $readLink = "https://openlibrary.org/books/" . $row['edition_key'];
        } elseif (!empty($row['isbn'])) {
          $readLink = "https://openlibrary.org/isbn/" . $row['isbn'];
        } else {
          $readLink = "";
        }
      ?>
        <div class="card">
          <?php if (!empty($row['cover_image'])): ?>
            <img src="<?php echo $row['cover_image']; ?>" alt="<?php echo $row['title']; ?> Cover">
          <?php endif; ?>
          <div class="card-details">
            <h3><?php echo $row['title']; ?></h3>
            <p><strong>Author:</strong> <?php echo $row['author']; ?></p>
            <p><strong>Borrowed On:</strong> <?php echo date("M d, Y", strtotime($row['borrowed_at'])); ?></p>
            <p><strong>Due Date:</strong> <?php echo date("M d, Y", strtotime($row['due_date'])); ?></p>
          </div>
          <div class="actions">
            <a href="checks.php?action=return&book_id=<?php echo $row['book_id']; ?>" class="return-btn">Return</a>
            <?php if (!empty($readLink)): ?>
              <a href="<?php echo $readLink; ?>" target="_blank" class="read-btn">Read</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align: center;">You have not borrowed any books.</p>
    <?php endif; ?>
  </div>
</body>
</html>
