<?php
    require 'header.php';
    require 'db_connection.php'; 

    //easier way of getting username withouth having to fetch from DB or use session varible constantly
    $username = $_SESSION['Username'];

    //if the user is NOT logged in, send to login page
    if (!isset($_SESSION['Username'])) {
        header("Location: login.php");
        exit();
    }

    //if remove reservation button is pressed
    if (isset($_POST['remove_reservation'])) {
        $isbnToRemove = $_POST['isbn'];

        //query the DB to remove the reservation
        $removeQuery = "DELETE FROM reservations WHERE ISBN = '" . mysqli_real_escape_string($conn, $isbnToRemove) . "' AND Username = '" . mysqli_real_escape_string($conn, $username) . "'";
        if (mysqli_query($conn, $removeQuery)) {
            echo "<p>Reservation removed successfully!</p>";
        } 
        else {
            echo "<p style='color: red;'>Failed to remove the reservation. Please try again.</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Reserved Books</title>
</head>
<body>
    <h2>Your Reserved Books</h2>

    <div class="reserved-books">
        <?php

            //sql to fetch books from reservation table
            $sql = "SELECT books.BookTitle, books.Author, books.Year, books.Edition, reservations.ISBN 
                    FROM reservations
                    JOIN books ON reservations.ISBN = books.ISBN
                    WHERE reservations.Username = '" . mysqli_real_escape_string($conn, $username) . "'";

            $result = mysqli_query($conn, $sql);

            //table index
            if (mysqli_num_rows($result) > 0) {
                echo "<table border='1'>
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Year</th>
                            <th>Edition</th>
                            <th>Action</th>
                        </tr>";

                //table content
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['BookTitle'] . "</td>";
                    echo "<td>" . $row['Author'] . "</td>";
                    echo "<td>" . $row['Year'] . "</td>";
                    echo "<td>" . $row['Edition'] . "</td>";
                    echo "<td>
                            <form method='post'>
                                <input type='hidden' name='isbn' value='" . $row['ISBN'] . "'>
                                <input type='submit' name='remove_reservation' value='Remove Reservation'>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>You have no reserved books.</p>";
            }
        ?>
    </div>

</body>
</html>


<?php
include 'footer.php';
?>
