<?php
require_once("control/init.php");

$events = [
    [
        "id" => "01",
        "date" => "June 1-2, 2019",
        "location" => "Augusta Motorsports Park",
        "description" => "Information about the event here.",
    ],
    [
        "id" => "02",
        "date" => "June 15-16, 2019",
        "location" => "Toronto Motorsports Park",
        "description" => "Information about the event here.",
    ],
    [
        "id" => "03",
        "date" => "July 27-28, 2019",
        "location" => "Augusta Motorsports Park",
        "description" => "Information about the event here.",
    ],
    [
        "id" => "04",
        "date" => "August 24-25, 2019",
        "location" => "Shannonville",
        "description" => "Newly announced location",
    ],
];


$addURL = "";
$title = "Welcome";

include(include_url_for('header.php'));
?>
<div class="container">
    <h2 class="header deep-orange-text center">Racer</h2>
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
    <div class="section">
    <h3 class="center deep-orange white-text">Events</h3>
        <div class="row">
        <?php
        foreach ($events as $event) {
            echo "<div class=\"col s12 m12 l6\">
                    <div class=\"card\">
                    <div class=\"card-content\">
                    <span class=\"card-title activator grey-text text-darken-4\">{$event['date']}<i class=\"material-icons right\">more_vert</i></span>
                    <p>{$event['location']}</p>
                    <p>{$event['description']}</p>
                    </div>
                    <div class=\"card-reveal\">
                    <span class=\"card-title grey-text text-darken-4\">{$event['date']}<i class=\"material-icons right\">close</i></span>";
            if ($signedIn == true) {
                echo "<a class=\"col s12 waves-effect waves-light btn green darken-4\" href=\"" . url_for('register.php') . "?event={$event['id']}\">Registration</a>";
            } else {
                echo "<a class=\"col s12 waves-effect waves-light btn light-blue darken-3\" href=\"" . url_for('login.php') . "\">Log in to register</a>";
            }                    
            echo "</div>
                </div>
                </div>";
        }
        ?>
        </div>
    </div>
</div>
<?php include(include_url_for('footer.php'));?>