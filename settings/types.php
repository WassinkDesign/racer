<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$types = [];
$types = get_race_types($conn);


$addURL = "settings/add-type.php";
$title = "Race Types";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
        <?php
        foreach ($types as $type) {?>
        <div class="line no-border" id="<?php echo $type['ID'];?>">
            <div class="line-title"><?php echo $type['NAME'];?></div>
            <p>Award Points: <?php 
                if ((int)$type["POINTS"] === 0) {echo "NO";} else {echo "YES";}
            ?></p>
            <div class="">
                <a href="<?php echo url_for('settings/update-type.php') . "?type={$type['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps">Edit</span></a>
                <a href="<?php echo url_for('settings/delete/delete-type.php') . "?type={$type['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps black-text">Delete</span></a>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>
