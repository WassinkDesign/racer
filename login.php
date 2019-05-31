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
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
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

$title = "Login";
include(url_for('header.php')); ?>


<div class="container">
    <h2 class="header center orange-text">Login</h2>
    <div class="row">
        <form class="col s12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="email" type="text" class="validate" name="email">
                    <label for="email">Email</label>
                    <span class="helper-text" data-error="wrong" data-success=""><?php echo $email_err; ?></span>
                </div>
                <div class="input-field col s12">
                    <input id="password" type="password" name="password" class="validate">
                    <label for="password">Password</label>
                    <span class="helper-text" data-error="wrong" data-success=""><?php echo $password_err; ?></span>
                </div>
                <div class="input-field col s12">
                    <a class="waves-effect waves-light btn" onclick="document.forms[0].submit();">Login</a>
                </div>
                <div class="col s12">
                    <p>Don't have an account? <a href="signup.php">Sign up now</a></p>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include(url_for('footer.php'));?>