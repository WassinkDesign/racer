<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$event_id = 0;
$event_only = false;
$event_name = "";
$event_start = "";
$event_end = "";

if (isset($_GET["event"])) {
    $event_id = htmlentities(trim($_GET["event"]));
    $event_only = true;

    if ($result = $conn->query("SELECT name, start_date, end_date FROM event WHERE event.id = $event_id")) {
        while ($obj = $result->fetch_object()) {
            $event_name = $obj->name;
            $event_start = $obj->start_date;
            $event_end = $obj->end_date;
        }
        $result->close();
    }
}

$races = [];

$sql = "
    SELECT 
        r.id as id,
        e.name as event,
        rt.name as type,
        rc.name as class,
        r.date as date,
        r.time as time,
        r.description as description,
        r.points as points
    FROM 
        race r,
        event e,
        race_type rt,
        race_class as rc
    WHERE 
        r.event = e.id
    AND r.race_type = rt.id
    AND r.class_type = rc.id";

if ($event_only === true) {
    $sql .="AND e.id = $event_id";
}

$sql .= " ORDER BY date, time";

if ($result = $conn->query($sql)) {
        while ($obj = $result->fetch_object()) {
            $curRace = array($obj->id, $obj->event, $obj->type, $obj->class, $obj->date, $obj->time, $obj->description, $obj->points); 
            array_push($races, $curRace);
    }
    $result->close();
}

if ($event_only === true) {
    $addURL = "settings/add-race.php?event=$event_id";
} else {
    $addURL = "settings/add-race.php";
}

$title = "Races";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
            <?php
            if ($event_only === true) {
                ?>
                    <h4><?php echo $event_name;?></h4>
                    <p><?php echo formatDateDisplay($event_start) . " - " . formatDateDisplay($event_end);?></p>
                <?php

                if (empty($races) === false) {
                    foreach($races as $race) {
                        echo "
                        <div class=\"col s12\">
                            <div class=\"card\">
                                <div class=\"card-content\" id=\"$race[0]\">
                                    <span class=\"card-title grey-text text-darken-4\">$race[3] - $race[2]</span>
                                    <p>$race[4] $race[5]</p>
                                    <p>$race[6]</p>
                                    <p>Award points: "; 
                                
                                        if ((int)$type[2] === 0) {echo "NO";} 
                                        if ((int)$type[2]===1) {echo "YES";}
                                        
                                        echo "</p>
                                    <a href=\"" . url_for('settings/update-race.php') . "?race=$race[0]\">Edit</a>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p class=\"red lighten-4 black-text z-depth-1\">There are no races currently available for this event.</p>";
                }

            } else {
                if (empty($races) === false) {
                    foreach ($races as $race) {                    
                        echo "
                        <div class=\"col s12\">
                            <div class=\"card\">
                                <div class=\"card-content\" id=\"$race[0]\">
                                    <span class=\"card-title grey-text text-darken-4\">$race[3] - $race[2]</span>
                                    <p>$race[1]<br/>
                                        $race[4] $race[5]</p>
                                    <p>$race[6]</p>
                                    <p>Award points: "; 
                                
                                        if ((int)$type[2] === 0) {echo "NO";} 
                                        if ((int)$type[2]===1) {echo "YES";}
                                        
                                        echo "</p>
                                    <a href=\"" . url_for('settings/update-race.php') . "?race=$race[0]\">Edit</a>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p class=\"red lighten-4 black-text z-depth-1\">There are no races currently available.</p>";
                }

            }
            ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>