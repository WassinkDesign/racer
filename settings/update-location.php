<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$location_id = 0;

if (isset($_GET["location"])) {
    $location_id = $_GET["location"];
} elseif (is_post_request() === true) {
    $location_id = h($_POST["location_id"]);
} else {
    redirect_to(url_for("settings/locations.php"));
    exit;
}

$location = get_location_display($conn, $location_id);

$name = $address = $city = $prov = "";
$address_id = 0;
$general_err = "";
$update_success = "";

if (is_post_request() === true) {
    $name = h($_POST["name"]);
    $address = h($_POST["address"]);
    $city = h($_POST["city"]);
    $prov = h($_POST["prov"]);

    if ($name == "") {$name = " ";}

    $rawLocation = get_location($conn, $location_id);

    if (update_address($conn, $rawLocation['ADDRESS'], $address, $city, $prov) === true) {
        if (update_location($conn, $id, $name) === true) {
            $update_success = true;
            $location = get_location_display($conn, $location_id);
        } else {
            $update_success = false;
            $general_err = "Error updating location information. Please try again later.";
        }
    } else {
        $update_success = false;
        $general_err = "Error updating address information. Please try again later.";
    }
} 


$addURL = "";
$title = "Update Location";
include include_url_for('header.php');

if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <input id="location_id" name="location_id" type="hidden" value="<?php echo $location_id; ?>">
            <?php echo text_input('Name', 'name', $name);?>
            <?php echo text_input('Address', 'address', $address);?>
            <?php echo text_input('City', 'city', $city);?>
            <?php echo text_input('Province', 'prov', $prov);?>
            <?php echo display_submit_cancel("settings/locations.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/locations.php'); ?>">Back to Locations</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>
