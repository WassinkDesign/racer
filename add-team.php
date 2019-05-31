<?php 
    require_once("control/init.php");

    $name = $notes = "";
    $team_err = "";
    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = htmlentities(trim($_POST["name"]));
        $notes = htmlentities(trim($_POST["notes"]));

        $updatePersonStmt = $conn->prepare("INSERT INTO team (name, notes) VALUES (?, ?)");

        if ($updatePersonStmt && 
                $updatePersonStmt->bind_param('ss', $name, $notes) &&
                $updatePersonStmt->execute()) {
            $update_success = true;
            $name = $notes = "";
        } else {
            $update_success = false;
            $general_err = "Error updating your information.  Please try again later.";
        }
    }

    $title = "Add Team";
    include(url_for('header.php'));

    if ($update_success === true) {
        echo "<div class=\"row alert-dismissible green darken-4 white-text z-depth-1 \" id=\"alert-div\">        
                <div class=\"col s10\">
                Your information has been successfully added.
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
    <h2 class="header center orange-text">Add Team</h2>
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
            </div>
        </form>
    </div>
</div>
<?php
    include(url_for('footer.php'));
?>