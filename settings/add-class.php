<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $name = $desc = "";
    
    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $desc = htmlentities(trim($_POST["desc"]));
        
        if ($name == "") {
            $general_err = "Please enter a name";
        } else {
            if (empty($desc)) {
                $desc = " ";
            }
            $addClassStmt = $conn->prepare("INSERT INTO race_class (name, description) VALUES (?, ?)");

            if ($addClassStmt && 
                    $addClassStmt->bind_param('ss', $name, $desc) &&
                    $addClassStmt->execute()) {
                $update_success = true;
                $name = $desc = "";
                redirect_to(url_for('settings/classes.php'));

                $addClassStmt->close();
            } else {
                $update_success = false;
                $general_err = "Error adding class information.  Please try again later.";
            }
        }
    }

    $addURL = "";
    $title = "Add Class";
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
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
        
            <label class="col s12" for="name">Name</label>
            <input class="col s12" id="name" name="name" type="text" value="<?php echo $name; ?>">

            <label class="col s12" for="desc">Description</label>
            <input class="col s12" id="desc" name="desc" type="text" value="<?php echo $desc; ?>">

            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Save</a>
                <a class="btn blue-grey lighten-5 black-text" onclick="window.history.back();">Cancel</a>
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