<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $name = "";
    $points = 2;
    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $points = htmlentities(trim($_POST["points"]));
        
        if ($name == "") {
            $general_err = "Please enter a name";
        } else {            
            $addTypeStmt = $conn->prepare("INSERT INTO race_type (name, points) VALUES (?, ?)");

            if ($addTypeStmt && 
                    $addTypeStmt->bind_param('si', $name, $points) &&
                    $addTypeStmt->execute()) {
                $update_success = true;
                $name = $points = "";
                redirect_to(url_for('settings/types.php'));

                $addTypeStmt->close();
            } else {
                $update_success = false;
                $general_err = "Error adding type information.  Please try again later.";
            }
        }
    }

    $addURL = "";
    $title = "Add Race Type";
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
