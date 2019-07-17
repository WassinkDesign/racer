<?php
require_once "control/init.php";

$race_class_id = $driver_id = $person_id = $vehicle_id = 0;
$vehicle_num = $details = "";
$race_classes = [];
$general_err = "";
$success = true;

if ($signedIn === false) {
    redirect_to(url_for("login.php"));
    exit;
}

if (isset($_GET["vehicle"])) {
    $vehicle_id = $_GET["vehicle"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("account.php"));
    exit;
}

$person_id = $_SESSION["id"];

$classes = get_race_classes($conn);
$vehicle = get_vehicle($conn, $vehicle_id);

if ($vehicle === false) {
    $general_err = "Error retrieving information. Please try again later.";
    $success = false;
} else {
    $race_class_id = $vehicle["CLASS"];
    $vehicle_num = $vehicle["NUMBER"];
    $details = $vehicle["DETAILS"];
}

if (is_post_request() === true) {
    $race_class_id = h($_POST['class']);
    $vehicle_id = h($_POST['vehicle_id']);
    $vehicle_num = h($_POST['vehicle_num']);
    $details = h($_POST['details']);

    if (update_vehicle($conn, $vehicle_id, $race_class_id, $vehicle_num, $details) === true) {
        $success = true;
        redirect_to(url_for('account.php'));
    } else {
        $success = false;
        $general_err = "Error adding your information.  Please try again later.";
    }
}

$addURL = "";
$title = "Update Vehicle";
include include_url_for('header.php');
if ($success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <input id="vehicle_id" name="vehicle_id" type="hidden" value="<?php echo $vehicle_id; ?>">
            <?php echo dropdown_input("Race Class", "class", "Race Class", true, $classes, "ID", "NAME", $race_class_id, "settings/classes.php", "Race Classes");?>
            <?php echo text_input("Number", "vehicle_num", $vehicle_num);?>
            <?php echo text_input("Details", "details", $details);?>
            <?php echo display_submit_cancel("vehicles.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('vehicles.php'); ?>">Back to Vehicles</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>
