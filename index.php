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


$title = "Welcome";

include(url_for('header.php'));
?>
<div class="container">
    <h2 class="header center orange-text">Racer</h2>
    <?php
    if ($signedIn === true) {
        ?>
    <div class="section">
        <div class="collection">
            <a href="account.php" class="collection-item">Account</a>
            <a href="standings.php" class="collection-item">Standings</a>
            <?php if ($admin === true) { ?>
            <a href="scorekeep.php" class="collection-item">Scorekeep</a>
            <a href="settings-main.php" class="collection-item">Settings</a>
            <?php } ?>
        </div>
    </div>
    <?php

    } else {
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
    <h3 class="center orange-text">Events</h3>
        <div class="row">
        <?php
        $count = 0;

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
                echo "<a class=\"col s12 waves-effect waves-light btn green darken-4\" href=\"register.php?event={$event['id']}\">Registration</a>";
            } else {
                echo "<a class=\"col s12 waves-effect waves-light btn light-blue darken-3\" href=\"login.php\">Log in to register</a>";
            }                    
            echo "</div>
                </div>
                </div>";
        }
        ?>
        </div>
    </div>
</div>
<?php include url_for('footer.php');?>