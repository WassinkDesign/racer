<?php 
    include_once('../control/signed-in-check.php');

    if ($signedIn === false){
        header("location: login.php");
        exit;
    }

    require_once "../control/database.php";

    $person_id = $_SESSION["id"];
    $address_id = 0;

    $email = $password = $confirm_password = $name = $phone = $address = $city = $pcode = "";
    $email_err = $password_err = $confirm_password_err = $name_err = $phone_err = $address_err = $city_err = $pcode_err = "";
    $general_err = "";
    $update_success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = trim($_POST["name"]);
        $phone = trim($_POST["phone"]);
        $address = trim($_POST["address"]);
        $city = trim($_POST["city"]);
        $pcode = trim($_POST["pcode"]);
        $email = trim($_POST["email"]);

        $updatePersonStmt = $conn->prepare("UPDATE address a, person p SET a.address = ?, a.city = ?, a.postalCode = ?, p.name = ?, p.phone = ?, p.email = ? WHERE a.id = p.address AND p.id = ?");

        if ($updatePersonStmt && 
                $updatePersonStmt->bind_param('ssssssi', $address, $city, $pcode, $name, $phone, $email, $person_id) &&
                $updatePersonStmt->execute()) {
            $update_success = true;
        } else {
            $update_success = false;
            $general_err = "Error updating your information.  Please try again later.";
        }
        
        $getDetailsStmt = $conn->prepare("SELECT a.address, a.city, a.postalCode, p.name, p.phone, p.email FROM person p, address a WHERE p.id = ? and p.address = a.id");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $person_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($address, $city, $pcode, $name, $phone, $email) &&
                $getDetailsStmt->fetch()) {
            
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
        
    } else {
        $getDetailsStmt = $conn->prepare("SELECT a.address, a.city, a.postalCode, p.name, p.phone, p.email FROM person p, address a WHERE p.id = ? and p.address = a.id");

        if ($getDetailsStmt &&
                $getDetailsStmt->bind_param('i', $person_id) &&
                $getDetailsStmt->execute() &&
                $getDetailsStmt->store_result() &&
                $getDetailsStmt->bind_result($address, $city, $pcode, $name, $phone, $email) &&
                $getDetailsStmt->fetch()) {
            
        } else {
            $general_err = "Error retrieving your information.  Please try again later.";
        }
    }

    $title = "Account";
    include('header.php');

    if ($update_success === true) {
        echo "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                Your information has been successfully updated.
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                    <span aria-hidden=\"true\">&times;</span>
                </button>
            </div>";
    } elseif ($update_success === false) {
        echo "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                $general_err
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                    <span aria-hidden=\"true\">&times;</span>
                </button>
            </div>";
    }
?>
<div class="row">
    <div class="col-sm-9">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err;?></span>
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
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save">
                <a class="btn btn-default" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
    <div class="col">
        <a href="reset-password.php" class="btn btn-outline-info btn-sm btn-block">Reset Password</a>
    </div>
</div>
<?php
    include('footer.php');
?>