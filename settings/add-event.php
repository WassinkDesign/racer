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
    $general_err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ($result = $conn->query("SELECT id, name FROM location")) {
            while ($obj = $result->fetch_object()) {
                $curLocation = array($obj->id, $obj->name);
                array_push($locations, $curLocation);
            }
            $result->close();
        }

        $name = trim($_POST["name"]);
        $desc = trim($_POST["desc"]);
        $location_id = trim($_POST["location"]);
        $startDate = trim($_POST["startDate"]);
        $endDate = trim($_POST["endDate"]);
        
        if ($name == "") {
            $general_err = "Please include a name for the event";
        }

        $startDate = formatDateSave($startDate);
        $endDate = formatDateSave($endDate);

        $stmt = $conn->prepare("INSERT INTO event (name, description, location, start_date, end_date) VALUES (?,?,?,?,?)");

        if ($stmt &&
                $stmt->bind_param("ssiss", $name, $desc, $location_id, $startDate, $endDate ) &&
                $stmt->execute()) {
            $success = true;
            $name = $desc = "";
            $location_id = $startDate = $endDate = 0;

            $stmt->close();

            redirect_to(url_for('settings/events.php'));
        } else {
            $general_err = "Error adding event information. Please try again later.";
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

    $addURL = "";
    $title = "Add Event";
    include(include_url_for('header.php'));

    if ($success === false) {
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
        <div class="row">
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

            <label class="col s12" for="name">Name</label>
            <input id="name" name="name" type="text" value="<?php echo $name; ?>">

            <label class="col s12" for="desc">Description</label>
            <input id="desc" name="desc" type="text" value="<?php echo $desc; ?>">

            <label class="col s12" for="startDate">Start Date</label>
            <div style="">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <input id="startDate" class="showDate" name="startDate" value="<?php echo $startDate;?>">
                        </div>
                    </div>
                </div>
            </div>

            <label class="col s12" for="endDate">End Date</label>
            <div style="">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <input id="endDate" class="showDate" name="endDate" value="<?php echo $endDate;?>">
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