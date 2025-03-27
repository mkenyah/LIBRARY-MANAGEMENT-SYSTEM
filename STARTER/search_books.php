<?php
include 'db.php';

if (isset($_POST['search']) && isset($_POST['type'])) {
    // Get search parameters and escape them for security
    $search = $conn->real_escape_string($_POST['search']);
    $type   = $conn->real_escape_string($_POST['type']);

    // Build query: Note: For production, consider using prepared statements!
    $sql = "SELECT title, author, isbn, genre, cover_image FROM books WHERE $type LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Use the stored cover_image value; if empty, use a default image.
            $coverImage = !empty($row["cover_image"]) ? $row["cover_image"] : "uploads/default_cover.jpg";

            // Ensure that the image path includes the "uploads/" prefix if needed.
            if (!str_starts_with($coverImage, 'uploads/')) {
                $coverImage = "uploads/" . $coverImage;
            }

            // Output a clickable card with the cover image and book details.
            echo "<a href='book_dashboard.php?isbn=" . urlencode($row['isbn']) . "' class='book-link'>
                    <div class='book-card'>
                        <img src='" . htmlspecialchars($coverImage) . "' alt='Book Cover' class='book-cover'>
                        <h2 class='book-title'>" . htmlspecialchars($row['title']) . "</h2>
                        <p class='book-author'>Author: " . htmlspecialchars($row['author']) . "</p>
                        <p class='book-isbn'>ISBN: " . htmlspecialchars($row['isbn']) . "</p>
                        <p class='book-genre'>Genre: " . htmlspecialchars($row['genre']) . "</p>
                    </div>
                  </a>";
        }
    } else {
        echo "<p>No books found.</p>";
    }
}

$conn->close();
?>
