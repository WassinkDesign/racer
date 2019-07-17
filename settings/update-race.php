<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}


// event info
$events = [];
$event_id = -1;
$event_name = $event_start = $event_end = "";

$race_id = -1;

// race type info
$race_types = [];
$race_type_id = 0;

// race class info
$race_classes = [];
$race_class_id = 0;

// race info
$date = $time = $description = "";

// housekeeping
$success = $general_err = "";

if (is_post_request() === true) {
    $race_id = h($_POST['race_id']);
} else if (isset($_GET["race"])) {
    $race_id = h($_GET["race"]);
} else {
    redirect_to(url_for('settings/events.php'));
    exit;
}

$race = [];
if ($race = get_race($conn, $race_id) === false) {
    $update_success = false;
    $general_err = "Unable to fetch race information.";
}

$event = get_event($conn, $race['EVENT_ID']);
$race_classes = get_race_classes($conn);
$race_types = get_race_types($conn);

if (is_post_request() === true) {
    $race_id = h($_POST['race_id']);
    $event_id = h($_POST['event_id']);
    $race_type_id = h($_POST['type']);
    $race_class_id = h($_POST['class']);
    $date = h($_POST['date']);
    $time = h($_POST['time']);
    $description = h($_POST['desc']);

    if (update_race($conn, $race_id, $event_id, $race_type_id, $race_class_id, $date, $time, $description) === true) {
        $update_success = true;
        $race = get_race($conn, $race_id);
    } else {
        $update_success = false;
        $general_err = "Error updating race information. Please try again later.";
    }
}

$addURL = "";
$title = "Update Race";
include include_url_for('header.php');

if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="row">
    <?php
    if ($event_id != -1) {?>
        <div class="line no-border">
            <div class="" id="<?php echo $event['ID'];?>>">
                <span class="line-title"><?php echo $event['NAME'];?></span>
                <p><?php echo "{$event['START_DATE']} - {$event['END_DATE']}";?></p>
            </div>
        </div>
    <?php } ?>
    <form id="mainForm" class="col s12" action="<?php echo h($_SERVER["PHP_SELF"]); ?>" method = "POST">
        <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id; ?>">
        <input id="race_id" name="race_id" type="hidden" value="<?php echo $race_id; ?>">
        <?php echo dropdown_input('Race Type', 'type', 'type', true, $race_types, 'ID', 'NAME', $race_type_id, 'settings/types.php', 'Race Types');?>
        <?php echo dropdown_input('Race Class', 'class', 'class', false, $race_classes, 'ID', 'NAME', $race_class_id, 'settings/classes.php', 'Race Classes');?>
        <?php echo text_input('Description', 'desc', $description);?>
        <?php echo date_input('Date', 'date', $date);?>
        <?php echo time_input('Time', 'time', $time);?>         
        <?php echo display_submit_cancel("settings/races.php"); ?>
    </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/races.php') . "?event=$event_id"; ?>">Back to Races</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>