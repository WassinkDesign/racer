<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $name = $desc = $startDate = $endDate = "";
    $location_id = 0;    
    $success = "";
    $general_err = "";
    
    $locations = get_locations($conn);

    if (is_post_request() === true)
    {
        $name = h($_POST["name"]);
        $desc = h($_POST["desc"]);
        $location_id = h($_POST["location"]);
        $startDate = h($_POST["startDate"]);
        $endDate = h($_POST["endDate"]);
        
        if ($name == "") {
            $general_err = "Please include a name for the event";
        } else {
            if (insert_event($conn, $name, $desc, $location_id, $startDate, $endDate) !== false) {
                redirect_to(url_for('settings/events.php'));
            } else {
                $general_err = "Error adding event information. Please try again later.";
            }
        }
    }    

    $addURL = "";
    $title = "Add Event";
    include(include_url_for('header.php'));

    if ($success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
    <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
        <div class="row">
            <?php echo dropdown_input('Location', 'location', 'location', true, $locations, 'ID', 'NAME', $location_id, 'settings/locations.php', 'Locations');?>
            <?php echo text_input('Name', 'name', $name);?>
            <?php echo text_input('Description', 'desc', $desc);?>
            <?php echo date_input('Start Date', 'startDate', $startDate);?>
            <?php echo date_input('End Date', 'endDate', $endDate);?>
            <?php echo display_submit_cancel("settings/events.php"); ?>
        </div>
    </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/events.php');?>">Back to Events</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>
