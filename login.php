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
    <title>Login</title>
</head>
<body>

    <div class="form-container">
        <form action="login.php" method="POST" class="form-box">
            <h2>Login Page</h2>
            
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required>
            <span class="error" id="usernameError"></span><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
            <span class="error" id="passwordError"></span><br><br>

            <input type="submit" value="Login">
        </form>
    </div>

    <div class="center">
        <p>Don't have an account? <a href="register.php">Register</a> now</p>
    </div>

</body>
</html>


<?php

    //create empty array for errors
    $errors = [];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = mysqli_real_escape_string($conn, $_POST['username']);
        $pass = mysqli_real_escape_string($conn, $_POST['password']);

        // SQL query to find the user
        $sql = "SELECT * FROM users WHERE Username = '$user' LIMIT 1";
        $result = $conn->query($sql);

        // Check if the user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check if the password matches
            if (password_verify($pass, $row['Password'])) {
                //add all data to session except for password
                $_SESSION['Username'] = $row['Username'];
                $_SESSION['FirstName'] = $row['FirstName'];
                $_SESSION['LastName'] = $row['LastName'];
                $_SESSION['AdressLine1'] = $row['AdressLine1'];
                $_SESSION['AdressLine2'] = $row['AdressLine2'];
                $_SESSION['city'] = $row['city'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['PhoneNumber'] = $row['PhoneNumber'];
                $_SESSION['BirthDate'] = $row['BirthDate'];
                $_SESSION['DateRegistered'] = $row['DateRegistered'];

                header("Location: home.php"); // Redirect to home page
                exit();
            } 
            else {
                //add the password error
                $errors['password'] = "Incorrect password.";
            }
        } 
        else {
            //add the username error
            $errors['username'] = "No user found with this username.";
        }
    }

    $conn->close();
?>

<!-- 
Show error messages beside fields 
This is using javascript but this is for VISUAL PURPOSE ONLY, 
couldve been done with the existing codeing languages but this makes things simpler
-->
<script>
    <?php if (isset($errors['username'])): ?>
        document.getElementById('usernameError').innerText = '<?php echo $errors['username']; ?>';
        document.getElementById('usernameError').style.display = 'block';
    <?php endif; ?>

    <?php if (isset($errors['password'])): ?>
        document.getElementById('passwordError').innerText = '<?php echo $errors['password']; ?>';
        document.getElementById('passwordError').style.display = 'block';
    <?php endif; ?>
</script>

<?php
    include 'footer.php';
?>
