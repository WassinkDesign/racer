<?php
include_once('control/signed-in-check.php');

if ($signedIn === false){
    header("location: login.php");
    exit;
}

$title = "Scorekeep";
include('header.php'); 
?>
<div class="container">
    <h2 class="header center orange-text">Scorekeep</h2>
</div>

<?php include('footer.php'); ?>