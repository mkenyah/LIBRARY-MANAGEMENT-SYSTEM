<?php
include 'db.php';

$sql = "SELECT title, author, isbn, genre, cover_image FROM books";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* styles.css - CSS for styling the books display */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }



        header {
    background-color: rgb(247, 242, 240);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
    text-align: center;
    padding: 4px 0;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
}


.search-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
    width: 40%;
    max-width: 600px;
        }
        #search-results {
            position: absolute;
            width: 100%;
            background: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }


        .search-container select,
        .search-container input {
            padding: 8px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    position: relative;
    left: 180px;

}

.search-container input {
    flex-grow: 1;
}

        .list-group-item {
            cursor: pointer;
            transition: 0.3s;
        }
        .list-group-item:hover {
            background-color: #007bff;
            color: white;
        }
        .container {
            width: 80%;
            margin: 190px auto;
        }
        h1 {
            text-align: center;
            color: rgb(248, 95, 6);
        }
        .books-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .book-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px;
            padding: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .book-card:hover {
            transform: scale(1.05);
        }
        .book-cover {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .book-title {
            font-size: 1.2em;
            color: #333;
            margin: 0;
        }
        .book-author, .book-genre, .book-isbn {
            font-size: 0.9em;
            color: #666;
            margin: 5px 0;
        }




        .dashboard-container {
    display: flex;
    justify-content: end; /* Center horizontally */
    align-items: center; /* Center vertically if needed */
    
}

.dashboard-btn {
    display: inline-block;
    padding: 12px 24px;
    font-size: 18px;
    font-weight: bold;
    color:   rgb(230, 139, 3);
    background-color:white;
    /* background: linear-gradient(45deg,rgb(230, 139, 3),rgb(0, 1, 2)); Gradient blue */
    border: none;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.9);
}

.dashboard-btn:hover {
    color:  white ;
    background-color:rgb(230, 139, 3);
    transform: scale(1.05); /* Slight zoom effect */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

a{
    text-decoration: none
}

.book-link {
    text-decoration: none;
    color: inherit;
}
.book-link:hover .book-card {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

#adb{
position: relative;
right: 40px;
}


.book-cover {
    width: 100px; /* Set a fixed width */
    height: 150px; /* Set a fixed height */
    object-fit: cover; /* Ensures the image fits within the dimensions */
    display: block;
    margin: 0 auto; /* Centers the image */
    border-bottom: 1px solid #ddd;
    margin-bottom: 10px;
}



    </style>
</head>
<body>
    <div class="container">

    <header>

    <div class="dashboard-container">
    <a href="addbook.php" class="dashboard-btn" id="adb">Add books</a>
    <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
   
</div>
  

    <h1>Available Books</h1>
    <div class="search-container">
        <select id="search-type" class="form-select">
            <option value="title">Title</option>
            <option value="author">Author</option>
            <option value="genre">Genre</option>
        </select>
        <input type="text" id="search-bar" class="form-control" placeholder="Search books..." autocomplete="off">
    </div>
   

<script>
$(document).ready(function () {
    $("#search-bar").on("keyup", function () {
        let query = $(this).val();
        let searchType = $("#search-type").val();

        if (query.length > 1) {
            $.ajax({
                url: "search_books.php", // Your PHP search script
                method: "POST",
                data: { search: query, type: searchType },
                success: function (data) {
                    $(".books-grid").html(data); // Replace books list with search results
                }
            });
        } else {
            $(".books-grid").html(""); // Clear results if input is empty
        }
    });

    // Prevent form from submitting (if your search input is inside a form)
    $("#search-form").submit(function (e) {
        e.preventDefault(); // Stops page reload
    });
});

</script>
    
    </header>




  








    <div id="search-results" class="list-group"></div>

    <script>
    $(document).ready(function(){
    $("#search-bar").keyup(function(){
        let query = $(this).val();
        let searchType = $("#search-type").val();

        if (query.length > 1) {
            $.ajax({
                url: "search_books.php",
                method: "POST",
                data: { search: query, type: searchType },
                success: function(data) {
                    $(".books-grid").html(data); // Replace books list with search results
                }
            });
        } else {
            $(".books-grid").load(location.href + " .books-grid > *"); // Load original books without refreshing
        }
    });
});

    </script>





<div class="books-grid">
<?php
include 'db.php';

$sql = "SELECT title, author, isbn, genre, cover_image FROM books";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $coverImage = !empty($row["cover_image"]) ? $row["cover_image"] : "uploads/default_cover.jpg";

        // Ensure that all images have 'uploads/' prefix if necessary
        if (!str_starts_with($coverImage, 'uploads/')) {
            $coverImage = "uploads/" . $coverImage;
        }

        // Make the entire card clickable by wrapping it in an <a> tag
        echo "<a href='book_dashboard.php?isbn=" . urlencode($row["isbn"]) . "' class='book-link'>
                <div class='book-card'>
                    <img src='" . htmlspecialchars($coverImage) . "' alt='Book Cover' class='book-cover'>
                    <h2 class='book-title'>" . htmlspecialchars($row["title"]) . "</h2>
                    <p class='book-author'>Author: " . htmlspecialchars($row["author"]) . "</p>
                    <p class='book-isbn'>ISBN: " . htmlspecialchars($row["isbn"]) . "</p>
                    <p class='book-genre'>Genre: " . htmlspecialchars($row["genre"]) . "</p>
                </div>
              </a>";
    }
} else {
    echo "<p>No books found.</p>";
}
?>
</div>



    </div>
</body>
</html>









