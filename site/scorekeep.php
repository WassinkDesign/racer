<?php
include_once('../control/signed-in-check.php');

if ($signedIn === false){
    header("location: login.php");
    exit;
}

$title = "Scorekeep";
include('header.php'); 
?>
<div class="page-header">
    <h1>OORA RACER</h1>
</div>

<?php include('footer.php'); ?>