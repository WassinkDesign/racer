<?php
require_once "control/init.php";

if ($signedIn === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$person_id = $_SESSION["id"];
$address_id = $team_id = 0;
$email = $name = $phone = $address = $city = $prov = $team = " ";
$general_err = $update_success = "";

$person = get_person($conn, $person_id);
$team_id = $person["TEAM"];

if (get_person($conn, $person_id) === false) {
    $general_err = "Error retrieving your information.  Please try again later.";
} else {
    $name = $person["NAME"];
    $email = $person["EMAIL"];
    $phone = $person["PHONE"];
    $address = $person["ADDRESS"];
    $city = $person["CITY"];
    $prov = $person["PROV"];
}

$teams = get_teams($conn);
$vehicles = get_vehicles_person_display($conn, $person_id);

if (is_post_request() === true) {
    $name = h($_POST["name"]);
    $phone = h($_POST["phone"]);
    $address = h($_POST["address"]);
    $city = h($_POST["city"]);
    $prov = h($_POST["prov"]);
    $email = h($_POST["email"]);
    $team_id = h($_POST["team-name"]);

    if (update_person($conn, $person_id, $address, $city, $prov, $name, $phone, $email, $team_id)) {
        $update_success = true;
    } else {
        $update_success = false;
        $general_err = "Error updating your information.  Please try again later.";
    }
}

$person = get_person($conn, $person_id);

if (get_person($conn, $person_id) === false) {
    $general_err = "Error retrieving your information.  Please try again later.";
} else {
    $name = $person["NAME"];
    $email = $person["EMAIL"];
    $phone = $person["PHONE"];
    $address = $person["ADDRESS"];
    $city = $person["CITY"];
    $prov = $person["PROV"];
}

$addURL = "";
$title = "Account";
include include_url_for('header.php');

if ($update_success === true) { echo display_update(); } elseif ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form action="<?php echo h($_SERVER["PHP_SELF"]); ?>" method = "post">
            <?php echo dropdown_input("Team", "team-name", "Team", true, $teams, "ID", "NAME", $team_id, "teams.php", "Teams", "Edit");?>
            <?php echo text_input("Name", "name", $name);?>
            <?php echo text_input("Email", "email", $email);?>
            <?php echo text_input("Phone", "phone", $phone);?>
            <?php echo text_input("Address", "address", $address);?>
            <?php echo text_input("City", "city", $city);?>
            <?php echo text_input("Province", "prov", $prov);?>
            <?php echo display_submit_cancel("index.php"); ?>
        </form>
    </div>
    <div class="row">
        <a href="<?php echo url_for('reset-password.php'); ?>" class="btn-small grey darken-3 white-text waves-effect waves-light">Reset Password</a>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>