<?php
include 'db.php';

if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    
    // Fetch book details using a prepared statement for security
    $sql = "SELECT * FROM books WHERE isbn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
    
    if (!$book) {
        echo "Book not found.";
        exit;
    }
} else {
    echo "No book selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Dashboard</title>
  <style>
    /* Basic styling */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    
    .container {
      width: 60%;
      margin: 50px auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: left;
    }
    
    h1 {
      text-align: center;
      color: rgb(248, 95, 6);
    }
    
    .book-info {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-top: 20px;
    }
    
    .book-cover {
      max-width: 150px;
      border-radius: 5px;
    }
    
    .book-details h2 {
      margin: 0;
      color: rgb(248, 95, 6);
    }
    
    .book-details p {
      margin: 5px 0;
      font-size: 0.9em;
      color: #666;
    }
    
    .actions {
      margin-top: 20px;
      text-align: center;
    }
    
    .actions a {
      display: inline-block;
      padding: 10px 20px;
      margin: 5px;
      color: white;
      background-color: rgb(248, 95, 6);
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
    }
    
    .actions a:hover {
      background-color: white;
      color: rgb(248, 95, 6);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.9);
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Book Details</h1>
  
  <div class="book-info">
    <?php
      // Get the cover image value; if empty, use a default image.
      $coverImage = !empty($book['cover_image']) ? $book['cover_image'] : 'default_cover.jpg';
      
      // Ensure the image path includes 'uploads/' if not already present.
      if (!str_starts_with($coverImage, 'uploads/')) {
          $coverImage = "uploads/" . $coverImage;
      }
    ?>
    <img src="<?php echo htmlspecialchars($coverImage); ?>" alt="Book Cover" class="book-cover">
    <div class="book-details">
      <h2><?php echo htmlspecialchars($book['title']); ?></h2>
      <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
      <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
      <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
      <p><strong>Status:</strong> <?php echo !empty($book) ? 'Available' : 'Not Available'; ?></p>
    </div>
  </div>
  
  <div class="actions">
    <a href="edit_book.php?isbn=<?php echo urlencode($book['isbn']); ?>">Edit Book</a>
    <a href="delete_book.php?isbn=<?php echo urlencode($book['isbn']); ?>" onclick="return confirm('Are you sure?')">Delete Book</a>
    <a href="lend_book.php?isbn=<?php echo urlencode($book['isbn']); ?>">Lend Book</a>
    <a href="books.php">Back to Dashboard</a>
  </div>
</div>

</body>
</html>
