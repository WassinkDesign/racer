<?php
require_once("control/init.php");

if ($signedIn === true){
    redirect_to(url_for("index.php"));
    exit;
}
 
// Define variables and initialize with empty values
$email = $password = $name = "";
$admin = 0;
$email_err = $password_err = "";
 
// Processing form data when form is submitted
if(is_post_request() === true){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, name, email, password, admin FROM person WHERE email = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if email exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $name, $email, $hashed_password, $admin);

                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;   
                            $_SESSION["name"] = $name;
                            
                            if ($admin === 1 || $admin === TRUE) {
                                $_SESSION["admin"] = true;
                            } else {
                                $_SESSION["admin"] = false;
                            }
                            
                            // Redirect user to welcome page
                            redirect_to(url_for("index.php"));
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $conn->close();
}

$addURL = "";
$title = "Login";

include(include_url_for('header.php')); 

if ($email_err != "" || $password_err != "") {
    $general_err = $email_err . "<br/>" . $password_err;
    echo display_error($general_err);
}
?>

<div class="container">
    <div class="row">
        <form id="mainForm" class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php echo text_input("Email", "email", $email);?>
            <?php echo password_input("Password", "password");?>
            <?php echo display_submit_cancel("index.php"); ?>
            <div class="divider"></div>
            <div class="col s12">
                <p>Don't have an account? <a href="signup.php">Sign up now</a></p>
            </div>
        </form>
    </div>
</div>
<?php include(include_url_for('footer.php'));?>
