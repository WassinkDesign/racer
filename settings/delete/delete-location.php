<?php
require_once "../../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$location_id = 0;

if (isset($_GET["location"])) {
    $location_id = $_GET["location"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("settings/locations.php"));
    exit;
}

$success = "";
$error = "";

if (is_post_request() === true) {
    $location_id = trim($_POST["location_id"]);
    $location = get_location($conn, $location_id);

    if (delete_location($conn, $location_id)) {
        $update_success = true;
        redirect_to(url_for("settings/locations.php"));
    } else {
        $update_success = false;
        $general_err = "Error updating location information. Please try again later.";
    }
}

$location = get_location($conn, $location_id);

$addURL = "";
$title = "Delete location";
include include_url_for('header.php');

if ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <h2 class="header center deep-orange-text">Delete location</h2>
    <div class="section">
        <div class="line no-border" id="<?php echo $location['ID']; ?>">
            <div class="line-title"><?php echo $location['NAME']; ?></div>
            <p><?php echo $location["ADDRESS"]; ?><br/>
                    <?php echo $location["CITY"]; ?>, <?php echo $location["PROV"]; ?></p>
            <div class="divider"></div>
            <p><span class="verify">Are you sure you want to delete this location?</span></p>
            <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input id="location_id" name="location_id" type="hidden" value="<?php echo $location['ID']; ?>">
                <?php echo display_submit_cancel("settings/locations.php"); ?>
            </form>
        </div>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/locations.php'); ?>">Back to locations</a>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>