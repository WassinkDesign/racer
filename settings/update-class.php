<?php 
require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $class_id = 0;

    if (isset($_GET["class"])) {
        $class_id = $_GET["class"];
    } elseif($_SERVER["REQUEST_METHOD"] != "POST") {
        redirect_to(url_for("settings/classes.php"));
        exit;
    }
    
    $name = $description = "";

    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $description = htmlentities(trim($_POST["description"]));
        $class_id = trim($_POST["class_id"]);

        if ($name == "") {$name = " ";}
        if ($description == "") {$description = " ";}

        $updateClassStmt = $conn->prepare("UPDATE race_class r SET r.name = ?, r.description = ? WHERE r.id = ?");

        if ($updateClassStmt && 
                $updateClassStmt->bind_param('ssi', $name, $description, $class_id) &&
                $updateClassStmt->execute()) {
            $update_success = true;

            $updateClassStmt->close();
            
        } else {
            $update_success = false;
            $general_err = "Error updating address information. Please try again later.";
        }
        
        $getDetailsStmt = $conn->prepare("SELECT r.name, r.description FROM race_class r WHERE r.id = ?");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $class_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($name, $description) &&
                $getDetailsStmt->fetch()) {
        } else {
            $general_err = "Error retrieving information. Please try again later.";
        }
        
    } else {
        $getDetailsStmt = $conn->prepare("SELECT r.name, r.description FROM race_class r WHERE r.id = ?");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $class_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($name, $description) &&
                $getDetailsStmt->fetch()) {
            
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
    }

    $addURL = "";
    $title = "Update Class";
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
    <h2 class="header center orange-text">Update Class</h2>    
    <div class="row">
        <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
            <div class="row">
                <input id="class_id" name="class_id" type="hidden" value="<?php echo $class_id;?>">
                <div class="input-field col s12">
                    <input id="name" name="name" type="text" value="<?php echo $name; ?>">
                    <label for="name">Name</label>
                </div>
                <div class="input-field col s12">
                    <input id="description" name="description" type="text" value="<?php echo $description; ?>">
                    <label for="description">Description</label>
                </div>
                <div class="input-field col s12">
                    <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Submit</a>
                    <a class="btn blue-grey lighten-5 black-text" onclick="window.history.back();">Cancel</a>
                </div>
            </div>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/classes.php');?>">Back to Classes</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>
