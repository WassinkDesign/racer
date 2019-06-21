<?php 
require_once("../../control/init.php");

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
    
    $name = $desc = "";
    $classes = [];
    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $desc = htmlentities(trim($_POST["desc"]));
        $class_id = trim($_POST["class_id"]);

        if ($name == "") {$name = " ";}

        $deleteclassStmt = $conn->prepare("
            DELETE FROM class_class 
            WHERE id = ?");

        if ($deleteclassStmt && 
                $deleteclassStmt->bind_param('i', $class_id) &&
                $deleteclassStmt->execute()) {
            $update_success = true;
            redirect_to(url_for("settings/classes.php"));
        } else {
            $update_success = false;
            $general_err = "Error updating class information. Please try again later.";
        }
    }    

    $getDetailsStmt = $conn->prepare(" SELECT 
            name,
            description
        FROM 
            race_class
        WHERE id = ?");

    if ($getDetailsStmt &&
            $getDetailsStmt->bind_param('i', $class_id) &&
            $getDetailsStmt->execute() &&
            $getDetailsStmt->store_result() &&
            $getDetailsStmt->bind_result($name, $desc) &&
            $getDetailsStmt->fetch()) {
        
    } else {
        $general_err = "Error retrieving your information.  Please try again later.";
    }

    $addURL = "";
    $title = "Delete Class";
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
    <h2 class="header center deep-orange-text">Delete Class</h2> 
    <div class="section">
        <div class="col s12">
            <div class="card">
                <div class="card-content" id="<?php echo $class_id;?>">
                    <span class="card-title grey-text text-darken-4\"><?php echo $name;?></span>
                    <p><?php echo $desc;?></p>
                    <div class="divider"></div>
                    <p><span class="verify">Are you sure you want to delete this class?</span></p>
                    <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input id="class_id" name="class_id" type="hidden" value="<?php echo $class_id;?>">
                        <div class="input-field col s12">
                            <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Yes</a>
                            <a class="btn blue-grey lighten-5 black-text" href="<?php echo url_for('settings/classes.php');?>">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="col s12">
        <a href="<?php echo url_for('settings/classes.php');?>">Back to Classes</a>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>