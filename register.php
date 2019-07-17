<?php
require_once("control/init.php");

if ($signedIn === false){
    redirect_to(url_for("login.php"));
    exit;
}

if (!isset($_SESSION["id"])) {
    redirect_to(url_for("login.php"));
    exit;
}

$event_id = 0;
$person_id = $_SESSION["id"];

if (isset($_GET["event"])) {
    $event_id = $_GET["event"];
} elseif (is_post_request() != true) {
    redirect_to(url_for("index.php"));
    exit;
} else {
    $event_id = trim($_POST["event_id"]);
}

$event = get_event($conn, $event_id);

if ($event === false) {
    $general_err = "Error retrieving information. Please try again later.";
}  

$vehicles = get_vehicles_person_display($conn, $person_id);
$registrations = get_registrations_person_event($conn,$person_id, $event_id);
$person = get_person($conn, $person_id);

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if (isset($_POST['vehicleVar'])) 
    {
        $checkedVehicles = $_POST['vehicleVar'];
        foreach($checkedVehicles as $checkedVehicle) {
            $exists = false;

            foreach($registrations as $registration) {
                if ($registration["VEHICLE"] == $checkedVehicle) {
                    $exists = true;
                }
            }

            if ($exists === false) {
                if (insert_registration($conn, $checkedVehicle, $event_id) === false) {
                    $success = false;
                    $general_err = "Error saving information. Please try again later.";
                }
            }            
        }

        // check if we need to delete
        foreach($registrations as $registration) {       
            $exists = false;

            foreach($checkedVehicles as $checkedVehicle) {
                if ($checkedVehicle == $registration["VEHICLE"]) {
                    $exists = true;
                }
            }

            if ($exists === false) {
                if (delete_registration($conn, $registration["ID"]) === false) {
                    $success = false;
                    $general_err = "Error removing a vehicle from registration.";
                }
            }
        }
    } else {
        // nothing was checked - make sure to delete any registrations
        foreach($registrations as $registration) {
            if (delete_registration($conn, $registration["ID"]) === false) {
                $success = false;
                $general_err = "Error removing a vehicle from registration.";
            }
        }
    }
}

$addURL = "";
$title="Registration";
include(include_url_for('header.php')); 

if ($success === true) { echo display_update(); } elseif ($success === false) { echo display_error($general_err); }
?>

<div class="container">
    <div class="line no-border">
        <div id="<?php echo $event["ID"]; ?>">
            <span class="line-title"><?php echo $event['NAME'];?></span>
            <p><?php echo "{$event['LOCATION']} <br/> {$event['DESCRIPTION']}";?></p>
            <p><?php echo "{$event['START_DATE']} - {$event['END_DATE']}";?></p>
        </div>
    </div>
    <div class="section line no-border">
        <table>
            <tbody>
            <?php 
            echo table_horz_row("Name", $person["NAME"]);
            echo table_horz_row("Team", $person["TEAM"]);
            echo table_horz_row("Email", $person["EMAIL"]);
            echo table_horz_row("Phone", $person["PHONE"]);
            echo table_horz_row("Address", "{$person['ADDRESS']}, {$person['CITY']} {$person['PROV']}");
            ?>
            </tbody>
        </table>
        <br/>
        <a href="<?php echo url_for('account.php');?>" class="col right waves-effect waves-light">Edit Account</a>
        <br/>
    </div>
    <div class="container">
        <div class="">
            <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input id="event_id" name="event_id" type="hidden" value="<?php echo $event_id;?>">
                <label class="col s12" for="vehicle">Vehicles</label>
                <br/><a href='<?php echo url_for('vehicles.php');?>' class="col right waves-effect waves-light"><span class="right small-caps">Edit Vehicles</span></a><br/>

                <?php 
                foreach($vehicles as $vehicle) {?>
                <p>
                    <label>
                        <input type="checkbox" id="<?php echo $vehicle["ID"]; ?>" name="vehicleVar[]" value="<?php echo $vehicle["ID"];?>" <?php
                            foreach($registrations as $registration) {
                                    if ($registration["VEHICLE"] === $vehicle["ID"]) {
                                        echo 'checked';
                                    }
                            }
                        ?>/>
                        <span><?php echo "{$vehicle["CLASS"]} - #{$vehicle["NUMBER"]}";?></span>
                    </label>
                </p>
                <?php }
                ?>


                <p>Your registration will not be final until you have attended any manditory meetings for the <?php echo $event["NAME"];?> event, have signed all waivers, and have paid the registration fee(s).<br/>
                Registering for the event does not guarantee that any races will be running for your vehicle's class.</p>

                <?php echo display_submit_cancel("index.php"); ?>
            </form>
        </div>
    </div>
</div>
<?php include(include_url_for('footer.php'));?>