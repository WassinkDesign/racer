<?php
require_once "control/init.php";

if ($signedIn === false) {
    header("location: login.php");
    exit;
}

$team_id = 0;

if (isset($_GET["team"])) {
    $team_id = $_GET["team"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("teams.php"));
    exit;
}

$person_id = $_SESSION["id"];

$name = $notes = $team_err = $general_err = $update_success = "";

if (is_post_request() === true) {
    $name = h($_POST["name"]);
    $notes = h($_POST["notes"]);
    $team_id = h($_POST["team"]);

    if (update_team($conn, $team_id, $name, $notes) === true) {
        redirect_to(url_for('teams.php'));
    } else {
        $update_success = false;
        $general_err = "Error updating your information. Please try again later.";
    }

    $team = get_team($conn, $team_id);

    if ($team === false) {
        $general_err = "Error retrieving information. Please try again later.";
    } else {
        $name = $team["NAME"];
        $notes = $team["NOTES"];
    }

} else {
    $team = get_team($conn, $team_id);

    if ($team === false) {
        $general_err = "Error retrieving information. Please try again later.";
    } else {
        $name = $team["NAME"];
        $notes = $team["NOTES"];
    }
}

$addURL = "";
$title = "Update Team";
include include_url_for('header.php');
if ($update_success === true) {echo display_update();} elseif ($update_success === false) {echo display_error($general_err);}
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <input id="team" name="team" type="hidden" value="<?php echo $team_id; ?>">
            <?php echo text_input("Name", "name", $name);?>
            <?php echo text_input("Notes", "notes", $notes);?>
            <?php echo display_submit_cancel("teams.php"); ?>
        </form>
    </div>
        <div class="col s12">
            <a href="<?php echo url_for('teams.php'); ?>">Back to Teams</a>
        </div>
</div>
<?php
include include_url_for('footer.php');
?>