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

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
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
    $title = "Add Location";
    include(include_url_for('header.php'));

    if ($update_success === true) {
        echo "<div class=\"row alert-dismissible green darken-4 white-text z-depth-1 \" id=\"alert-div\">        
                <div class=\"col s10\">
                Your information has been successfully added.
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
    <h2 class="header center orange-text">Add Location</h2>
    <div class="row">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
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
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>