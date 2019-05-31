<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$title = "Settings";
include('header.php'); 
?>
<div class="container">
    <h2 class="header center orange-text">Main</h2>

    <div class="section">
        <div class="collection">
            <a href="account.php" class="collection-item">Account</a>
            <a href="standings.php" class="collection-item">Standings</a>
            <?php if ($admin === true) { ?>
            <a href="scorekeep.php" class="collection-item">Scorekeep</a>
            <a href="settings-main.php" class="collection-item">Settings</a>
            <?php } ?>
        </div>
    </div>

<div class="mb-3">
    <div class="list-group">
        <a href="settings/events.php" class="list-group-item list-group-item-action border-success">Events</a>
        <a href="settings-classes.php" class="list-group-item list-group-item-action border-success">Classes</a>
        <a href="settings-racetypes.php" class="list-group-item list-group-item-action border-success">Race Types</a>
    </div>
</div>
<?php include(url_for('footer.php')); ?>