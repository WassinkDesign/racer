<?php
require_once "control/init.php";

$name = $notes = $team_err = $general_err = $update_success = "";

if (is_post_request() === true) {
    $name = htmlentities(trim($_POST["name"]));
    $notes = htmlentities(trim($_POST["notes"]));

    if (insert_team($conn, $name, $notes) !== false) {
        $update_success = true;
        $name = $notes = "";
    } else {
        $update_success = false;
        $general_err = "Error adding your information.  Please try again later.";
    }
}
$addURL = "";
$title = "Add Team";
include include_url_for('header.php');
if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <?php echo text_input("Name", "name", $name);?>
            <?php echo text_input("Notes", "notes", $notes);?>
            <?php echo display_submit_cancel("teams.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('teams.php'); ?>">Back to Teams</a>
        </div>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>
