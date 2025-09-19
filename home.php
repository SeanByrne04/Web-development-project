<?php
    require 'header.php';
    require 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Home</title>
</head>
<body>
    <div class="home-wrapper">
        <div class="home-hero">
            <?php
                if (isset($_SESSION['Username'])) {
                    //logged in
                    echo '<h1>Welcome, ' . $_SESSION['FirstName'] . '</h1>';
                    echo '<p>We hope you find what you are looking for with our website!</p>';
                } 
                else {
                    //not logged in
                    echo '<h1>New to our website?</h1>';
                    echo '<p>Feel free to <a href="login.php">login</a> or <a href="register.php">register</a> with us to access our services.</p>';
                }
            ?>
        </div>

        <section class="home-goals">
            <h2>Our Goals</h2>
            <p>We aim to build a comprehensive and efficient library that meets all your needs. Our goal is to provide easy access to books for everyone, and we hope you will join us and help achieve our goal.</p>
        </section>

        <section class="home-books">
            <h2>Books Recommended by the Team</h2>
            <div class="book-item">
                <strong>Cujo</strong> by Stephen King
            </div>
            <div class="book-item">
                <strong>The Da Vinci Code</strong> by Dan Brown
            </div>
            <div class="book-item">
                <strong>Computers in Business</strong> by Alicia O'Neill
            </div>
        </section>
    </div>

</body>
</html>

<?php
include 'footer.php';
?>
