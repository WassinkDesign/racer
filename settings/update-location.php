<?php 
require_once("../control/init.php");

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
    
    $name = $address = $city = $prov = "";
    $address_id = 0;
    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $address = htmlentities(trim($_POST["address"]));
        $city = htmlentities(trim($_POST["city"]));
        $prov = htmlentities(trim($_POST["prov"]));
        $location_id = trim($_POST["location_id"]);

        if ($name == "") {$name = " ";}
        if ($address == "") {$address = " ";}
        if ($city == "") {$city = " ";}
        if ($prov == "") {$prov = " ";}

        $updateAddressStmt = $conn->prepare("UPDATE address a, location l SET a.address = ?, city = ?, prov = ? WHERE a.id = l.address AND l.id = ?");

        if ($updateAddressStmt && 
                $updateAddressStmt->bind_param('sssi', $address, $city, $prov, $location_id) &&
                $updateAddressStmt->execute()) {
            $update_success = true;

            $updateAddressStmt->close();
            
            $updateLocationStmt = $conn->prepare("UPDATE location SET name= ? WHERE id = ?");
            
            if ($updateLocationStmt &&
                    $updateLocationStmt->bind_param('si', $name, $location_id) &&
                    $updateLocationStmt->execute()) {
                $update_success = true;
                $updateLocationStmt->close();
            } else {
                $update_success = false;
                $general_err = "Error updating location information. Please try again later.";
            }

        } else {
            $update_success = false;
            $general_err = "Error updating address information. Please try again later.";
        }
        
        $getDetailsStmt = $conn->prepare("SELECT name, a.address, city, prov FROM location l, address a WHERE l.address = a.id AND l.id = ?");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $location_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($name, $address, $city, $prov) &&
                $getDetailsStmt->fetch()) {
        } else {
            $general_err = "Error retrieving information. Please try again later.";
        }
        
    } else {
        $getDetailsStmt = $conn->prepare("SELECT name, a.address, city, prov FROM location l, address a WHERE l.address = a.id AND l.id = ?");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $location_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($name, $address, $city, $prov) &&
                $getDetailsStmt->fetch()) {
            
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
    }

    $addURL = "";
    $title = "Update Location";
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
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <input id="location_id" name="location_id" type="hidden" value="<?php echo $location_id;?>">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" value="<?php echo $name; ?>">
                <label for="name">Name</label>
            </div>
            <div class="input-field col s12">
                    <input id="address" name="address" type="text" value="<?php echo $address; ?>">
                    <label for="address">Address</label>
            </div>
            <div class="input-field col s12">
                <input id="city" name="city" type="text" value="<?php echo $city; ?>">
                <label for="city">City</label>
            </div>
            <div class="input-field col s12">
                <input id="prov" name="prov" type="text" value="<?php echo $prov; ?>">
                <label for="prov">Province</label>
            </div>       
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Save</a>
                <a class="btn blue-grey lighten-5 black-text" onclick="window.history.back();">Cancel</a>
            </div>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/locations.php');?>">Back to Locations</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>
