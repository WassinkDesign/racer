<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$locations = [];

if ($result = $conn->query("
    SELECT 
        l.id as id, 
        l.name as name,
        a.address as address,
        a.city as city,
        a.prov as prov
    FROM 
        location l, 
        address a
    WHERE l.address = a.id
    ORDER BY name")) {
        while ($obj = $result->fetch_object()) {
            $curLocation = array($obj->id, $obj->name, $obj->address, $obj->city, $obj->prov);
            array_push($locations, $curLocation);
    }
    $result->close();
}

$addURL = "settings/add-location.php";
$title = "Locations";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
            <?php
            foreach ($locations as $location) {
                echo "
                <div class=\"col s12\">
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"$location[0]\">
                            <span class=\"card-title grey-text text-darken-4\">$location[1]</span>
                            <p>$location[2]<br/>
                                $location[3], $location[4]</p>
                            <div class=\"row\">
                                <a href=\"" . url_for('settings/update-location.php') . "?location=$location[0]\" class=\"col s3 waves-effect waves-light\"><span class=\"left small-caps\">Edit</span></a>
                                <a href=\"" . url_for('settings/delete/delete-location.php') . "?location=$location[0]\" class=\"col s3 waves-effect waves-light\"><span class=\"left small-caps black-text\">Delete</span></a>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>