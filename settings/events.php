<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$name = $desc = "";
$location_id = 0;
$locations = [];
$startDate = $endDate = "";
$events = [];
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($result = $conn->query("SELECT id, name FROM location")) {
        while ($obj = $result->fetch_object()) {
            $curLocation = array($obj->id, $obj->name);
            array_push($locations, $curLocation);
        }
        $result->close();
    }

    $name = trim($_GET["name"]);
    $desc = trim($_GET["desc"]);

    if ($name == "") {
        $error = "Please include a name for the event";
    }

    if ($desc == "") {
        $desc = " ";
    }

    $location_id = trim($_GET["location"]);
    $startDate = trim($_GET["startDate"]);
    $endDate = trim($_GET["endDate"]);

    $stmt = $conn->prepare("INSERT INTO event (name, description, location, start_date, end_date) VALUES (?,?,?,?,?)");

    if ($stmt &&
            $stmt->bind_param("ssiii", $name, $desc, $location_id, $startDate, $endDate ) &&
            $stmt->execute()) {
        $success = true;
        $name = $desc = "";
        $location_id = $startDate = $endDate = 0;

        $stmt->close();
    } else {
        $error = "Error adding event information. Please try again later.";
    }
} else {
    if ($result = $conn->query("SELECT id, name FROM location")) {
        while ($obj = $result->fetch_object()) {
            $curLocation = array($obj->id, $obj->name);
            array_push($locations, $curLocation);
        }
        $result->close();
    }        
}

if ($result = $conn->query("SELECT event.id, event.name, event.description, l.name as location, start_date, end_date FROM event, location l WHERE event.location = l.id")) {
    while ($obj = $result->fetch_object()) {
        $curEvent = array($obj->id, $obj->name, $obj->description, $obj->location, $obj->start_date, $obj->end_date);
        array_push($events, $curEvent);
    }
    $result->close();
}

$addURL = "settings/add-event.php";
$title = "Events";
include(include_url_for('header.php')); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($success === true) {
        echo "<div class=\"row alert-dismissible green darken-4 white-text z-depth-1 \" id=\"alert-div\">        
                <div class=\"col s10\">
                Your information has been successfully added.
                </div>
                <div class=\"col s2\">
                    <a class=\"btn green darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
                </div>
            </div>";
    } else {
        echo "<div class=\"row alert-dismissible red darken-4 white-text z-depth-1 \" id=\"alert-div\">        
                <div class=\"col s10\">
                $error
                </div>
                <div class=\"col s2\">
                    <a class=\"btn red darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
                </div>
            </div>";
    }
}
?>
<div class="container">
    <h2 class="header center orange-text">Events</h2>
    <form class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
        <div class="row">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" value="<?php echo $name; ?>">
                <label for="name">Name</label>
            </div>
            <div class="input-field">
                <select id="location" name="location" class="browser-default col s12 m9">
                    <option value="" disabled selected>Choose your location</option>
                    <?php
                        foreach ($locations as $location) {
                            echo "<option value=\"$location[0]\"";
                            if ((int)$location[0] === (int)$location_id) {echo " selected ";}
                            echo ">$location[1]</option>";
                        }
                    ?>
                </select>
                <a href='<?php echo url_for('settings/locations.php');?>' class="col s12 m3 btn yellow lighten-2 black-text waves-effect waves-light">Locations</a>
            </div>
            <div class="input-field col s12">
                <input id="desc" name="desc" type="text" value="<?php echo $desc; ?>">
                <label for="desc">Description</label>
            </div>
            <div class="input-field col s12">
                <input id="startDate" name="startDate" type="text" class="datepicker" value="<?php echo $startDate; ?>">
                <label for="startDate">Start Date</label>
            </div>
            <div class="input-field col s12">
                <input id="endDate" name="endDate" type="text" class="datepicker" value="<?php echo $endDate; ?>">
                <label for="endDate">End Date</label>
            </div>
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Submit</a>
                <a class="btn blue-grey lighten-5 black-text" onclick="document.forms[0].reset();">Cancel</a>
            </div>
        </div>
    </form>
<div class="section">
    <div class="row">
        <?php

        // events
        // id, name, description, location, start-date, end-date
        foreach ($events as $event) {
            echo "<div class=\"col s12 m12 l6\">
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"{$event['id']}\">
                            <span class=\"card-title activator grey-text text-darken-4\">{$event['name']}<i class=\"material-icons right\">more_vert</i></span>
                            <p>{$event['start-date']} - {$event['end-date']}<br/>
                                {$event['location']}</p>
                            <p>{$event['description']}</p>
                        </div>
                    </div>
                </div>";
        }
        ?>
    </div>
</div>
</div>
<?php include(include_url_for('footer.php')); ?>