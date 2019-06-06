<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $name = $address = $city = $prov = "";
    $address_id = 0;
    $general_err = "";
    $update_success = "";

    $event_id = 0;
    $event_selected = false;
    $events = [];
    $classes = [];
    $types = [];

    if (isset($_GET["event"])) {
        $event_id = htmlentities(trim($_GET["event"]));
        $event_selected = true;
    
        if ($result = $conn->query("SELECT name, start_date, end_date FROM event WHERE event.id = $event_id")) {
            while ($obj = $result->fetch_object()) {
                $events = array($obj->name, $obj->start_date, $obj->end_date); 
            }
        }
    } else {
        if ($result = $conn->query("SELECT id, name, start_date, end_date FROM event")) {
            while ($obj = $result->fetch_object()) {
                $curEvent = array($obj->id, $obj->name, $obj->start_date, $obj->end_date); 
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
        $type_id = 0;
        $class_id = 0;
        $name = htmlentities(trim($_POST["name"]));
        $address = htmlentities(trim($_POST["address"]));
        $city = htmlentities(trim($_POST["city"]));
        $prov = htmlentities(trim($_POST["prov"]));

        $addAddressStmt = $conn->prepare("INSERT INTO address (address, city, prov) VALUES (?, ?, ?)");

        if ($addAddressStmt && 
                $addAddressStmt->bind_param('sss', $address, $city, $prov) &&
                $addAddressStmt->execute()) {
            $update_success = true;
            $address = $city = $prov = "";

            $address_id = $addAddressStmt->insert_id;
            $addAddressStmt->close();

            $addLocationStmt = $conn->prepare("INSERT INTO location (name, address) VALUES (?, ?)");

            if ($addLocationStmt &&
                    $addLocationStmt->bind_param('si', $name, $address_id) &&
                    $addLocationStmt->execute()) {
                $update_success = true;
                $name = "";
                redirect_to(url_for("settings/locations.php"));
            } else {
                $update_success = false;
                $general_err = "Error adding location information. Please try again later.";
            }
        } else {
            $update_success = false;
            $general_err = "Error adding address information.  Please try again later.";
        }
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
    <h2 class="header center orange-text">Add Race</h2>
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <input id="race_id" name="race_id" type="hidden" value="<?php echo $race_id?>">
                <?php 
                    if ($event_selected === true) {
                        ?>
                        <label for="event" class="col s12">Event</label>
                        <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id;?>">
                        <input id="event" class="col s12" name="event" type="text" disabled value="<?php echo "$events[0]: $events[1] - $events[2]";?>">
                        <?
                    } else {
                ?>
                        <select id="event" name="event" class="browser-default col s12 m8">
                            <option value="" disabled selected>Choose the event</option>
                            <?php
                                foreach ($events as $event) {
                                    echo "<option value=\"$event[0]\"";
                                    if ((int)$event[0] === $event_id) {echo " selected ";}
                                    echo ">$event[1]</option>";
                                }
                            ?>
                        </select>
                        <a href="<?php echo url_for('settings/events.php');?>" class="col s12 m4 btn blue-grey lighten-2 black-text waves-effect waves-light">Events</a>
                <?php } ?>
                <label class="col s12" for="class">Race Type</label>
                <select id="type" name="type" class="browser-default col s12">
                    <option value="" disabled selected>Choose the Race Type</option>
                    <?php
                        foreach ($types as $type) {
                            echo "<option value=\"$type[0]\"";
                            if ((int)$type[0] === $type_id) {echo " selected ";}
                            echo ">$type[1]</option>";
                        }
                    ?>
                </select>
                <label class="col s12" for="class">Race Class</label>
                <select id="class" name="class" class="browser-default col s12">
                    <option value="" disabled selected>Choose the Class</option>
                    <?php
                        foreach ($classes as $class) {
                            echo "<option value=\"$class[0]\"";
                            if ((int)$class[0] === $class_id) {echo " selected ";}
                            echo ">$class[1]</option>";
                        }
                    ?>
                </select>
                <label class="col s12" for="date_picker">Date</label>
                <input class="col s12" id="date_picker" name="date" type="text" class="" value="<?php echo $date; ?>">
                <label for="time" class="col s12">Time</label>
                <input id="time_picker" name="time" type="text" class=" col s12" value="<?php echo $time; ?>">
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