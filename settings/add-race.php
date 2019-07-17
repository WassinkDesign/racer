<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    // event info
    $event_id = -1;

    $race_type_id = 0;
    
    $race_class_id = 0;

    // race info
    $date = $time = $description = "";

    // housekeeping
    $success = $general_err = "";
    
    if (is_post_request() === true) {
        $event_id = h($_POST['event_id']);
    } else if (isset($_GET["event"])) {
        $event_id = h($_GET["event"]);
    } else {
        redirect_to(url_for('settings/events.php'));
        exit;
    }

    $event = get_event($conn, $event_id);
    $race_classes = get_race_classes($conn);
    $race_types = get_race_types($conn);

    if (is_post_request() === true)
    {
        $event_id = h($_POST['event_id']);
        $race_type_id = h($_POST['type']);
        $race_class_id = h($_POST['class']);
        $date = h($_POST['date']);
        $time = h($_POST['time']);
        $description = h($_POST['desc']);
        
        if (insert_race($conn, $event_id, $race_type_id, $race_class_id, $date, $time, $description) === false) {
            $general_err = "Error adding event information. Please try again later.";
            $success = false;
        } else {
            redirect_to(url_for('settings/races.php')."?event=$event_id");
        }
    }

    $addURL = "";
    $title = "Add Race";
    include(include_url_for('header.php'));

    if ($success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
    <?php 
    if ($event_id != -1) {
        echo "
            <div class=\"line\">
                <div class=\"\" id=\"$event_id\">
                    <span class=\"line-title\">$event_name</span>
                    <p>".formatDateDisplay($event_start) . " - " . formatDateDisplay($event_end) . "</p>
                </div>
            </div>
        ";
    }
    ?>
    <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
        <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id;?>">
        <div class="row">
            <?php echo dropdown_input('Race Type', 'type', 'type', true, $race_types, 'ID', 'NAME', $race_type_id, 'settings/types.php', 'Race Types');?>
            <?php echo dropdown_input('Race Class', 'class', 'class', false, $race_classes, 'ID', 'NAME', $race_class_id, 'settings/classes.php', 'Race Classes');?>
            <?php echo text_input('Description', 'desc', $description);?>
            <?php echo date_input('Date', 'date', $date);?>
            <?php echo time_input('Time', 'time', $time);?>            
            <?php echo display_submit_cancel("settings/races.php"); ?>
        </div>
    </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/races.php') . "?event=$event_id";?>">Back to Races</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>