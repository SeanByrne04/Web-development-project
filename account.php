<?php
require 'header.php';
require 'db_connection.php';

//if the user is NOT logged in, send to login page
if (!isset($_SESSION['Username'])) {
    echo "Not logged in"; 
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
    <title>Account</title>
</head>
<body>

    <div class="form-container">
        <form class="form-box">
            <h2>Your Details</h2><br>
            <p><strong>Name:</strong> <?php echo $_SESSION['FirstName'] . " " . $_SESSION['LastName']; ?></p><br>
            <p><strong>Date of Birth:</strong> <?php echo $_SESSION['BirthDate']; ?></p><br>
            <p><strong>Date Registered:</strong> <?php echo $_SESSION['DateRegistered']; ?></p><br>
            <p><strong>Address:</strong> <?php echo $_SESSION['AdressLine1'] . ' ' . $_SESSION['AdressLine2'] . ' ' . $_SESSION['city']; ?></p><br><br>
            <h2>Your Contact Details</h2><br>
            <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p><br>
            <p><strong>Phone Number:</strong> <?php echo $_SESSION['PhoneNumber']; ?></p><br>
        </form>
    </div>

</body>
</html>

<?php
include 'footer.php';
?>
