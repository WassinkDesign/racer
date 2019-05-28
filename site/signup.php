<?php
include_once('../control/signed-in-check.php');

if ($signedIn === true){
    header("location: index.php");
    exit;
}

require_once "../control/database.php";

$email = $password = $confirm_password = $name = $phone = $address = $city = $pcode = "";
$email_err = $password_err = $confirm_password_err = $name_err = $phone_err = $address_err = $city_err = $pcode_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        $sql = "SELECT id FROM person WHERE email = ?";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_email);
            
            $param_email = trim($_POST["email"]);
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "This email is already registered.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        $stmt->close();
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $city = trim($_POST["city"]);
    $pcode = trim($_POST["pcode"]);

    if((empty($email_err) || $email_err == "") && 
        (empty($password_err) || $password_err == "") && 
        (empty($confirm_password_err) || $confirm_password_err == "")){
        
        $address_id = 0;
        
        if (!empty($address) || !empty($city) || !emtpy($pcode)) {
            $stmt = $conn->prepare("INSERT INTO address (address, city, postalCode) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $param_address, $param_city, $param_pcode);

            $param_address = $address;
            $param_city = $city;
            $param_pcode = $pcode;

            if ($stmt->execute()) {
                $address_id = $stmt->insert_id;
            }

            $stmt->close();
        }

        $pStmt = $conn->prepare("INSERT INTO person (address, name, phone, email, password) VALUES (?, ?, ?, ?, ?)");
        $pStmt->bind_param("issss", $param_address, $param_name, $param_phone, $param_email, $param_password);

        $param_address = $address_id;
        $param_name = $name;
        $param_phone = $phone;
        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        if ($pStmt->execute()) {
            $person_id = $pStmt->insert_id;

            session_start();

            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $person_id;
            $_SESSION["email"] = $email;                            
            
            header("location: index.php");

        } else {
            echo "There were technical difficulties. I'm sorry. Try again later?";
        }

        $pStmt->close();
    }
    
    $conn->close();
}

$title="Signup";
include('header.php'); ?>
<div class="wrapper">
<div class="container">
    <h2 class="header center orange-text">Sign Up</h2>
</div>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
            <span class="help-block"><?php echo $name_err;?></span>
        </div>
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email Address</label>
            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
            <span class="help-block"><?php echo $phone_err;?></span>
        </div>
        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
            <span class="help-block"><?php echo $address_err;?></span>
        </div>
        <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
            <label>City</label>
            <input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
            <span class="help-block"><?php echo $city_err;?></span>
        </div>
        <div class="form-group <?php echo (!empty($pcode_err)) ? 'has-error' : ''; ?>">
            <label>Postal Code</label>
            <input type="text" name="pcode" class="form-control" value="<?php echo $pcode; ?>">
            <span class="help-block"><?php echo $pcode_err;?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Cancel">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
<?php include('footer.php');?>