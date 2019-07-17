<?php 
require_once("../../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $race_id = $event_id = 0;

    if (isset($_GET["race"])) {
        $race_id = $_GET["race"];
    } elseif(is_post_request() != true) {
        redirect_to(url_for("settings/events.php"));
        exit;
    }
    
    $update_success = "";
    $error = "";

    if (is_post_request() === true)
    {
        $race_id = trim($_POST["race_id"]);
        
        if (delete_race($conn, $race_id)) {
            $update_success = true;
            redirect_to(url_for("settings/races.php")."?event = $event_id");
        } else {
            $update_success = false;
            $general_err = "Error updating event information. Please try again later.";
        }
    }    

    $race = get_race($conn, $race_id);
    if ($race === false) {
        $general_err = "Error retrieving your information.  Please try again later.";
    }

    $addURL = "";
    $title = "Delete Race";
    include(include_url_for('header.php'));

    if ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="section">
        <div class="line no-border" id="<?php echo $race['ID']; ?>">
            <div class="line-title"><?php echo $event; ?></div>
            <p><?php echo $race["TYPE"];?><br/>
                <?php echo $race["CLASS"];?></p>
            <p><?php echo $race["DATE"];?> at <?php echo $race["TIME"];?></p>
            <div class="divider"></div>
            <p><span class="verify">Are you sure you want to delete this type?</span></p>
            <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input id="race_id" name="race_id" type="hidden" value="<?php echo $race["ID"];?>">
                <input id="event_id" name="event_id" type="hidden" value="<?php echo $race["EVENT_ID"];?>">
                <?php echo display_submit_cancel("settings/races.php?event=".$event_id); ?>
            </form>
        </div>
    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/races.php')."?event=$event_id";?>">Back to Races</a>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>