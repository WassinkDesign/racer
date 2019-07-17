<?php
require_once "../../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$type_id = 0;

if (isset($_GET["type"])) {
    $type_id = $_GET["type"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("settings/types.php"));
    exit;
}

$types = [];
$success = "";
$error = "";

if (is_post_request() === true) {
    $type_id = trim($_POST["type_id"]);

    if (delete_race_type($conn, $type_id) === true) {
        $update_success = true;
        redirect_to(url_for("settings/types.php"));
    } else {
        $update_success = false;
        $general_err = "Error deleting information. Please try again later.";
    }
}

$type = get_race_type($conn, $type_id);

if ($type === false) {
    $general_err = "Error retrieving your information.  Please try again later.";
}

$addURL = "";
$title = "Delete Race Type";
include include_url_for('header.php');

if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="section">
        <div class="line no-border" id="<?php echo $type['ID']; ?>">
            <div class="line-title"><?php echo $type['NAME']; ?></div>
            <div class="divider"></div>
            <p><span class="verify">Are you sure you want to delete this type?</span></p>
            <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input id="type_id" name="type_id" type="hidden" value="<?php echo $type['ID']; ?>">
                <?php echo display_submit_cancel("settings/types.php"); ?>
            </form>
        </div>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/types.php'); ?>">Back to Types</a>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>