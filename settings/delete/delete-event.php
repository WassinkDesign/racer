<?php 
require_once("../../control/init.php");

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
    
    $location = $name = $desc = $startDate = $endDate = "";
    $events = [];
    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $desc = htmlentities(trim($_POST["desc"]));
        $location = htmlentities(trim($_POST["location"]));
        $startDate = htmlentities(trim($_POST["startDate"]));
        $endDate = htmlentities(trim($_POST["endDate"]));
        $event_id = trim($_POST["event_id"]);

        if ($name == "") {$name = " ";}

        $deleteEventStmt = $conn->prepare("
            DELETE FROM event 
            WHERE id = ?");

        if ($deleteEventStmt && 
                $deleteEventStmt->bind_param('i', $event_id) &&
                $deleteEventStmt->execute()) {
            $update_success = true;
            redirect_to(url_for("settings/events.php"));
        } else {
            $update_success = false;
            $general_err = "Error updating event information. Please try again later.";
        }
    }    

    $getDetailsStmt = $conn->prepare("SELECT e.name as name, e.description as description, l.name as location, e.start_date as start_date, e.end_date as end_date FROM event e, location l WHERE e.location = l.id AND e.id = ?");

    if ($getDetailsStmt &&
            $getDetailsStmt->bind_param('i', $event_id) &&
            $getDetailsStmt->execute() &&
            $getDetailsStmt->store_result() &&
            $getDetailsStmt->bind_result($name, $desc, $location, $startDate, $endDate) &&
            $getDetailsStmt->fetch()) {
        
    } else {
        $general_err = "Error retrieving your information.  Please try again later.";
    }

    $addURL = "";
    $title = "Delete Event";
    include(include_url_for('header.php'));

    if ($update_success === false) {
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
    <h2 class="header center deep-orange-text">Delete Event</h2> 
    <div class="section">
        <div class="col s12">
            <div class="card">
                <div class="card-content" id="<?php echo $event_id;?>">
                    <span class="card-title grey-text text-darken-4\"><?php echo $name;?></span>
                    <p><?php echo $location;?><br/>
                    <?php echo $desc;?></p>
                    <p><?php echo $startDate;?> - <?php echo $endDate;?></p>
                    <div class="divider"></div>
                    <p><span class="verify">Are you sure you want to delete this event?</span></p>
                    <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id;?>">
                        <div class="input-field col s12">
                            <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Yes</a>
                            <a class="btn blue-grey lighten-5 black-text" href="<?php echo url_for('settings/events.php');?>">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/events.php');?>">Back to Events</a>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>