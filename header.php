<?php
//header is linked to all pages so we use session start here
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <ul>
    <li><a href="home.php">Home</a></li>
    <li><a href="browseBooks.php">Browse Books</a></li>
    <li><a href="reserved.php">Reserved Books</a></li>
    <ri>
        <div class="dropdown">
            <button class="dropbtn">Account</button>
            <div class="dropdown-content">
                <?php
                //showing account page link if the user is logged in
                if (isset($_SESSION['Username'])) {
                    ?>
                    <html><a href="account.php">Account</a></html>
                    <?php
                }?>

                <a href="login.php">Login</a>
                <a href="register.php">Register</a>

                <?php
                //showing log out page link if the user is logged in
                if (isset($_SESSION['Username'])){
                ?>
                <a href="log_out.php">Log out</a>
                <?php
                }
                ?>
            </div>
        </div>
    </ri>
    </ul>
    <div class="header-band">
        <span>
        <?php
        //display username if logged in
        if (isset($_SESSION['Username'])) {
            echo "Welcome, " .$_SESSION['Username'];
        } 
        else {
            echo "Welcome"; 
        }
        ?>
        </span>
    </div>
    
</body>
</html>