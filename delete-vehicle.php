<?php
require_once "control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$race_class_id = $driver_id = $person_id = $vehicle_id = 0;
$vehicle_num = $details = $race_class = $general_err = "";
$success = true;

if (isset($_GET["vehicle"])) {
    $vehicle_id = $_GET["vehicle"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("vehicles.php"));
    exit;
}

$vehicle = get_vehicle($conn, $vehicle_id);

if ($vehicle === false) {
    $general_err = "Error retrieving information. Please try again later.";
    $success = false;
}

if (is_post_request() === true) {
    $vehicle_id = htmlentities(trim($_POST['vehicle_id']));

    if (delete_vehicle($conn, $vehicle_id) === true) {
        $success = true;
        redirect_to(url_for("account.php"));        
    } else {
        $success = false;
        $general_err = "Error deleting information. Please try again later.";
    }
}

$addURL = "";
$title = "Delete Vehicle";
include include_url_for('header.php');

if ($success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="section">
        <div class="line no-border" id="<?php echo $vehicle['ID']; ?>">
            <div class="line-title"><?php echo $vehicle['CLASS'] . " #" . $vehicle['NUMBER']; ?></div>
            <p><?php echo $vehicle['DETAILS'];?></p>
            <div class="divider"></div>
            <p><span class="verify">Are you sure you want to delete this vehicle?</span></p>
            <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input id="vehicle" name="vehicle" type="hidden" value="<?php echo $vehicle['ID']; ?>">
                <?php echo display_submit_cancel("vehicles.php"); ?>
            </form>
        </div>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('vehicles.php'); ?>">Back to Vehicles</a>        
    </div>
</div>
<?php
include include_url_for('footer.php');
?>