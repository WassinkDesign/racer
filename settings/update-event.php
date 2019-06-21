<?php 
require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $event_id = 0;

    if (isset($_GET["event"])) {
        $event_id = $_GET["event"];
    } elseif($_SERVER["REQUEST_METHOD"] != "POST") {
        redirect_to(url_for("settings/events.php"));
        exit;
    }
    
    $name = $desc = $startDate = $endDate = "";
    $location_id = 0;
    $locations = [];    
    $events = [];
    $success = "";
    $error = "";

    if ($result = $conn->query("SELECT id, name FROM location")) {
        while ($obj = $result->fetch_object()) {
            $curLocation = array($obj->id, $obj->name);
            array_push($locations, $curLocation);
        }
        $result->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $desc = htmlentities(trim($_POST["desc"]));
        $location_id = htmlentities(trim($_POST["location"]));
        $startDate = htmlentities(trim($_POST["startDate"]));
        $endDate = htmlentities(trim($_POST["endDate"]));
        $event_id = trim($_POST["event_id"]);

        $startDate = formatDateSave($startDate);
        $endDate = formatDateSave($endDate);

        if ($name == "") {$name = " ";}

        $updateEventStmt = $conn->prepare("
            UPDATE event e
            SET 
                e.name = ?, 
                e.description = ?, 
                e.location = ?, 
                e.start_date = ?, 
                e.end_date = ? 
            WHERE id = ?");

        if ($updateEventStmt && 
                $updateEventStmt->bind_param('ssissi', $name, $desc, $location_id, $startDate, $endDate, $event_id) &&
                $updateEventStmt->execute()) {
            $update_success = true;
        } else {
            $update_success = false;
            $general_err = "Error updating event information. Please try again later.";
        }
        
        $updateEventStmt->close();

        $getDetailsStmt = $conn->prepare("SELECT name, description, location, start_date, end_date FROM event WHERE id = ?");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $location_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($name, $desc, $location_id, $startDate, $endDate) &&
                $getDetailsStmt->fetch()) {
        } else {
            $general_err = "Error retrieving information. Please try again later.";
        }        
    }    

    $getDetailsStmt = $conn->prepare("SELECT name, description, location, start_date, end_date FROM event WHERE id = ?");

    if ($getDetailsStmt &&
            $getDetailsStmt->bind_param('i', $event_id) &&
            $getDetailsStmt->execute() &&
            $getDetailsStmt->store_result() &&
            $getDetailsStmt->bind_result($name, $desc, $location_id, $startDate, $endDate) &&
            $getDetailsStmt->fetch()) {
        
    } else {
        $general_err = "Error retrieving your information.  Please try again later.";
    }

    $addURL = "";
    $title = "Update Event";
    include(include_url_for('header.php'));

    if ($update_success === true) {
        echo "<div class=\"row alert-dismissible green darken-4 white-text z-depth-1 \" id=\"alert-div\">        
                <div class=\"col s10\">
                Your information has been successfully updated.
                </div>
                <div class=\"col s2\">
                    <a class=\"btn green darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
                </div>
            </div>";
    } elseif ($update_success === false) {
        echo "<div class=\"row alert-dismissible red darken-4 white-text z-depth-1 \" id=\"alert-div\">        
                <div class=\"col s10\">
                $general_err
                </div>
                <div class=\"col s2\">
                    <a class=\"btn red darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
                </div>
            </div>";
    }
?>
<div class="container"> 
    <div class="row">
        <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
        <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id;?>">
            <div class="row">
                <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id;?>">

                <label class="col s12" for="location">Location</label>
                <select id="location" name="location" class="browser-default" autofocus>
                    <option value="" disabled selected>Choose your location</option>
                    <?php
                        foreach ($locations as $location) {
                            echo "<option value=\"$location[0]\"";
                            if ((int)$location[0] === (int)$location_id) {echo " selected ";}
                            echo ">$location[1]</option>";
                        }
                    ?>
                </select>
                <a href='<?php echo url_for('settings/locations.php');?>' class="col s12 waves-effect waves-light"><span class="right small-caps">Edit Locations</span></a>
                                                                
                <label for="name" class="col s12">Name</label>
                <input id="name" name="name" type="text" value="<?php echo $name; ?>">
                    
                <label class="col s12" for="desc">Description</label>
                <input id="desc" name="desc" type="text" value="<?php echo $desc; ?>">

                <label class="col s12" for="startDate">Start Date</label>
                <div style="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input id="startDate" class="showDate" name="startDate" value="<?php echo formatDateDisplay($startDate);?>">
                            </div>
                        </div>
                    </div>
                </div>

                <label class="col s12" for="endDate">End Date</label>
                <div style="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input id="endDate" class="showDate" name="endDate" value="<?php echo formatDateDisplay($endDate);?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="input-field col s12">
                    <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Submit</a>
                    <a class="btn blue-grey lighten-5 black-text" onclick="window.history.back();">Cancel</a>
                </div>
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