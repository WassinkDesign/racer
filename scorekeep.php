<?php

require_once("control/init.php");



if ($signedIn === false){

    redirect_to(url_for("login.php"));

    exit;

}



$addURL = "";
$title = "Scorekeep";

include(include_url_for('header.php')); 

?>

<div class="container">

    <h2 class="header center orange-text">Scorekeep</h2>

</div>



<?php include(include_url_for('footer.php')); ?>