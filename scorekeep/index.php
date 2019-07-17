<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$events = $races = [];
$race_id = 0;




$addURL = "";
$title = "Scorekeep Setup";

include include_url_for('header.php');
?>
<div class="container">

</div>
<?php include include_url_for('footer.php');?>

