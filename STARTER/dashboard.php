<?php 
include("db.php");


// Check if the user is logged in
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php"); // Redirect to login page if not logged in
//     exit();
// }

// Get the logged-in user's name from session
$username = $_SESSION['username'];


$sql = "SELECT COUNT(*) AS total_books FROM books";
$result = $conn->query($sql);

// Fetch the result
$row = $result->fetch_assoc();
$total_books = $row['total_books'];



// Fetch total number of users
$sql = "SELECT COUNT(*) as total_users FROM users";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalUsers = $row['total_users'];



$sql = "SELECT COUNT(*) AS total_borrowed FROM borrowed_books";
$result = $conn->query($sql);

// Fetch result
$totalBorrowed = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalBorrowed = $row['total_borrowed'];
}

$conn->close();
?>

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library E-Book Lending System</title>
    <link rel="stylesheet" href="styls.css">
</head>
<body>
    <!-- <div class="container"> -->
    <header>

    
    <a href="homepage.php" class="dashboard-btn" id="dashboard-container">logout</a>
   
   

        <h1>Library E-Book Lending</h1>
        
      
    </header>
        
       

    <div class="dashboard-container">
        <div class="dashboard-card" onclick="location.href='books.php';">
            <h2>Books Available</h2>
            <p>Total: <?php echo $total_books; ?></p>
        </div>

        <!-- <div class="dashboard-card" onclick="location.href='requested_books.php';">
            <h2>Requested Books</h2> -->
            <!-- <p>Total: PHP code to fetch number of Requested books</p> -->
        <!-- </div> -->

        <div class="dashboard-card" onclick="location.href='users.php';">
            <h2>Users</h2>
            <p>Total: <?php echo $totalUsers; ?></p>
        </div>

        <div class="dashboard-card" onclick="location.href='add_users.php';">
            <h2>Manage Users</h2>
            <p></p>
        </div>



        <!-- <div class="dashboard-card" onclick="location.href='Fines.php';">
            <h2>Fines</h2> -->
            <!-- <p>Total: PHP code to fetch fines</p>
        </div> -->

        <div class="dashboard-card" onclick="location.href='borrowedboks.php';">
            <h2>Books Borrowed</h2>
            <p>Total: <?php echo $totalBorrowed; ?></p>
        </div>
    </div>




</body>
</html>
