<?php
include_once('../control/signed-in-check.php');

if ($signedIn === false){
    header("location: login.php");
    exit;
}

$title = "Settings";
include('header.php'); 
?>
<div class="container">
    <h2 class="header center orange-text">Main</h2>
</div>
<div class="mb-3">
    <div class="list-group">
        <a href="settings/events.php" class="list-group-item list-group-item-action border-success">Events</a>
        <a href="settings-classes.php" class="list-group-item list-group-item-action border-success">Classes</a>
        <a href="settings-racetypes.php" class="list-group-item list-group-item-action border-success">Race Types</a>
    </div>
</div>
<?php include('footer.php'); ?>