<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$events = get_events_display($conn);

$addURL = "settings/add-event.php";
$title = "Events";
include(include_url_for('header.php')); 

?>

<div class="container">
    <div class="section">
    <?php
    if (empty($events) === false) {
        foreach ($events as $event) { ?>
            <div class="line no-border" id="<?php echo $event['ID'];?>">
                <div class="line-title"><?php echo $event['NAME'];?></div>
                <p><?php echo "{$event['START_DATE']} - {$event['END_DATE']}";?><br/>
                <?php echo $event['LOCATION'];?><br/>
                <?php echo $event['DESCRIPTION'];?></p>
                <div class="">
                    <a href="<?php echo url_for('settings/update-event.php') . "?event={$event['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps">Edit</span></a>
                    <a href="<?php echo url_for('settings/delete/delete-event.php') . "?event={$event['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps black-text">Delete</span></a>
                    <a href="<?php echo url_for('settings/races.php') . "?event={$event['ID']}";?>" class="col waves-effect waves-light"><span class="right small-caps green-text">Races</span></a>
                </div>                
            </div>
        <?php }
    } else {
    ?> 
        <p class="">There are no events available.</p>
    <?php
    }
    ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>