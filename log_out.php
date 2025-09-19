<?php
session_start();
if (isset($_SESSION['Username'])) {
    session_unset(); // removes session variables
    session_destroy();
    session_abort();
}
header("Location: home.php"); //redirct to homepage
?>