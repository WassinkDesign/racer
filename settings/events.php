<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

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

$addURL = "settings/add-event.php";
$title = "Events";
include(include_url_for('header.php')); 

?>

<div class="container">
    <h2 class="header center orange-text">Events</h2>
    <div class="section">
            <?php
            foreach ($events as $event) {
                echo "
                <div class=\"col s12\">
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"$event[0]\">
                            <span class=\"card-title grey-text text-darken-4\">$event[1]</span>
                            <p>$event[3]<br/>
                                $event[2]</p>
                            <p>$event[4] - $event[5]</p>
                            <a href=\"" . url_for('settings/update-event.php') . "?event=$event[0]\"  class=\"waves-effect green waves-light btn\">Edit</a>
                            <a href=\"" . url_for('settings/races.php') . "?event=$event[0]\"  class=\"waves-effect orange waves-light btn\">Races</a>
                            <a href=\"" . url_for('settings/delete/delete-event.php') . "?event=$event[0]\"  class=\"waves-effect red waves-dark right btn\">DELETE</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>