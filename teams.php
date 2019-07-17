<?php
require_once("control/init.php");

if ($signedIn === false){
    redirect_to(url_for("login.php"));
    exit;
}

$teams = get_teams($conn);

$addURL = "add-team.php";
$title = "Teams";
include(include_url_for('header.php')); 

?>
<div class="container">
    <div class="section">
            <?php
            foreach ($teams as $team) {
                echo "
                <div class=\"col s12\">
                    <div class=\"card\">
                        <div class=\"card-content\" id=\"{$team['ID']}\">
                            <span class=\"card-title grey-text text-darken-4\">{$team['NAME']}</span>
                            <p>{$team['NOTES']}</p>
                            
                            <a href=\"" . url_for('update-team.php') . "?team={$team['ID']}\" class=\"col s3 waves-effect waves-light\"><span class=\"left small-caps\">Edit</span></a>
                            <a href=\"" . url_for('delete-team.php') . "?team={$team['ID']}\" class=\"col s3 waves-effect waves-light\"><span class=\"left small-caps black-text\">Delete</span></a>
                        </div>
                    </div>
                </div>";
            }
            ?>
    </div>
</div>
<?php include(include_url_for('footer.php')); ?>