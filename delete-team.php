<?php
require_once "control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$team_id = 0;
$general_err = "";
$success = true;

if (isset($_GET["team"])) {
    $team_id = $_GET["team"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("account.php"));
    exit;
}

$team = get_team($conn, $team_id);

if ($team === false) {
    $general_err = "Error retrieving information. Please try again later.";
    $success = false;
}

if (is_post_request() === true) {
    $team_id = h($_POST['team']);

    if (delete_team($conn, $team_id) === true) {
        redirect_to(url_for("teams.php"));        
    } else {
        $success = false;
        $general_err = "Error deleting information. Please try again later.";
    }
}

$addURL = "";
$title = "Delete Vehicle";
include include_url_for('header.php');

if ($success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="section">
        <div class="line no-border" id="<?php echo $team['ID']; ?>">
            <div class="line-title"><?php echo $team['NAME']; ?></div>
            <p><?php echo $team['NOTES'];?></p>
            <div class="divider"></div>
            <p><span class="verify">Are you sure you want to delete this team?</span></p>
            <form id="mainForm" class="col s12" action="<?php echo h($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input id="team" name="team" type="hidden" value="<?php echo $team['ID']; ?>">
                <?php echo display_submit_cancel("teams.php"); ?>
            </form>
        </div>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('teams.php'); ?>">Back to Teams</a>        
    </div>
</div>
<?php
include include_url_for('footer.php');
?>