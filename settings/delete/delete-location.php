<?php 
require_once("../../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $location_id = 0;

    if (isset($_GET["location"])) {
        $location_id = $_GET["location"];
    } elseif($_SERVER["REQUEST_METHOD"] != "POST") {
        redirect_to(url_for("settings/locations.php"));
        exit;
    }
    
    $city = $name = $address = $prov = "";
    $locations = [];
    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $address = htmlentities(trim($_POST["desc"]));
        $city = htmlentities(trim($_POST["location"]));
        $prov = htmlentities(trim($_POST["startDate"]));
        $endDate = htmlentities(trim($_POST["endDate"]));
        $location_id = trim($_POST["location_id"]);

        if ($name == "") {$name = " ";}

        $deletelocationStmt = $conn->prepare("
            DELETE FROM location 
            WHERE id = ?");

        if ($deletelocationStmt && 
                $deletelocationStmt->bind_param('i', $city_id) &&
                $deletelocationStmt->execute()) {
            $update_success = true;
            redirect_to(url_for("settings/locations.php"));
        } else {
            $update_success = false;
            $general_err = "Error updating location information. Please try again later.";
        }
    }    

    $getDetailsStmt = $conn->prepare("SELECT 
            l.name as name,
            a.address as address,
            a.city as city,
            a.prov as prov
        FROM 
            location l, 
            address a
        WHERE l.address = a.id AND l.id = ?");

    if ($getDetailsStmt &&
            $getDetailsStmt->bind_param('i', $location_id) &&
            $getDetailsStmt->execute() &&
            $getDetailsStmt->store_result() &&
            $getDetailsStmt->bind_result($name, $address, $city, $prov) &&
            $getDetailsStmt->fetch()) {
        
    } else {
        $general_err = "Error retrieving your information.  Please try again later.";
    }

    $addURL = "";
    $title = "Delete location";
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
    <h2 class="header center deep-orange-text">Delete location</h2> 
    <div class="section">
        <div class="col s12">
            <div class="card">
                <div class="card-content" id="<?php echo $location_id;?>">
                    <span class="card-title grey-text text-darken-4\"><?php echo $name;?></span>
                    <p><?php echo $address;?><br/>
                    <?php echo $city;?>, <?php echo $prov;?></p>
                    <div class="divider"></div>
                    <p><span class="verify">Are you sure you want to delete this location?</span></p>
                    <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input id="location_id" name="location_id" type="hidden" value="<?php echo $location_id;?>">
                        <div class="input-field col s12">
                            <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Yes</a>
                            <a class="btn blue-grey lighten-5 black-text" href="<?php echo url_for('settings/locations.php');?>">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/locations.php');?>">Back to locations</a>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>