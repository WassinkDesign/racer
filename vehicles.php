<?php
require_once "control/init.php";

if ($signedIn === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$person_id = $_SESSION["id"];

$vehicles = get_vehicles_person_display($conn, $person_id);

$addURL = "add-vehicle.php";
$title = "Vehicles";
include include_url_for('header.php');

if ($update_success === true) { echo display_update(); } elseif ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="line no-border">
        <?php 
        if (empty($vehicles) === false) {?>
        <table>
        <?php echo table_heading_row(["Number", "Class", "Details", "", ""]);?>
            <tbody><?php
            foreach ($vehicles as $vehicle) {
                echo table_row([$vehicle["NUMBER"], $vehicle["CLASS"], $vehicle["DETAILS"], table_link(url_for('update-vehicle.php') . "?vehicle={$vehicle["ID"]}", "Edit", ""), table_link(url_for('delete-vehicle.php') . "?vehicle={$vehicle["ID"]}", "Delete", "black-text")]);
            }
            ?>
            </tbody>
        </table>
        <?php } else { echo "<p class=\"\">There are no vehicles available.</p>"; } ?>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>