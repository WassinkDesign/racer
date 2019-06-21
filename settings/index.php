<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$title = "Settings";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
        <div class="collection">
            <a href="<?php echo url_for('settings/classes.php');?>" class="collection-item">Classes</a>
            <a href="<?php echo url_for('settings/events.php');?>" class="collection-item">Events</a>
            <a href="<?php echo url_for('settings/locations.php');?>" class="collection-item">Locations</a>
            <a href="<?php echo url_for('settings/types.php');?>" class="collection-item">Race Types</a>
        </div>
    </div>
<?php include(include_url_for('footer.php')); ?>