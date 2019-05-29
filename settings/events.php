<?php
include_once('../../control/signed-in-check.php');

if ($signedIn === false){
    header("location: ../login.php");
    exit;
}

$title = "Events";
include('../header.php'); 
?>
<div class="container">
    <h2 class="header center orange-text">Events</h2>
</div>

<?php include('../footer.php'); ?>