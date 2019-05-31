<?php
require_once("control/init.php");

if ($signedIn === false){
    redirect_to(url_for("login.php"));
    exit;
}
  
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

$update_success = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE person SET password = ? WHERE id = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                redirect_to(url_for("login.php"));
                exit();
            } else{
                $update_success = false;
            }
        }
        
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $conn->close();
}

$title="Reset Password";
include(url_for('header.php')); 

if ($update_success === true) {
    echo "<div class=\"row alert-dismissible green darken-4 white-text z-depth-1 \" id=\"alert-div\">        
            <div class=\"col s10\">
            Your password has been successfully updated.
            </div>
            <div class=\"col s2\">
                <a class=\"btn green darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
            </div>
        </div>";
} elseif ($update_success === false) {
    echo "<div class=\"row alert-dismissible red darken-4 white-text z-depth-1 \" id=\"alert-div\">        
            <div class=\"col s10\">
            Error updating password. Please try again later.
            </div>
            <div class=\"col s2\">
                <a class=\"btn red darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
            </div>
        </div>";
} 
?>
<div class="container">
    <h2 class="header center orange-text">Reset Password</h2>
    <div class="row">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 

            <div class="input-field col s12">
                <input id="password" name="password" type="password">
                <label for="password">New Password</label>
            </div>
            <div class="input-field col s12">
                <input id="confirm_password" name="confirm_password" type="password">
                <label for="confirm_password">Confirm Password</label>
            </div>
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Save</a>
                <a class="btn-small blue-grey lighten-5 black-text" onclick="document.forms[0].reset();">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php include(url_for('footer.php')); ?>