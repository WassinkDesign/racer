<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$class_id = 0;

if (isset($_GET["class"])) {
    $class_id = $_GET["class"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("settings/classes.php"));
    exit;
}

$name = $description = "";

$general_err = "";
$update_success = "";

if (is_post_request() === true) {
    $name = h($_POST["name"]);
    $description = h($_POST["description"]);
    $class_id = h($_POST["class_id"]);

    if (update_race_class($conn, $class_id, $name, $description) === true) {
        $update_success = true;
    } else {
        $update_success = false;
        $general_err = "Error updating address information. Please try again later.";
    }
}

$class = get_race_class($conn, $class_id);

if ($class === false) {
    $general_err = "Error retrieving your information.  Please try again later.";
}

$addURL = "";
$title = "Update Class";
include include_url_for('header.php');

if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}

?>
<div class="container">
    <div class="row">
        <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
            <div class="row">
                <input id="class_id" name="class_id" type="hidden" value="<?php echo $class_id; ?>">
                <?php echo text_input('Name', 'name', $class['NAME']);?>
                <?php echo text_input('Description', 'desc', $class['DESCRIPTION']);?>
                <?php echo display_submit_cancel("settings/classes.php"); ?>
            </div>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/classes.php'); ?>">Back to Classes</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>
