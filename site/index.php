<?php
include_once('../control/signed-in-check.php');

$events = [
    [
        "id"=>"01",
        "date"=>"June 1-2, 2019",
        "location"=>"Augusta Motorsports Park",
        "description"=>"Information about the event here."
    ],
    [
        "id"=>"02",
        "date"=>"June 15-16, 2019",
        "location"=>"Toronto Motorsports Park",
        "description"=>"Information about the event here."
    ],
    [
        "id"=>"03",
        "date"=>"July 27-28, 2019",
        "location"=>"Augusta Motorsports Park",
        "description"=>"Information about the event here."
    ],
    [
        "id"=>"04",
        "date"=>"August 24-25, 2019",
        "location"=>"Shannonville",
        "description"=>"Newly announced location"
    ],
];

$title = "Welcome";
include('header.php'); 

?>
<div class="container">
    <h2 class="header center orange-text">Racer</h2>
</div>
<?php 
if ($signedIn == true) {
?>
<div class="container">
<div class="collection">
    <a href="account.php" class="collection-item">Account</a>
    <a href="standings.php" class="collection-item">Standings</a>
    <a href="scorekeep.php" class="collection-item">Scorekeep</a>
</div>
</div>

<?php

} else {
?>
<!-- logged out user - promo time -->
<div class="container">
<p>Promotional resources will be displayed here.</p>
</div>
<?php

}
?>
<div class="container">

<?php 
    $count=0;

    foreach ($events as $event) {
        if ($count === 0) {
            echo "<div class=\"row\">";
        } 

        echo "<div class=\"col-md-4\">
        <div class=\"card\">
        <div class=\"card-content\">
          <span class=\"card-title activator grey-text text-darken-4\">{$event['date']}<i class=\"material-icons right\">more_vert</i></span>
          <p>{$event['location']}</p>
          <p><a href=\"register.php?event={$event['id']}\">Registration</a></p>
        </div>
        <div class=\"card-reveal\">
          <span class=\"card-title grey-text text-darken-4\">{$event['date']}<i class=\"material-icons right\">close</i></span>
          <p>{$event['description']}</p>
        </div>
      </div>
            </div>";

        if ($count === 2) {
            echo "</div>";
            $count = 0;
        } else {
            ++$count;
        }
    }
?>
</div>
<?php include('footer.php'); ?>