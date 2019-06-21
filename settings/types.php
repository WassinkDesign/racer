<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$types = [];

if ($result = $conn->query("
    SELECT 
        id,
        name,
        points
    FROM 
        race_type
    ORDER BY name")) {
        while ($obj = $result->fetch_object()) {
            $curType = array($obj->id, $obj->name, $obj->points);
            array_push($types, $curType);
    }
    $result->close();
}

$addURL = "settings/add-type.php";
$title = "Race Types";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
            <?php
            foreach ($types as $type) {
                echo "
                <div class=\"col s12\">
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"$type[0]\">
                            <span class=\"card-title grey-text text-darken-4\">$type[1]</span>
                            <p>Award points: "; 
                            
                            if ((int)$type[2] === 0) {echo "NO";} 
                            if ((int)$type[2]===1) {echo "YES";}
                            
                            echo "</p>
                            <a href=\"" . url_for('settings/update-type.php') . "?type=$type[0]\">Edit</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>
