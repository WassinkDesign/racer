<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$addURL = "settings/add-type.php";
$title = "Race Types";
include(include_url_for('header.php')); 

?>
<div class="container">
    <h2 class="header center orange-text">Race Types</h2>


<?php include(include_url_for('footer.php')); ?>