<?php 
// Initialize the session
session_start();
$signedIn = $admin = false;

// Check if the user is logged in, if not then redirect him to login page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $signedIn = true;
}
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    $admin = true;
}
?>