<?php
require_once("control/init.php");

if ($signedIn === true){
    redirect_to(url_for("index.php"));
    exit;
}

$person = [];
$email = $password = $confirm_password = $name = $phone = $address = $city = $prov = $team_id = "";
$email_err = $password_err = $confirm_password_err = $name_err = $phone_err = $address_err = $city_err = $prov_err = "";
$teams = [];
 
if(is_post_request() === true){
    $teams = get_teams($conn);

    if(empty(h($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        $person = get_person_email($conn, h($_POST["email"]));
          
        if ($person === false) {
            $email = h($_POST["email"]);
        } else {
            $email_err = "This email is already registered.";
        }
    }

    if(empty(h($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(h($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = h($_POST["password"]);
    }

    if(empty(h($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = h($_POST["confirm_password"]);

        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    $name = h($_POST["name"]);
    $phone = h($_POST["phone"]);
    $address = h($_POST["address"]);
    $city = h($_POST["city"]);
    $prov = h($_POST["prov"]);
    $team_id = h($_POST["team-name"]);

    if ($address == "") {$address = " ";}
    if ($city == "") {$city = " ";}
    if ($phone == "") {$phone = " ";}
    if ($prov == "") {$prov = " ";}

    if((empty($email_err) || $email_err == "") && 
        (empty($password_err) || $password_err == "") && 
        (empty($confirm_password_err) || $confirm_password_err == "")){
        
            $address_id = 0;

        if ($address != "" || $city != "" || $prov != "") {
            $address_id = insert_address($conn, $address, $city, $prov);
        }

        if ($address_id !== false) {
            
        }

        $person_id = insert_person($conn, $address_id, $name, $phone, $email, password_hash($password, PASSWORD_DEFAULT));
        
        if ($person_id !== false) {
            session_start();

            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $person_id;
            $_SESSION["email"] = $email;                            

            redirect_to(url_for("index.php"));
        } else {
            echo "There were technical difficulties. I'm sorry. Try again later?";
        }
    }
} else {
    $teams = get_teams($conn);
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

if ($submit_err !== "") { echo display_error($submit_err); }
?>

<div class="container">
    <div class="row">
        <div class="col s12">
            <p>Already have an account? <a href="<?php echo url_for('login.php');?>">Login here</a></p>
        </div>
        <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <?php echo dropdown_input("Team", "team-name", "Team", true, $teams, "ID", "NAME", $team_id, "add-team.php", "Teams", "Add");?>
            <?php echo text_input("Name", "name", $name);?>
            <?php echo text_input("Email", "email", $email);?>
            <?php echo text_input("Phone", "phone", $phone);?>
            <?php echo text_input("Address", "address", $address);?>
            <?php echo text_input("City", "city", $city);?>
            <?php echo text_input("Province", "prov", $prov);?>
            <?php echo password_input("Password", "password");?>            
            <?php echo password_input("Confirm Password", "confirm_password");?>            
            <?php echo display_submit_cancel("index.php"); ?>
        </form>
    </div>
</div>

<?php include(include_url_for('footer.php'));?>
?>
