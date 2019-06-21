<?php 
require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $type_id = 0;

    if (isset($_GET["type"])) {
        $type_id = $_GET["type"];
    } elseif($_SERVER["REQUEST_METHOD"] != "POST") {
        redirect_to(url_for("settings/types.php"));
        exit;
    }
    
    $name = "";
    $points = 0;
    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $points = htmlentities(trim($_POST["points"]));
        $type_id = trim($_POST["type_id"]);

        if ($name == "") {
            $general_err = "Please enter a name";
        } else { 
            $updatetypeStmt = $conn->prepare("UPDATE race_type r SET r.name = ?, r.points = ? WHERE r.id = ?");

            if ($updatetypeStmt && 
                    $updatetypeStmt->bind_param('sii', $name, $points, $type_id) &&
                    $updatetypeStmt->execute()) {
                $update_success = true;

                $updatetypeStmt->close();
                
            } else {
                $update_success = false;
                $general_err = "Error updating type information. Please try again later.";
            }
        }        
    }

    $getDetailsStmt = $conn->prepare("SELECT r.name, r.points FROM race_type r WHERE r.id = ?");

    if ($getDetailsStmt &&
            $getDetailsStmt->bind_param('i', $type_id) &&
            $getDetailsStmt->execute() &&
            $getDetailsStmt->store_result() &&
            $getDetailsStmt->bind_result($name, $points) &&
            $getDetailsStmt->fetch()) {
        
    } else {
        $general_err = "Error retrieving your information.  Please try again later.";
    }

    $addURL = "";
    $title = "Update type";
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
        echo "<div type=\"row alert-dismissible red darken-4 white-text z-depth-1 \" id=\"alert-div\">        
                <div type=\"col s10\">
                $general_err
                </div>
                <div type=\"col s2\">
                    <a type=\"btn red darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
                </div>
            </div>";
    }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
        <input id="type_id" name="type_id" type="hidden" value="<?php echo $type_id;?>">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" value="<?php echo $name; ?>">
                <label for="name">Name</label>
            </div>
            <div class="input-field col s12">
                <select id="points" name="points" class="browser-default">
                    <option value="" disabled selected>Points Awarded</option>
                    <option value=1 <?php if ((int)$points == 1) {echo " selected ";} ?>>YES</option>
                    <option value=0 <?php if ((int)$points == 0) {echo " selected ";} ?>>NO</option>                    
                </select>
            </div>
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Save</a>
                <a class="btn blue-grey lighten-5 black-text" onclick="window.history.back();">Cancel</a>
            </div>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/types.php');?>">Back to Types</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>

