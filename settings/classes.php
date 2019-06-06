<?php
require_once("../control/init.php");

if ($signedIn === false || $admin === false){
    redirect_to(url_for("login.php"));
    exit;
}

$classes = [];

if ($result = $conn->query("
    SELECT 
        id,
        name,
        description
    FROM 
        race_class
    ORDER BY name")) {
        while ($obj = $result->fetch_object()) {
            $curClass = array($obj->id, $obj->name, $obj->description);
            array_push($classes, $curClass);
    }
    $result->close();
}

$addURL = "settings/add-class.php";
$title = "Classes";
include(include_url_for('header.php')); 

?>
<div class="container">
    <h2 class="header center orange-text">Classes</h2>
    <div class="section">
            <?php
            foreach ($classes as $class) {
                echo "
                <div class=\"col s12\">
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"$class[0]\">
                            <span class=\"card-title grey-text text-darken-4\">$class[1]</span>
                            <p>$class[2]</p>
                            <a href=\"" . url_for('settings/update-class.php') . "?class=$class[0]\">Edit</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>
