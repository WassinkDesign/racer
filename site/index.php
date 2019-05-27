<?php
include('signed-in-check.php');

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
<div class="page-header">
    <h1>OORA RACER</h1>
</div>
<?php 
if ($signedIn == true) {
?>
<div class="mb-3">
    <div class="list-group">
        <a href="account.php" class="list-group-item list-group-item-action border-success">Account</a>
        <a href="standings.php" class="list-group-item list-group-item-action border-success">Standings</a>
        <a href="scorekeep.php" class="list-group-item list-group-item-action border-success">Scorekeep</a>
    </div>
</div>

<?php

} else {
?>
<!-- logged out user - promo time -->
<p>Promotional resources will be displayed here.</p>

<?php

}
?>
<div class="mb-3">
<?php 
    $count=0;

    foreach ($events as $event) {
        if ($count === 0) {
            echo "<div class=\"row\">";
        } 

        echo "<div class=\"col-md-4\">
                <div class=\"card border-dark mb-3\">
                    <div class=\"card-header text-white bg-dark font-weight-bold\">
                        {$event['date']}
                    </div>
                    <div class=\"card-body\">
                        <p class=\"card-text\"><span class=\"font-weight-bold\">{$event['location']}</span> - {$event['description']}</p>                    
                    </div>
                    <a href=\"register.php?event={$event['id']}\" class=\"card-link card-footer text-center text-white bg-success\">Registration</a>
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