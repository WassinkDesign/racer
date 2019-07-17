<?php
require_once("control/init.php");

if ($signedIn === false){
    redirect_to(url_for("login.php"));
    exit;
}
  
// Define variables and initialize with empty values
$new_password = $confirm_password = $new_password_err = $confirm_password_err = $update_success = "";

// Processing form data when form is submitted
if(is_post_request() === true){
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
        if (update_password($conn, $person_id, password_hash($new_password, PASSWORD_DEFAULT)) === true) {
            session_destroy();
            redirect_to(url_for("login.php"));
            exit();
        } else {
            $update_success = false;
        }
    }
}

$addURL = "";
$title="Reset Password";

include(include_url_for('header.php')); 


if ($update_success === true) { echo display_update(); } elseif ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <?php echo password_input("Password", "password");?>
            <?php echo password_input("Confirm Password", "confirm_password");?>
            <?php echo display_submit_cancel("index.php"); ?>
        </form>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>