<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library E-Book Lending</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>


    <style>
        .get-started-btn {
        background-color: rgb(248, 95, 6); /* Your preferred color */
        color: white;
        font-size: 18px;
        font-weight: bold;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
        text-transform: uppercase;
    }

    .get-started-btn:hover {
        color:  rgb(248, 95, 6);
        background-color:rgb(255, 255, 255); /* Slightly darker for hover effect */
        transform: scale(1.05);
    }

    .get-started-btn:active {
        background-color: #3e8e41;
        transform: scale(0.98);
    }
    </style>
</head>
<body>
    <header>
        <h1>Library E-Book Lending</h1>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="./login.php">Login</a></li>
                <li><a href="./register.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <div id="home">

    <main>
        <section class="welcome">
            <h2>Welcome to Our Digital Library</h2>
            <p class="des">Unlock a universe of knowledge—explore, discover, and dive into endless stories..</p>
            
            <button class="get-started-btn" onclick="window.location.href='register.php'">Get Started</button>
        </section>

        </div>


        <div id="about">

        <div class="features">
            <div class="feature">
                <h3>📚 Vast Collection</h3>
                <p>Explore thousands of e-books across different genres.</p>
            </div>
            <div class="feature">
                <h3>🔄 Easy Borrowing</h3>
                <p>Borrow books with just one click and return them anytime.</p>
            </div>
            <div class="feature">
                <h3>👩‍💻 Admin & Librarian Support</h3>
                <p>Manage books and users seamlessly with our dashboard.</p>
            </div>
        </div>
    </main>
    </div>


    <div id="contact">
    <div class="container">
        <h2 class="contact_header" >Contact Us</h2>
        <p  class="contact_header" > We'd love to hear from you! Reach out to us through the following methods:</p>
        <div class="contact-info">
            <div class="contact-item">
                <h3>Email</h3>
                <p><a href="mailto:info@yourebooklibrary.com">info@yourebooklibrary.com</a></p>
            </div>
            <div class="contact-item">
                <h3>Phone</h3>
                <p><a href="tel:+1234567890">+1 (234) 567-890</a></p>
            </div>
            <div class="contact-item">
                <h3>Address</h3>
                <p>123 Library Lane<br>Booktown, BK 12345</p>
            </div>
        </div>
    </div>
<div/>


    <footer>
        <p>&copy; 2025 Library E-Book Lending</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
