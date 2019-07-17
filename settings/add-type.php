<?php 
    require_once("../control/init.php");

    if ($signedIn === false || $admin === false){
        redirect_to(url_for("login.php"));
        exit;
    }

    $points = 2;
    $name = $general_err = $update_success = "";
    $pointsDisplay = [
        ["ID"=>"1",
        "DISPLAY"=>"YES"],
        ["ID"=>"0",
        "DISPLAY"=>"NO"]
    ];

    if (is_post_request() === true)
    {
        $name = htmlentities(trim($_POST["name"]));
        $points = htmlentities(trim($_POST["points"]));
        
        if ($name == "") {
            $general_err = "Please enter a name";
        } else {
            if (insert_race_type($conn, $name, $points) === false) {
                $update_success = false;
                $general_err = "Error adding type information.  Please try again later.";
            } else {
                redirect_to(url_for('settings/types.php'));
            }         
        }
    }

    $addURL = "";
    $title = "Add Race Type";
    include(include_url_for('header.php'));

    if ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <?php echo text_input('Name', 'name', $name);?>
            <?php echo dropdown_input_basic('Points Awarded', 'points', false, $pointsDisplay, 'ID', 'DISPLAY', $points);?>
            <?php echo display_submit_cancel("settings/types.php"); ?>
        </form>
        <div class="col s12">
            <a href="<?php echo url_for('settings/types.php');?>">Back to Types</a>
        </div>
    </div>
</div>
<?php
    include(include_url_for('footer.php'));
?>
