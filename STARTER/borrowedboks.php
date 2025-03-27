<?php
session_start();
include 'db.php'; // Ensure your database connection file is included

// OPTIONAL: You might want to check if the logged-in user is an admin here

// Process admin action to mark a borrowed record as returned
if (isset($_GET['action']) && $_GET['action'] === 'mark_returned' && isset($_GET['borrow_id'])) {
    $borrow_id = intval($_GET['borrow_id']);
    $updateQuery = "UPDATE borrowed_books SET status = 'returned', returned_at = NOW() WHERE id = '$borrow_id'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Marked as returned!'); window.location='borrowedboks.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error marking as returned!');</script>";
    }
}

// Fetch all borrowed book records with related book and user information
$query = "SELECT bb.*, b.title, b.author, b.cover_image, u.username, u.full_name 
          FROM borrowed_books bb
          JOIN books b ON bb.book_id = b.id
          JOIN users u ON bb.user_id = u.user_id
          ORDER BY bb.borrowed_at DESC";
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
  <title>Borrowed Books - Admin</title>
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
/* Fixed Header */
header {
  display: flex;
  align-items: center;
  justify-content: space-between; /* Ensures elements are spaced */
  background-color: rgb(247, 242, 240);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
  padding: 20px;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

/* Center H1 */
header h1 {
  margin: 0 auto; /* Centering */
  font-size: 24px;
  color: rgb(248, 95, 6);
  text-align: center;
  flex-grow: 1; /* Takes available space for centering */
}

/* Dashboard Button */
.dashboard-nav {
  margin-left: auto; /* Pushes to the right */
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
      margin: 120px auto 20px; /* Increased top margin to ensure cards appear below the fixed header */
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
      width: 100px;
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
    .actions {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .action-btn {
      padding: 8px 16px;
      background-color: black;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
    }
    .action-btn:hover {
      background-color: white;
      color: black;
      border: 2px solid black;
    }
    /* Status Badge */
    .status {
      font-size: 14px;
      font-weight: bold;
      padding: 4px 8px;
      border-radius: 4px;
    }
    .status.pending {
      background-color: rgb(248, 95, 6);
      color: white;
    }
    .status.returned {
      background-color: green;
      color: white;
    }

    a{
        text-decoration: none;
    }
  </style>
</head>
<body>
  <header>
    <div class="dashboard-nav">
      <a href="dashboard.php">Dashboard</a>
    </div>
    <h1>Borrowed Books</h1>
  </header>
  
  <div class="container">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
          <?php if (!empty($row['cover_image'])): ?>
            <img src="<?php echo $row['cover_image']; ?>" alt="<?php echo $row['title']; ?> Cover">
          <?php endif; ?>
          <div class="card-details">
            <h3><?php echo $row['title']; ?></h3>
            <p><strong>Author:</strong> <?php echo $row['author']; ?></p>
            <p><strong>Borrowed By:</strong> <?php echo $row['full_name'] . " (" . $row['username'] . ")"; ?></p>
            <p><strong>Borrowed On:</strong> <?php echo date("M d, Y", strtotime($row['borrowed_at'])); ?></p>
            <p><strong>Due Date:</strong> <?php echo date("M d, Y", strtotime($row['due_date'])); ?></p>
            <p><strong>Status:</strong> 
              <?php if ($row['status'] == 'returned'): ?>
                <span class="status returned">Returned</span>
              <?php else: ?>
                <span class="status pending">Pending</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="actions">
            <?php if ($row['status'] == 'pending'): ?>
              <a href="borrowedboks.php?action=mark_returned&borrow_id=<?php echo $row['id']; ?>" class="action-btn">Mark as Returned</a>
            <?php else: ?>
              <span class="action-btn" style="background-color: gray; cursor: default;">No Action</span>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align: center;">No borrow records found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
