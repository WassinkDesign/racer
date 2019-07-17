<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$classes = [];

$classes = get_race_classes($conn);

$addURL = "settings/add-class.php";
$title = "Classes";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
        <?php
        foreach ($classes as $class) {?>
        <div class="line no-border" id="<?php echo $class['ID'];?>">
            <div class="line-title"><?php echo $class['NAME'];?></div>
            <p><?php echo $class['DESCRIPTION'];?></p>
            <div class="">
                <a href="<?php echo url_for('settings/update-class.php') . "?class={$class['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps">Edit</span></a>
                <a href="<?php echo url_for('settings/delete/delete-class.php') . "?class={$class['ID']}";?>" class="col waves-effect waves-light"><span class="left small-caps black-text">Delete</span></a>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>
