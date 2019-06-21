<?php
require_once("control/init.php");

$events = [];

if ($result = $conn->query("
    SELECT 
        e.id as id,
        e.name as name,
        e.description as description,
        l.name as location,
        e.start_date as start_date,
        e.end_date as end_date
    FROM 
        event e,
        location l
    WHERE e.location = l.id
    ORDER BY start_date")) {
        while ($obj = $result->fetch_object()) {
            $curEvent = array($obj->id, $obj->name, $obj->description, $obj->location, $obj->start_date, $obj->end_date);
            array_push($events, $curEvent);
    }
    $result->close();
}

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
            <a href="<?php echo url_for('scorekeep.php')?>" class="collection-item">Scorekeep</a>
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
        foreach ($events as $event) {
            echo "<div class=\"col s12 m12 l6\">
                    <div class=\"card\">
                    <div class=\"card-content\">
                    <span class=\"card-title activator grey-text text-darken-4\">{$event[1]}<i class=\"material-icons right\">more_vert</i></span>
                    <p>{$event[4]} - {$event[5]}<br/>
                    {$event[3]}<br/>
                    {$event[2]}</p>
                    </div>
                    <div class=\"card-reveal\">
                    <span class=\"card-title grey-text text-darken-4\">{$event[1]}<i class=\"material-icons right\">close</i></span>
                    <p>{$event[3]}</p>";
            if ($signedIn == true) {
                echo "<a class=\"col s12 waves-effect waves-light btn-small green darken-4 white-text\" href=\"" . url_for('register.php') . "?event={$event[0]}\">Registration</a>";
            } else {
                echo "<a class=\"col s12 waves-effect waves-light btn-small light-blue darken-3 white-text\" href=\"" . url_for('login.php') . "\">Log in to register</a>";
            }                    
            echo "</div>
                </div>
                </div>";
        }
        ?>
        </div>
    </div>
    <?php }?>
</div>
<?php include(include_url_for('footer.php'));?>