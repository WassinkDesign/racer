<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$addURL = "";
$title = "Add Class";
include(include_url_for('header.php')); 

?>
<div class="container">
    <h2 class="header center orange-text">Add Class</h2>


<?php include(include_url_for('footer.php')); ?>