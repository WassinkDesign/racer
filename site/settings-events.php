<?php
include_once('../control/signed-in-check.php');

if ($signedIn === false){
    header("location: login.php");
    exit;
}

$title = "Events";
include('header.php'); 
?>
<div class="page-header">
    <h1>Events</h1>
</div>

<?php include('footer.php'); ?>