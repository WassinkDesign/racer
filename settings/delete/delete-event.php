<?php
require_once "../../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$event_id = 0;

if (isset($_GET["event"])) {
    $event_id = $_GET["event"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("settings/events.php"));
    exit;
}

$success = "";
$error = "";

if (is_post_request() === true) {
    $event_id = trim($_POST["event_id"]);

    if (delete_event($conn, $event_id) === true) {
        $success = true;
        redirect_to(url_for("settings/events.php"));
    } else {
        $success = false;
        $general_err = "Error deleting information. Please try again later.";
    }
}

$event = get_event($conn, $event_id);

$addURL = "";
$title = "Delete Event";
include include_url_for('header.php');

if ($success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="section">
        <div class="line no-border" id="<?php echo $event['ID']; ?>">
            <div class="line-title"><?php echo $event['NAME']; ?></div>
            <p><?php echo $event["LOCATION"]; ?><br/>
                <?php echo $event["DESCRIPTION"]; ?></p>
            <p><?php echo $event["START_DATE"]; ?> - <?php echo $event["END_DATE"]; ?></p>
            <div class="divider"></div>
            <p><span class="verify">Are you sure you want to delete this event?</span></p>
            <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input id="event_id" name="event_id" type="hidden" value="<?php echo $event['ID']; ?>">
                <?php echo display_submit_cancel("settings/events.php"); ?>
            </form>
        </div>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/events.php'); ?>">Back to Events</a>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>