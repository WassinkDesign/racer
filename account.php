<?php 
    require_once("control/init.php");
    
    if ($signedIn === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $person_id = $_SESSION["id"];
    $address_id = 0;
    $team_id = 0;

    $email = $name = $phone = $address = $city = $pcode = $team = "";
    $email_err = $name_err = $phone_err = $address_err = $city_err = $pcode_err = "";
    $general_err = "";
    $update_success = "";
    $teams = [];

    if ($result = $conn->query("SELECT id, name FROM team")) {
        while ($obj = $result->fetch_object()) {
            $curTeam = array($obj->id, $obj->name);
            array_push($teams, $curTeam);
        }
        $result->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = trim($_POST["name"]);
        $phone = trim($_POST["phone"]);
        $address = trim($_POST["address"]);
        $city = trim($_POST["city"]);
        $pcode = trim($_POST["pcode"]);
        $email = trim($_POST["email"]);
        $team_id = trim($_POST["team-name"]);

        $updatePersonStmt = $conn->prepare("UPDATE address a, person p, driver d SET a.address = ?, a.city = ?, a.postalCode = ?, p.name = ?, p.phone = ?, p.email = ?, d.team = ? WHERE a.id = p.address AND p.id = ? and p.id=d.person");
        
        if ($updatePersonStmt && 
                $updatePersonStmt->bind_param('ssssssii', $address, $city, $pcode, $name, $phone, $email, $team_id, $person_id) &&
                $updatePersonStmt->execute()) {
            $update_success = true;
        } else {
            $update_success = false;
            $general_err = "Error updating your information.  Please try again later.";
        }
        
        $getDetailsStmt = $conn->prepare("SELECT a.address, a.city, a.postalCode, p.name, p.phone, p.email, d.team FROM person p, address a, driver d WHERE p.id = ? and p.address = a.id and p.id = d.person");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $person_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($address, $city, $pcode, $name, $phone, $email, $team_id) &&
                $getDetailsStmt->fetch()) {
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
        
    } else {
        $getDetailsStmt = $conn->prepare("SELECT a.address, a.city, a.postalCode, p.name, p.phone, p.email, d.team FROM person p, address a, driver d WHERE p.id = ? and p.address = a.id and p.id = d.person");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $person_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($address, $city, $pcode, $name, $phone, $email, $team_id) &&
                $getDetailsStmt->fetch()) {
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
    }

    $title = "Account";
    include(url_for('header.php'));

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
    <h2 class="header center orange-text">Account</h2>
    <div class="row">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <div class="input-field">
                <select id="team-name" name="team-name" class="browser-default col s12 m8">
                    <option value="" disabled selected>Choose your team</option>
                    <?php
                        foreach ($teams as $team) {
                            echo "<option value=\"$team[0]\"";
                            if ((int)$team[0] === $team_id) {echo " selected ";}
                            echo ">$team[1]</option>";
                        }
                    ?>
                </select>
                
                <div class="col s12 m4 ">
                    <a href="update-team.php" class="col s12 btn yellow lighten-3 black-text waves-effect waves-light">Update Team</a>
                    <a href="add-team.php" class="col s12 btn green lighten-3 black-text waves-effect waves-light">New Team</a>
                </div>
            </div>
            <div class="input-field col s12">
                <input id="name" name="name" type="text" value="<?php echo $name; ?>">
                <label for="name">Name</label>
            </div>
            <div class="input-field col s12">
                <input id="email" name="email" type="text" value="<?php echo $email; ?>">
                <label for="email">Email</label>
            </div>
            <div class="input-field col s12">
                <input id="phone" name="phone" type="text" value="<?php echo $phone; ?>">
                <label for="phone">Phone</label>
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
                <input id="pcode" name="pcode" type="text" value="<?php echo $pcode; ?>">
                <label for="pcode">Postal Code</label>
            </div>
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Save</a>
                <a class="btn-small blue-grey lighten-5 black-text" onclick="document.forms[0].reset();">Cancel</a>
            </div>
        </form>
    </div>
    <div class="row">             
        <a href="<?php echo url_for('reset-password.php');?>" class="btn-small blue-grey darken-2 white-text waves-effect waves-light">Reset Password</a>
    </div>
</div>
<?php
    include(url_for('footer.php'));
?>