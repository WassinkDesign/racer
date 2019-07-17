<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $name = $address = $city = $prov = "";
    $address_id = 0;
    $general_err = "";
    $update_success = "";

    if (is_post_request() === true)
    {
        $name = h($_POST["name"]);
        $address = h($_POST["address"]);
        $city = h($_POST["city"]);
        $prov = h($_POST["prov"]);

        $address_id = insert_address($conn, $address, $city, $prov);
        if ($address_id !== false) {
            $update_success = true;
            $address = $city = $prov = "";

            if (insert_location($name, $address_id) !== false) {
                $update_success = true;
                $name = "";
                redirect_to(url_for("settings/locations.php"));
            } else {
                $update_success = false;
                $general_err = "Error adding location information. Please try again later.";
            }
        } else {
            $update_success = false;
            $general_err = "Error adding address information.  Please try again later.";
        }
    }

    $addURL = "";
    $title = "Add Location";
    include(include_url_for('header.php'));

    if ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <?php echo text_input('Name', 'name', $name);?>
            <?php echo text_input('Address', 'address', $address);?>
            <?php echo text_input('City', 'city', $city);?>
            <?php echo text_input('Province', 'prov', $prov);?>
            <?php echo display_submit_cancel("settings/locations.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/locations.php');?>">Back to Locations</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>