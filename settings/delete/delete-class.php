<?php
require_once "../../control/init.php";

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

$classes = [];
$success = "";
$error = "";

if (is_post_request() === true) {
    $class_id = trim($_POST["class_id"]);

    if (delete_class($conn, $class_id) === true) {
        $update_success = true;
        redirect_to(url_for("settings/classes.php"));
    } else {
        $update_success = false;
        $general_err = "Error deleting information. Please try again later.";
    }
}

$class = get_race_class($conn, $class_id);

if ($class === false) {
    $general_err = "Error retrieving your information.  Please try again later.";
}

$addURL = "";
$title = "Delete Class";
include include_url_for('header.php');

if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="section">
        <div class="line no-border" id="<?php echo $class['ID']; ?>">
            <div class="line-title"><?php echo $class['NAME']; ?></div>
            <p><?php echo $class['DESCRIPTION'];?></p>
            <div class="divider"></div>
            <p><span class="verify">Are you sure you want to delete this class?</span></p>
            <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input id="class_id" name="class_id" type="hidden" value="<?php echo $class['ID']; ?>">
                <?php echo display_submit_cancel("settings/classes.php"); ?>
            </form>
        </div>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/classes.php'); ?>">Back to Classes</a>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>