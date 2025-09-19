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
    <title>Register</title>
</head>
<body>

    <div class="form-container">
        <form action="Register.php" method="POST" class="form-box">
            <h2>Register Page</h2>
            
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required>
            <span class="error" id="usernameError"></span><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
            <span class="error" id="passwordError"></span><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span class="error" id="confirmPasswordError"></span><br><br>

            <label for="FirstName">First Name:</label><br>
            <input type="text" id="FirstName" name="FirstName" required><br><br>

            <label for="LastName">Last Name:</label><br>
            <input type="text" id="LastName" name="LastName" required><br><br>

            <label for="AdressLine1">AdressLine1:</label><br>
            <input type="text" id="AdressLine1" name="AdressLine1" required><br><br>

            <label for="AdressLine2">AdressLine2:</label><br>
            <input type="text" id="AdressLine2" name="AdressLine2" required><br><br>

            <label for="city">City:</label><br>
            <input type="text" id="city" name="city" required><br><br>

            <label for="DOB">Date of Birth:</label><br>
            <input type="date" id="DOB" name="DOB" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
            <span class="error" id="emailError"></span><br><br>

            <label for="phoneNo">Phone number:</label><br>
            <input type="text" id="phoneNo" name="phoneNo" required>
            <span class="error" id="phoneNoError"></span><br><br>

            <input type="submit" value="Register">
        </form>
    </div>

    <div class="center">
        <p>Already have an account? <a href="login.php">Login</a> now</p>
    </div><br><br><br>

</body>
</html>

<?php
    include 'footer.php';
?>


<?php

    //create empty array for errors
    $errors = [];
    
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $Password = mysqli_real_escape_string($conn, $_POST['password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
        $FirstName = mysqli_real_escape_string($conn, $_POST['FirstName']);
        $LastName = mysqli_real_escape_string($conn, $_POST['LastName']);
        $AdressLine1 = mysqli_real_escape_string($conn, $_POST['AdressLine1']);
        $AdressLine2 = mysqli_real_escape_string($conn, $_POST['AdressLine2']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $BirthDate = mysqli_real_escape_string($conn, $_POST['DOB']);
        $PhoneNo = mysqli_real_escape_string($conn, $_POST["phoneNo"]);
        $role = "user";


        // SQL query to validate username
        $sql_check_username = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql_check_username);


        //checking for errors
        if ($result->num_rows > 0) {
            $errors['username'] = "Username is already taken.";//adds error message
        }

        if (strlen($Password) < 6) {
            $errors['password'] = "Password must be at least 6 characters long!";//adds error message
        }

        if ($Password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match!";//adds error message
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format!";//adds error message
        }

        if (!preg_match("/^[0-9]{10}$/", $PhoneNo)) {
            $errors['phoneNo'] = "Invalid phone number format!";//adds error message
        }


        if (empty($errors)) {
            //no errors
            $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);//hash password
            $DateRegistered = date('Y-m-d');//insert current date
            //add all data to the users table
            $sql = "INSERT INTO users (username, password, FirstName, LastName, AdressLine1, AdressLine2, city, email, BirthDate, DateRegistered, PhoneNumber) 
                    VALUES ('$username', '$hashedPassword', '$FirstName', '$LastName', '$AdressLine1', '$AdressLine2', '$city', '$email', '$BirthDate', '$DateRegistered', '$PhoneNo')";
            
            if ($conn->query($sql) === TRUE) {
                header("Location: login.php");
                exit();
            } 
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

    <?php if (isset($errors['confirm_password'])): ?>
        document.getElementById('confirmPasswordError').innerText = '<?php echo $errors['confirm_password']; ?>';
        document.getElementById('confirmPasswordError').style.display = 'block';
    <?php endif; ?>

    <?php if (isset($errors['email'])): ?>
        document.getElementById('emailError').innerText = '<?php echo $errors['email']; ?>';
        document.getElementById('emailError').style.display = 'block';
    <?php endif; ?>

    <?php if (isset($errors['phoneNo'])): ?>
        document.getElementById('phoneNoError').innerText = '<?php echo $errors['phoneNo']; ?>';
        document.getElementById('phoneNoError').style.display = 'block';
    <?php endif; ?>
</script>

