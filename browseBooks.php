<?php
    require 'header.php';
    require 'db_connection.php';

    $username = $_SESSION['Username'];

    //if the user is NOT logged in, send to login page
    if (!isset($_SESSION['Username'])) {
        header("Location: login.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Books</title>
</head>
<body>
    <h2>Search for a Book</h2>

    <form id="searchForm" method="post">
        <label for="bookName">Book Name:</label>
        <input type="text" name="bookName" id="bookName" placeholder="Enter book title">

        <label for="author">Author:</label>
        <input type="text" name="author" id="author" placeholder="Enter author name">

        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="">Select a category</option>
            <?php
                //showing options for dropdown from the category table
                $query = "SELECT * FROM category";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['categoryID'] . "'>" . $row['categoryDescription'] . "</option>";
                }
            ?>
        </select>

        <input type="submit" name="Submit" value="Search">
    </form>

    <div class="book-results">
        <?php

        //easier use than fetching data from the table or using session variable
        //will always work as the user cannot acsess the page unless there logged in
        $username = $_SESSION['Username'];


        $resultsPerPage = 5; // Variable foir number of results to show per page
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page default is 1
        $offset = ($currentPage - 1) * $resultsPerPage;


        $bookName = isset($_POST['bookName']) ? $_POST['bookName'] : '';
        $author = isset($_POST['author']) ? $_POST['author'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';

        // SQL query to get number of results
        $countSql = "SELECT COUNT(*) AS total FROM books 
                    JOIN category ON books.Category = category.categoryID
                    WHERE 1";

        // Serches
        if (!empty($bookName)) {
            $countSql .= " AND BookTitle LIKE '%" . mysqli_real_escape_string($conn, $bookName) . "%'";
        }

        if (!empty($author)) {
            $countSql .= " AND Author LIKE '%" . mysqli_real_escape_string($conn, $author) . "%'";
        }

        if (!empty($category)) {
            $countSql .= " AND books.Category = '" . mysqli_real_escape_string($conn, $category) . "'";
        }

        $countResult = mysqli_query($conn, $countSql);
        $countRow = mysqli_fetch_assoc($countResult);
        $totalResults = $countRow['total']; // Num of boooks
        $totalPages = ceil($totalResults / $resultsPerPage); //Find number of pages

        // SQL query to fetch books
        $sql = "SELECT books.*, category.categoryDescription 
                FROM books
                JOIN category ON books.Category = category.categoryID
                WHERE 1";

        if (!empty($bookName)) {
            $sql .= " AND BookTitle LIKE '%" . mysqli_real_escape_string($conn, $bookName) . "%'";
        }

        if (!empty($author)) {
            $sql .= " AND Author LIKE '%" . mysqli_real_escape_string($conn, $author) . "%'";
        }

        if (!empty($category)) {
            $sql .= " AND books.Category = '" . mysqli_real_escape_string($conn, $category) . "'";
        }

        $sql .= " LIMIT $offset, $resultsPerPage"; // limit 5

        $result = mysqli_query($conn, $sql);

        // Reservation logic
        if (isset($_POST['reserve'])) {
            $isbnToReserve = $_POST['isbn'];

            // Check if the book is reserved
            $checkQuery = "SELECT * FROM reservations WHERE ISBN = '" . mysqli_real_escape_string($conn, $isbnToReserve) . "'";
            $checkResult = mysqli_query($conn, $checkQuery);

            
            if (mysqli_num_rows($checkResult) > 0) {
                // Book is alredy reserved
                echo "<p style='color: red;'>Sorry, this book has already been reserved by another user.</p>";
            } 
            else {
                // Insert current the date
                $reservationDate = date("Y-m-d");

                // SQL for reservation
                $reserveQuery = "INSERT INTO reservations (ISBN, Username, Reservation_date) 
                                VALUES ('" . mysqli_real_escape_string($conn, $isbnToReserve) . "', 
                                        '" . mysqli_real_escape_string($conn, $username) . "', 
                                        '" . $reservationDate . "')";
                mysqli_query($conn, $reserveQuery);
                echo "<p>Book reserved successfully!</p>";
            }
        }

        // Check if exist
        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Search Results:</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Year</th>
                        <th>Edition</th>
                        <th>Action</th>
                    </tr>";

            // Show search results
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['BookTitle'] . "</td>";
                echo "<td>" . $row['Author'] . "</td>";
                echo "<td>" . $row['categoryDescription'] . "</td>";
                echo "<td>" . $row['Year'] . "</td>";
                echo "<td>" . $row['Edition'] . "</td>";
                echo "<td>
                        <form method='post'>
                            <input type='hidden' name='isbn' value='" . $row['ISBN'] . "'>
                            <input type='submit' name='reserve' value='Reserve'>
                        </form>
                    </td>";
                echo "</tr>";
            }
            echo "</table>";

            // Pagination controls
            echo "<div class='pagination'>";
            if ($currentPage > 1) {
                echo "<a href='?page=" . ($currentPage - 1) . "'>&laquo; Previous</a>";
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $currentPage) {
                    echo "<span>$i</span>";
                } 
                else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }

            if ($currentPage < $totalPages) {
                echo "<a href='?page=" . ($currentPage + 1) . "'>Next &raquo;</a>";
            }
            echo "</div>";

        } 
        else {
            echo "<p>No books found.</p>";
        }
    ?>


    </div>

</body>
</html>

<?php
include 'footer.php';
?>