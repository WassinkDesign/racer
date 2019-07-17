<?php
require_once "../../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$general_err = "";
$file_path = "";
$success = true;

// POST method
if (is_post_request() === true) {
    $file_path = "";

    if ($_FILES["file"]["error"] > 0)
    {
        $general_err = "Return Code: " . $_FILES["file"]["error"] . "<br />";
        $success = false;
    } else {   
        $file_path = $_FILES["file"]["name"]; 
        
        if(file_exists($file_path)) unlink($file_path);
        if(file_exists($_FILES["file"]["tmp_name"])) unlink($_FILES["file"]["tmp_name"]);

        move_uploaded_file($file_path, $_FILES["file"]["tmp_name"]);
        
        // read the file
        $fr = fopen($file_path, "r") or die ("Unable to open file");
        $success = true;

        while (!feof($fr)) {
            echo fgets($fr);
        }
        fclose($fr);
    }

}

$addURL = "";
$title = "Import Races";
include include_url_for('header.php');

if ($success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post" enctype="multipart/form-data">
            <label for="file">File:</label>
            <input type="file" name="file" id="file" /> 
            <br />
            <input type="submit" name="submit" value="Upload" />
        </form>
    </div>
</div>
<?php
include include_url_for('footer.php');
?>