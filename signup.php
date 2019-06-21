<?php
require_once("control/init.php");

if ($signedIn === true){
    redirect_to(url_for("index.php"));
    exit;
}

$email = $password = $confirm_password = $name = $phone = $address = $city = $prov = $team_id = "";
$email_err = $password_err = $confirm_password_err = $name_err = $phone_err = $address_err = $city_err = $prov_err = "";
$teams = [];
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if ($result = $conn->query("SELECT id, name FROM team")) {
        while ($obj = $result->fetch_object()) {
            $curTeam = array($obj->id, $obj->name);
            array_push($teams, $curTeam);
        }
        $result->close();
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        $sql = "SELECT id FROM person WHERE email = ?";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_email);
            
            $param_email = trim($_POST["email"]);
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "This email is already registered.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        $stmt->close();
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);

        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $city = trim($_POST["city"]);
    $prov = trim($_POST["prov"]);
    $team_id = trim($_POST["team-name"]);

    if ($address == "") {$address = " ";}
    if ($city == "") {$city = " ";}
    if ($phone == "") {$phone = " ";}
    if ($prov == "") {$prov = " ";}

    if((empty($email_err) || $email_err == "") && 
        (empty($password_err) || $password_err == "") && 
        (empty($confirm_password_err) || $confirm_password_err == "")){
        
            $address_id = 0;

        if ($address != "" || $city != "" || $prov != "") {
            $stmt = $conn->prepare("INSERT INTO address (address, city, prov) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $param_address, $param_city, $param_prov);

            $param_address = $address;
            $param_city = $city;
            $param_prov = $prov;

            if ($stmt->execute()) {
                $address_id = $stmt->insert_id;
            }

            $stmt->close();
        }

        $pStmt = $conn->prepare("INSERT INTO person (address, name, phone, email, password) VALUES (?, ?, ?, ?, ?)");
        $pStmt->bind_param("issss", $param_address, $param_name, $param_phone, $param_email, $param_password);
        $param_address = $address_id;
        $param_name = $name;
        $param_phone = $phone;
        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        if ($pStmt->execute()) {
            $person_id = $pStmt->insert_id;
            $dStmt = $conn->prepare("INSERT INTO driver (person, team) VALUES (?,?)");
            $dStmt->bind_param("ii", $person_id, $team_id);
            $dStmt->execute();
            session_start();

            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $person_id;
            $_SESSION["email"] = $email;                            

            redirect_to(url_for("index.php"));
        } else {
            echo "There were technical difficulties. I'm sorry. Try again later?";
        }
        $pStmt->close();
    }
    $conn->close();
} else {
    if ($result = $conn->query("SELECT id, name FROM team")) {
        while ($obj = $result->fetch_object()) {
            $curTeam = array($obj->id, $obj->name);            
            array_push($teams, $curTeam);
        }
        $result->close();
    }
}
?>
<?php

$addURL = "";
$title="Signup";
include(include_url_for('header.php')); 

$submit_err = "";

if ($email_err != "") { $submit_err .= $email_err . ' ';}
if ($password_err  != "") { $submit_err .= $password_err . ' ';}
if ($confirm_password_err  != "") { $submit_err .= $confirm_password_err . ' ';}
if ($name_err  != "") { $submit_err .= $name_err . ' ';}
if ($phone_err  != "") { $submit_err .= $phone_err . ' ';}
if ($address_err  != "") { $submit_err .= $address_err . ' ';}
if ($city_err  != "") { $submit_err .= $city_err . ' ';}
if ($prov_err != "") { $submit_err .= $prov_err . ' ';}

if ($submit_err !== "") {
        echo "
        <div class=\"row alert-dismissible red darken-4 white-text z-depth-1 \" id=\"alert-div\">        
            <div class=\"col s10\">
                $submit_err
            </div>
            <div class=\"col s2\">
                <a class=\"btn red darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
            </div>
        </div>";
    }
?>

<div class="container">
    <div class="row">
        <div class="col s12">
            <p>Already have an account? <a href="<?php echo url_for('login.php');?>">Login here</a></p>
        </div>
        <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <div class="row">
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
                    <a href="<?php echo url_for('add-team.php');?>" class="col s12 m4 btn blue-grey lighten-2 black-text waves-effect waves-light">New Team</a>
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
                    <input id="prov" name="prov" type="text" value="<?php echo $prov; ?>">
                    <label for="prov">Province</label>
                </div>
                <div class="input-field col s12">
                    <input id="password" name="password" type="password" value="<?php echo $password; ?>">
                    <label for="password">Password</label>
                </div>
                <div class="input-field col s12">
                    <input id="confirm_password" name="confirm_password" type="password" value="<?php echo $confirm_password; ?>">
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <div class="input-field col s12">
                    <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Submit</a>
                    <a class="btn blue-grey lighten-5 black-text" onclick="document.forms[0].reset();">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include(include_url_for('footer.php'));?>
?>
