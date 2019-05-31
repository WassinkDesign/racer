<?php 
require_once("control/init.php");

    if ($signedIn === false){
        header("location: login.php");
        exit;
    }

    $person_id = $_SESSION["id"];
    $team_id = 0;
    $name = $notes = "";
    $team_err = "";
    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = trim($_POST["name"]);
        $notes = trim($_POST["notes"]);

        $updatePersonStmt = $conn->prepare("UPDATE team, driver SET team.name = ?, team.notes = ? WHERE driver.team = team.id and driver.person = ?");

        if ($updatePersonStmt && 
                $updatePersonStmt->bind_param('ssi', $name, $notes, $person_id) &&
                $updatePersonStmt->execute()) {
            $update_success = true;
            
        } else {
            $update_success = false;
            $general_err = "Error updating your information.  Please try again later.";
        }
        
        $getDetailsStmt = $conn->prepare("SELECT team.name, team.notes FROM team, driver WHERE driver.team = team.id and driver.person = ?");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $person_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($name, $notes) &&
                $getDetailsStmt->fetch()) {
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
        
    } else {
        $getDetailsStmt = $conn->prepare("SELECT team.name, team.notes FROM team, driver WHERE driver.team = team.id and driver.person = ?");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $person_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($name, $notes) &&
                $getDetailsStmt->fetch()) {
            
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
    }

    $title = "Update Team";
    include('header.php');

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
    <h2 class="header center orange-text">Update Team</h2>    
            <a class="btn-floating right btn-large waves-effect waves-light orange" href="add-team.php"><i class="material-icons">add</i></a>
    <div class="row">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" value="<?php echo $name; ?>">
                <label for="name">Name</label>
            </div>
            <div class="input-field col s12">
                <input  id="notes" name="notes" type="text" value="<?php echo $notes; ?>">
                <label for="notes">Notes</label>
            </div>            
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Save</a>
                <a class="btn blue-grey lighten-5 black-text" onclick="window.location='account.php';">Cancel</a>
            </div>
        </form>
    </div>
    <div class="row">        
        <div class="col s12">            
        </div>
    </div>
</div>
<?php
    include('footer.php');
?>