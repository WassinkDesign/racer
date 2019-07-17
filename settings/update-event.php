<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$event_id = 0;
$location_id = 0;

if (isset($_GET["event"])) {
    $event_id = $_GET["event"];
} elseif (is_post_request() == true) {
    $event_id = h($_POST["event_id"]);
    $location_id = h($_POST["location"]);
} else {
    redirect_to(url_for("settings/events.php"));
    exit;
}

$name = $desc = $startDate = $endDate = $success = $error = "";
$locations = get_locations($conn);
$event = get_event($conn, $event_id);

if (is_post_request() === true) {
    $name = h($_POST["name"]);
    $desc = h($_POST["desc"]);
    $startDate = h($_POST["startDate"]);
    $endDate = h($_POST["endDate"]);

    if (update_event($conn, $event_id, $name, $desc, $location_id, $start_date, $end_date) === false) {
        $update_success = false;
        $general_err = "Error updating event information. Please try again later.";
    } else {
        $update_success = true;
    }
}

$addURL = "";
$title = "Update Event";
include include_url_for('header.php');

if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="row">
        <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
            <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id; ?>">
            <?php echo dropdown_input('Location', 'location', 'location', true, $locations, 'ID', 'NAME', $location_id, 'settings/locations.php', 'Locations');?>
            <?php echo text_input('Name', 'name', $name);?>
            <?php echo text_input('Description', 'desc', $desc);?>
            <?php echo date_input('Start Date', 'startDate', $startDate);?>
            <?php echo date_input('End Date', 'endDate', $endDate);?>
            <?php echo display_submit_cancel("settings/events.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/events.php'); ?>">Back to Events</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>