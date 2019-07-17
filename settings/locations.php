<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$locations = get_locations_display($conn);


$addURL = "settings/add-location.php";
$title = "Locations";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
    <?php
    foreach ($locations as $location) {
        ?>
        <div class="line no-border" id="<?php echo $location['ID'];?>">
            <div class="line-title"><?php echo $location['NAME'];?></div>
            <p><?php 
            if ($location['ADDRESS'] !== ' ') { echo "{$location['ADDRESS']}, "; }
            if ($location['CITY'] !== ' ') { echo "{$location['CITY']}, ";}
            if ($location['PROV'] !== ' ') { echo "{$location['PROV']}";}
            ?> </p>
            <div class="">
                <a href="<?php echo url_for('settings/update-location.php') . "?location={$location['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps">Edit</span></a>
                <a href="<?php echo url_for('settings/delete/delete-location.php') . "?location={$location['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps black-text">Delete</span></a>
            </div>                
        </div>
    <?php } ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>