<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$type_id = 0;

if (isset($_GET["type"])) {
    $type_id = $_GET["type"];
} elseif (is_post_request() == true) {
    $type_id = h($_POST["type_id"]);
} else {
    redirect_to(url_for("settings/types.php"));
    exit;
}

$type = get_race_type($conn, $type_id);
$general_err = "";
$update_success = "";

$pointsDisplay = [
    ["ID"=>"1",
    "DISPLAY"=>"YES"],
    ["ID"=>"0",
    "DISPLAY"=>"NO"]
];

if (is_post_request() === true) {
    $type['NAME'] = h($_POST["name"]);
    $type['POINTS'] = h($_POST["points"]);

    if ($name == "") {
        $general_err = "Please enter a name";
    } else {
        if (update_race_type($conn, $type_id, $name, $points) === true) {
            redirect_to(url_for('settings/types.php'));
        } else {
            $update_success = false;
            $general_err = "Error updating type information. Please try again later.";
        }
    }
}


if ($type === false) {
    $general_err = "Error retrieving your information.  Please try again later.";
}

$addURL = "";
$title = "Update type";
include include_url_for('header.php');

if ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
        <input id="type_id" name="type_id" type="hidden" value="<?php echo $type['ID']; ?>">
            <?php echo text_input('Name', 'name', $type['NAME']);?>
            <?php echo dropdown_input_basic('Points Awarded', 'points', false, $pointsDisplay, 'ID', 'DISPLAY', $type['POINTS']);?>
            <?php echo display_submit_cancel("settings/types.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/types.php'); ?>">Back to Types</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>

