<?php
require_once("control/init.php");

if ($signedIn === false){
    redirect_to(url_for("login.php"));
    exit;
}

$teams = [];

if ($result = $conn->query("SELECT team.id, team.name, team.notes FROM team ORDER BY team.name")) {
        while ($obj = $result->fetch_object()) {
            $curTeam = array($obj->id, $obj->name, $obj->notes);
            array_push($teams, $curTeam);
    }
    $result->close();
}

$addURL = "add-team.php";
$title = "Teams";
include(include_url_for('header.php')); 

?>
<div class="container">
    <h2 class="header center orange-text">Teams</h2>
    <div class="section">
            <?php
            foreach ($teams as $team) {
                echo "
                <div class=\"col s12\">
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"$team[0]\">
                            <span class=\"card-title grey-text text-darken-4\">$team[1]</span>
                            <p>$team[2]</p>
                            <a href=\"" . url_for('update-team.php') . "?team=$team[0]\">Edit</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>