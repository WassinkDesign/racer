<?php
require_once("control/init.php");

$events = get_events_display($conn);

$addURL = "";
$title = "Racer";

include(include_url_for('header.php'));
?>
<div class="container">

    <?php
    if ($signedIn === true) {
        ?>
    <div class="section">
        <div class="collection">
            <a href="<?php echo url_for('account.php')?>" class="collection-item">Account</a>
            <a href="<?php echo url_for('standings.php')?>" class="collection-item">Standings</a>
            <?php if ($admin === true) { ?>
            <a href="<?php echo url_for('scorekeep/index.php')?>" class="collection-item">Scorekeep</a>
            <?php } ?>
        </div>
    </div>
    <?php
        if ($admin === true) {
            ?> 
            <div class="collection">                
                <a href="<?php echo url_for('settings/index.php')?>" class="collection-item grey darken-3 white-text">Settings</a>
                <a href="<?php echo url_for('settings/classes.php')?>" class="collection-item">Classes</a>
                <a href="<?php echo url_for('settings/events.php')?>" class="collection-item">Events</a>
                <a href="<?php echo url_for('settings/locations.php')?>" class="collection-item">Locations</a>
                <a href="<?php echo url_for('settings/types.php')?>" class="collection-item">Race Types</a>
            </div>
            <?php
        }
    }
    else {
        ?>
    <!-- logged out user - promo time -->
    <div class="section">
        <div class="center promo">
        <!-- <i class="material-icons">flash_on</i> -->
        <h5>Ontario Offroad Racing Association</h5>
        <p class="promo-caption">Canada's premier short course off road racing series</p>
        <p class="light center grey-text text-darken-4">Short course off road racing has been around since the early 70's in Ontario and has evolved into what we have now in an exciting
            wheel to wheel, dirt throwing, high jumping racing series that brings drivers from all around Ontario at various tracks in southern Ontario.
            If you've never witnessed off road racing for yourself, take stock car racing, rally cars, and motocross, mix it all together and you've got short course off road racing!<br/>
            See you at the track!</p>
        </div>
    </div>
    <?php
    }
    ?>

    <?php if (empty($events) === false) {?>
    <div class="section">
    <h3 class="center">Events</h3>
        <div class="row">
        <?php
        foreach ($events as $event) {?>
            <div class="line no-border">
                <div class="" id="<?php echo $event['ID'];?>">
                    <span class="line-title"><?php echo $event['NAME'];?></span>
                    <p><?php echo "{$event['START_DATE']} - {$event['END_DATE']}";?><br/>
                    <?php echo $event['LOCATION'];?><br/>
                    <?php echo $event['DESCRIPTION'];?></p>
                    <p>
                    <?php 
                    if ($signedIn === true) {
                    ?>
                        <a class="waves-effect waves-light btn-small green darken-4 white-text" href="<?php echo url_for('register.php') . "?event={$event["ID"]}";?>">Registration</a>
                    <?php
                    } else {
                    ?>
                        <a class="waves-effect waves-light btn-small light-blue darken-3 white-text" href="<?php echo url_for('login.php');?>">Log in to register</a>
                    <?php
                    }
                    ?>
                    </p>
                </div>
            </div>
        <?php
        }
        ?>
        </div>
    </div>
    <?php }?>
</div>
<?php include(include_url_for('footer.php'));?>