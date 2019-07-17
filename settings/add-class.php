<?php
require_once "../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$name = $desc = "";

$general_err = "";
$update_success = "";

if (is_post_request() === true) {
    $name = h($_POST["name"]);
    $desc = h($_POST["desc"]);

    if ($name == "") {
        $general_err = "Please enter a name";
    } else {
        if (empty($desc)) {
            $desc = " ";
        }

        if (insert_race_class($conn, $name, $desc) !== false) {
            redirect_to(url_for('settings/classes.php'));
        } else {
            $update_success = false;
            $general_err = "Error adding class information.  Please try again later.";
        }
    }
}

$addURL = "";
$title = "Add Class";
include include_url_for('header.php');

if ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <?php echo text_input('Name', 'name', $name);?>
            <?php echo text_input('Description', 'desc', $desc);?>
            <?php echo display_submit_cancel("settings/classes.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/classes.php'); ?>">Back to Classes</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>