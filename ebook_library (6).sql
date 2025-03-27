-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 10:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebook_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `edition_key` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `genre`, `cover_image`, `created_at`, `edition_key`) VALUES
(1, 'To Kill a Mockingbird', 'Harper Leey', '978-0-06-112008-4', 'Fiction', 'to_kill_a_mockingbird.jpg', '2025-03-26 05:24:55', ''),
(2, '1984', 'George Orwell', '978-0-452-28423-4', 'Dystopian', '1984.jpg', '2025-03-26 05:24:55', ''),
(3, 'Pride and Prejudice', 'Jane Austen', '978-0-19-953556-9', 'Romance', 'pride_and_prejudice.jpg', '2025-03-26 05:24:55', ''),
(4, 'The Great Gatsby', 'F. Scott Fitzgerald', '978-0-7432-7356-5', 'Classic', 'the_great_gatsby.jpg', '2025-03-26 05:24:55', ''),
(5, 'Moby-Dick', 'Herman Melville', '978-0-14-243724-7', 'Adventure', 'moby_dick.jpg', '2025-03-26 05:24:55', ''),
(6, 'War and Peace', 'Leo Tolstoy', '978-0-14-044793-4', 'Historical', 'war_and_peace.jpg', '2025-03-26 05:24:55', ''),
(7, 'The Catcher in the Rye', 'J.D. Salinger', '978-0-316-76948-0', 'Fiction', 'the_catcher_in_the_rye.jpg', '2025-03-26 05:24:55', ''),
(8, 'The Hobbit', 'J.R.R. Tolkien', '978-0-618-00221-3', 'Fantasy', 'the_hobbit.jpg', '2025-03-26 05:24:55', ''),
(9, 'Fahrenheit 451', 'Ray Bradbury', '978-0-7432-4722-1', 'Dystopian', 'fahrenheit_451.jpg', '2025-03-26 05:24:55', ''),
(10, 'Jane Eyre', 'Charlotte Brontë', '978-0-14-144114-6', 'Gothic', 'jane_eyre.jpg', '2025-03-26 05:24:55', ''),
(11, 'The Catcher in the Rye', 'J.D. Salinger', '9780316769488', 'Fiction', 'catcher_in_the_rye.jpg', '2025-03-26 05:36:18', ''),
(12, 'Pride and Prejudice', 'Jane Austen', '9780141439518', 'Classic', 'pride_and_prejudice.jpg', '2025-03-26 05:36:18', ''),
(13, 'Atomic habits', 'James Clear', '978-0735211292', 'Self-Help', 'uploads/atomihabit.png', '2025-03-26 09:58:36', ''),
(15, 'The Midnight Library', 'Matt Haig', '978-0525559474', 'Fiction', 'uploads/midnight_library.jpg', '2025-03-26 10:11:29', '');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `borrowed_at` datetime DEFAULT current_timestamp(),
  `due_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `returned_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `book_id`, `user_id`, `borrowed_at`, `due_date`, `status`, `returned_at`) VALUES
(3, 2, 9, '2025-03-27 08:53:59', '2025-04-03 08:53:59', 'returned', '2025-03-27 11:55:12'),
(4, 15, 9, '2025-03-27 08:59:10', '2025-04-03 08:59:10', 'returned', '2025-03-27 11:55:08');

-- --------------------------------------------------------

--
-- Table structure for table `requested_books`
--

CREATE TABLE `requested_books` (
  `id` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `requested_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `username`, `password`, `created_at`, `role`) VALUES
(9, 'Joseph kirika', 'kirikajoseph16@gmail.com', 'joseph', '$2y$10$aR4AnPZXm0aAer4gtI1jDOJQP/GCxuop4N/ccfn/nrLkDIkQMNYR.', '2025-03-26 23:08:15', 'Admin'),
(12, 'test Joseh', 'test@gmail.com', 'test', '$2y$10$yd9D2avapXpai.6mKgwH5.ddy6fK7b8zqghAuuiz/8t.r146sag3W', '2025-03-27 09:30:51', 'Patron'),
(13, 'Bernice wambui', 'bernice@gmail.com', 'bernice', '$2y$10$rjkQCWuO3hoJWNNqjHcQl.q5qUkKTfG4O3BOeHoEfAZr7cuv5xwv.', '2025-03-27 10:10:39', 'Patron');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `requested_books`
--
ALTER TABLE `requested_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `requested_books`
--
ALTER TABLE `requested_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `requested_books`
--
ALTER TABLE `requested_books`
  ADD CONSTRAINT `requested_books_ibfk_1` FOREIGN KEY (`requested_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
