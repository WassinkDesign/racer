<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$event_id = 0;
$event = [];

if (isset($_GET["event"])) {
    $event_id = h($_GET["event"]);
    $event = get_event($conn, $event_id);
    if ($event === false) {
        $general_err = "Error retrieving information. Please try again later.";
    }
} else {
    redirect_to(url_for('settings/events.php'));
}

$locations = get_locations_display($conn);

$races = get_races($conn);

$addURL = "settings/add-race.php?event=$event_id";
$title = "Races";
include include_url_for('header.php');

?>
<div class="container">
    <div class="section">
        <div class="line no-border">
            <div class="" id="<?php echo $event['ID'];?>">
                <span class="line-title"><?php echo $event['NAME'];?></span>
                <p><?php echo "{$event['START_DATE']} - {$event['END_DATE']}";?></p>
            </div>
        </div>
    </div>
    <div class="section">
    <?php
    if (empty($races) === false) {
        foreach ($races as $race) {
            ?>
        <div class="line no-border">
            <div class="" id="<?php echo $race['ID'];?>">
                <span class="line-title"><?php echo "{$race['CLASS']} {$race['TYPE']}";?></span>
                <p><?php echo "{$race['DATE']} {$race['TIME']}";?></p>
                <p>
                    <a href="<?php echo url_for('settings/update-race.php') . "?race={$race['ID']}";?>"><span class="left small-caps">Edit</span></a>
                    <a href="<?php echo url_for('settings/delete/delete-race.php') . "?race={$race['ID']}";?>"><span class="small-caps black-text">Delete</span></a>
                </p>
            </div>
        </div>
            <?php
        }
    } else {
        echo "<p class=\"\">There are no races available for this event.</p>";
    } ?>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/events.php'); ?>">Back to Events</a>
    </div>
</div>
<?php include include_url_for('footer.php');?>