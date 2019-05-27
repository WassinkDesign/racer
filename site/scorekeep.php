<?php
// Initialize the session
session_start();
$signedIn = false;

// Check if the user is logged in, if not then redirect him to login page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $signedIn = true;
}

$title = "Scorekeep";
include('header.php'); 
?>
<div class="page-header">
    <h1>OORA RACER</h1>
</div>

<?php include('footer.php'); ?>