<?php
session_start();
include 'db.php'; // Include your database connection file

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ==========================================
   OPTIONAL: Fetch Books from API if DB is Empty
   ========================================== */
$countQuery = "SELECT COUNT(*) as total FROM books";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);

if ($countRow['total'] == 0) {
    // No books in DB; fetch from Open Library API
    $query = urlencode("subject:fiction");
    $url = "https://openlibrary.org/search.json?q={$query}&limit=10";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['docs']) && count($data['docs']) > 0) {
        foreach ($data['docs'] as $doc) {
            $title  = isset($doc['title']) ? mysqli_real_escape_string($conn, $doc['title']) : 'Unknown Title';
            $author = isset($doc['author_name'][0]) ? mysqli_real_escape_string($conn, $doc['author_name'][0]) : 'Unknown Author';
            $isbn   = isset($doc['isbn'][0]) ? mysqli_real_escape_string($conn, $doc['isbn'][0]) : '';
            $genre  = isset($doc['subject'][0]) ? mysqli_real_escape_string($conn, $doc['subject'][0]) : 'Fiction';
            if (isset($doc['cover_i'])) {
                $cover_image = "https://covers.openlibrary.org/b/id/" . $doc['cover_i'] . "-L.jpg";
            } else {
                $cover_image = ''; // Fallback if no cover
            }
            // Fetch the first edition key if available
            $edition_key = isset($doc['edition_key'][0]) ? mysqli_real_escape_string($conn, $doc['edition_key'][0]) : '';
            
            // Insert the book into the database, including the edition key
            $insertQuery = "INSERT INTO books (title, author, isbn, genre, cover_image, edition_key) 
                            VALUES ('$title', '$author', '$isbn', '$genre', '$cover_image', '$edition_key')";
            mysqli_query($conn, $insertQuery);
        }
    }
}

/* ==========================================
   Process Borrow/Return Actions
   ========================================== */
if (isset($_GET['action']) && isset($_GET['book_id'])) {
    $action = $_GET['action'];
    $book_id = intval($_GET['book_id']);
    
    if ($action === 'borrow') {
        // Check if the book is already borrowed
        $checkQuery = "SELECT * FROM borrowed_books WHERE book_id = '$book_id'";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) == 0) {
            // Insert the borrow record with due_date 7 days from now
            $borrowQuery = "INSERT INTO borrowed_books (book_id, user_id, due_date) VALUES ('$book_id', '$user_id', DATE_ADD(NOW(), INTERVAL 7 DAY))";
            if (mysqli_query($conn, $borrowQuery)) {
                echo "<script>alert('Book borrowed successfully!'); window.location='user_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error borrowing book!');</script>";
            }
        } else {
            echo "<script>alert('Book is already borrowed!'); window.location='user_dashboard.php';</script>";
        }
    } elseif ($action === 'return') {
        // Delete the borrow record if it belongs to the current user
        $returnQuery = "DELETE FROM borrowed_books WHERE book_id = '$book_id' AND user_id = '$user_id'";
        if (mysqli_query($conn, $returnQuery)) {
            echo "<script>alert('Book returned successfully!'); window.location='user_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error returning book!');</script>";
        }
    }
}

/* ==========================================
   Process Search and Fetch Books with Borrow Status
   ========================================== */
$search = "";
$whereClause = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $whereClause = "WHERE b.title LIKE '%$search%' OR b.author LIKE '%$search%'";
}

// LEFT JOIN borrowed_books to see if the book is borrowed and by whom
$booksQuery = "SELECT b.*, bb.user_id as borrowed_by 
               FROM books b 
               LEFT JOIN borrowed_books bb ON b.id = bb.book_id 
               $whereClause";
$booksResult = mysqli_query($conn, $booksQuery);
if (!$booksResult) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Library</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: whitesmoke;
            margin: 0;
            padding: 0;
            color: #333;
        }
        a {
            text-decoration: none;
        }
        /* Header */
        .header {
            background-color: rgb(248, 95, 6);
            color: white;
            padding: 15px;
            text-align: center;
        }
        /* Container */
        .container {
            width: 90%;
            margin: 20px auto;
        }
        /* Search Bar */
        .search-bar {
            text-align: center;
            margin-top: 180px;
        }
        .search-bar input[type="text"] {
            width: 40%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-bar button:hover {
            background-color: white;
            color: black;
            border: 2px solid black;
        }
        /* Books Grid */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .book-card {
            margin: 30px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .book-card:hover {
            transform: translateY(-5px);
        }
        .book-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .book-card h3 {
            color: rgb(248, 95, 6);
            margin-top: 10px;
        }
        .book-card p {
            font-size: 14px;
            line-height: 1.6;
        }
        .actions {
            margin-top: 10px;
        }
        .actions a {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px 3px 0 0;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            transition: all 0.3s ease;
        }
        /* Borrow/Return Button */
        .borrow-btn {
            background-color: rgb(248, 95, 6);
            color: white;
            border: 2px solid rgb(248, 95, 6);
        }
        .borrow-btn:hover {
            background-color: white;
            color: rgb(248, 95, 6);
            border: 2px solid rgb(248, 95, 6);
        }
        .return-btn {
            background-color: black;
            color: white;
            border: 2px solid black;
        }
        .return-btn:hover {
            background-color: white;
            color: black;
            border: 2px solid black;
        }
        /* Download/Read Button */
        .download-btn, .read-btn {
            background-color: black;
            color: white;
            border: 2px solid black;
        }
        .download-btn:hover, .read-btn:hover {
            background-color: white;
            color: black;
            border: 2px solid black;
        }
        /* Dashboard Navigation */
        .dashboard-nav {
  display: flex;
  justify-content: end;
  margin-right: 40px;
  padding: 10px 0;
  gap: 10px;
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


        header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: rgb(248, 95, 6);
      color: white;
      padding: 15px 20px;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
    }
   
    </style>
</head>
<body>

<header>
    
        <h1>User Dashboard</h1>
    
    
   
    <div class="container">
        <div class="dashboard-nav">
        <a href="homepage.php">log out</a>
            <a href="checks.php">checks</a>
        </div>
        </header>
        
        <div class="search-bar">
            <form action="user_dashboard.php" method="GET">
                <input type="text" name="search" placeholder="Search for a book..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="books-grid">
            <?php while ($book = mysqli_fetch_assoc($booksResult)) { 
                // Generate real read/download links using the edition_key if available,
                // otherwise fallback to using ISBN (which may not always work)
                if (!empty($book['edition_key'])) {
                    $readLink = "https://openlibrary.org/books/" . $book['edition_key'];
                    $downloadLink = "https://openlibrary.org/books/" . $book['edition_key'] . "/download";
                } else {
                    $readLink = !empty($book['isbn']) ? "https://openlibrary.org/isbn/" . $book['isbn'] : '';
                    $downloadLink = !empty($book['isbn']) ? "https://openlibrary.org/isbn/" . $book['isbn'] . "/download" : '';
                }
            ?>
                <div class="book-card">
                    <?php if (!empty($book['cover_image'])) { ?>
                        <img src="<?php echo $book['cover_image']; ?>" alt="<?php echo $book['title']; ?> Cover">
                    <?php } ?>
                    <h3><?php echo $book['title']; ?></h3>
                    <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                    <p><strong>Genre:</strong> <?php echo $book['genre']; ?></p>
                    <p><strong>ISBN:</strong> <?php echo $book['isbn']; ?></p>
                    <div class="actions">
                        <?php if (is_null($book['borrowed_by'])) { ?>
                            <a href="user_dashboard.php?action=borrow&book_id=<?php echo $book['id']; ?>" class="borrow-btn">Borrow</a>
                        <?php } elseif ($book['borrowed_by'] == $user_id) { ?>
                            <a href="user_dashboard.php?action=return&book_id=<?php echo $book['id']; ?>" class="return-btn">Return</a>
                        <?php } else { ?>
                            <span style="color: red; font-weight:bold;">Not Available</span>
                        <?php } ?>
                        <?php if (!empty($readLink)) { ?>
                            <!-- <a href="<?php echo $downloadLink; ?>" class="download-btn" download>Download</a> -->
                            <a href="<?php echo $readLink; ?>" target="_blank" class="read-btn">Read</a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
