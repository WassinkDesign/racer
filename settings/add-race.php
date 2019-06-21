<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $date = $time = $description = "";
    $address_id = 0;
    $general_err = "";
    $update_success = "";

    $event_id = $class_id = $type_id = 0;
    $eName = $eDesc = $eLoc = $eStart = $eEnd = "";
    $event_selected = false;
    $events = [];
    $classes = [];
    $types = [];

    if (isset($_GET["event"])) {
        $event_id = htmlentities(trim($_GET["event"]));        
        
        $eventStmt = $con->prepare("SELECT e.name as name, e.description as description, l.name as location, e.start_date as start_date, e.end_date as end_date FROM event e, location l WHERE e.location = l.id AND e.id = ?");
        
        if ($eventStmt &&
            $eventStmt->bind_param('i', $event_id) &&
            $eventStmt->execute() &&
            $eventStmt->store_result() &&
            $eventStmt->bind_result($eName, $eDesc, $eLoc, $eStart, $eEnd) && 
            $eventStmt->fetch()) {
                $event_selected = true;
            } else {
                $event_selected = false;
            }
    } 
    if ($event_selected === false) {
        if ($result = $conn->query("SELECT e.name as name, e.description as description, l.name as location, e.start_date as start_date, e.end_date as end_date FROM event e, location l WHERE e.location = l.id ORDER BY e.start_date")) {
            while ($obj = $result->fetch_object()) {
                $curEvent = array($obj->name, $obj->description, $obj->location, $obj->start_date, $obj->end_date); 
                array_push($events, $curEvent);
            }
        }
    }

    if ($result = $conn->query("SELECT id, name FROM race_class")) {
        while ($obj = $result->fetch_object()) {
            $curClass = array($obj->id, $obj->name); 
            array_push($classes, $curClass);
        }
    }

    if ($result = $conn->query("SELECT id, name, points FROM race_type")) {
        while ($obj = $result->fetch_object()) {
            $curType = array($obj->id, $obj->name, $obj->points); 
            array_push($types, $curType);
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $type_id = $_POST['type'];
        $class_id = $_POST['class'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $description = $_POST['description'];
        $points = $_POST['points'];
        
        $date = formatDateSave($date);
        $time = formatTimeSave($time);

        $stmt = $conn->prepare("INSERT INTO race");
    }

    $addURL = "";
    $title = "Add Race";
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
    <div class="row">
        <?php 
            if ($event_selected === true) {
                echo "
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"$event_id\">
                            <span class=\"card-title grey-text text-darken-4\">$events[0]</span>
                            <p>$events[2]</p>
                            <p>" . formatDateDisplay($events[3]) . " - " . formatDateDisplay($events[4]) . "</p>
                        </div>
                    </div>
                ";
            }
        ?>
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <input id="race_id" name="race_id" type="hidden" value="<?php echo $race_id?>">
                <?php 
                    if ($event_selected === true) {
                        
                    } else {
                ?>
                        <label class="col s12" for="event">Event</label>
                        <select id="event" name="event" class="browser-default col s12">
                            <option value="" disabled selected>Choose the event</option>
                            <?php
                                foreach ($events as $event) {
                                    echo "<option value=\"$event[0]\"";
                                    if ((int)$event[0] === $event_id) {echo " selected ";}
                                    echo ">$event[1]</option>";
                                }
                            ?>
                        </select>
                        <a href='<?php echo url_for('settings/events.php');?>' class="col s12 waves-effect waves-light"><span class="right small-caps">Edit Events</span></a>

                <?php } ?>
                
                <label class="col s12" for="class">Race Type</label>
                <select id="type" name="type" class="browser-default col s12" autofocus>
                    <option value="" disabled selected>Choose the Race Type</option>
                    <?php
                        foreach ($types as $type) {
                            echo "<option value=\"$type[0]\"";
                            if ((int)$type[0] === $type_id) {echo " selected ";}
                            echo ">$type[1]</option>";
                        }
                    ?>
                </select>
                <a href='<?php echo url_for('settings/types.php');?>' class="col s12 waves-effect waves-light"><span class="right small-caps">Edit Race Types</span></a>


                <label class="col s12" for="class">Race Class</label>
                <select id="class" name="class" class="browser-default">
                    <option value="" disabled selected>Choose your Race Class</option>
                    <?php
                        foreach ($classes as $class) {
                            echo "<option value=\"$class[0]\"";
                            if ((int)$class[0] === (int)$class_id) {echo " selected ";}
                            echo ">$class[1]</option>";
                        }
                    ?>
                </select>
                <a href='<?php echo url_for('settings/classes.php');?>' class="col s12 waves-effect waves-light"><span class="right small-caps">Edit Classes</span></a>

                <label class="col s12" for="date">Date</label>
                <div style="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input id="date" class="showDate" name="date" value="<?php echo $date;?>">
                            </div>
                        </div>
                    </div>
                </div>

                <label class="col s12" for="time">Time</label>
                <div style="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input id="time" class="showTime" name="time" value="<?php echo $time;?>">
                            </div>
                        </div>
                    </div>
                </div>

                <label for="description" class="col s12">Description</label>
                <input id="description" class="col s12" name="description" type="text" value="<?php echo $description; ?>">

                <select id="points" name="points" class="browser-default col s12">
                    <option value="" disabled selected>Points Awarded</option>
                    <option value=1 <?php if ((int)$points == 1) {echo " selected ";} ?>>YES</option>
                    <option value=0 <?php if ((int)$points == 0) {echo " selected ";} ?>>NO</option>                    
                </select>
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Save</a>
                <a class="btn blue-grey lighten-5 black-text" onclick="window.history.back();">Cancel</a>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/races.php');?>">Back to Races</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>