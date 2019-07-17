<?php
require_once "../../control/init.php";

if ($signedIn === false || $admin === false) {
    redirect_to(url_for("login.php"));
    exit;
}

$general_err = "";
$file_path = "raceData.xml";
$finished = false;

$events = $race_classes = $race_types = [];

if ($eventResult = $conn->query("SELECT id, name, start_date, end_date FROM event ORDER BY start_date")) {
    while ($obj = $eventResult->fetch_object()) {
        $curEvent = array($obj->id, $obj->name, $obj->start_date, $obj->end_date);
        array_push($events, $curEvent);
    }
    $eventResult->close();
}

if ($classResult = $conn->query("SELECT id, name FROM race_class ORDER BY name")) {
    while ($obj = $classResult->fetch_object()) {
        $curClass = array($obj->id, $obj->name);
        array_push($race_classes, $curClass);
    }
    $classResult->close();
}

if ($typeResult = $conn->query("SELECT id, name FROM race_type")) {
    while ($obj = $typeResult->fetch_object()) {
        $curType = array($obj->id, $obj->name);
        array_push($race_types, $curType);
    }
    $typeResult->close();
}

if (is_post_request() === true) {

    $xml = new DOMDocument("1.0", "ISO-8859-15");

    $xml_main = $xml->createElement("RaceData");

    // RACE CLASSES
    $xml_race_classes = $xml->createElement("RaceClasses");

    foreach ($race_classes as $race_class) {
        $xml_rc_id = $xml->createElement("ID");
        $xml_rc_id->nodeValue = $race_class[0];
        $xml_rc_name = $xml->createElement("NAME");
        $xml_rc_name->nodeValue = $race_class[1];

        $xml_rc_object = $xml->createElement("RaceClass");
        $xml_rc_object->appendChild($xml_rc_id);
        $xml_rc_object->appendChild($xml_rc_name);

        $xml_race_classes->appendChild($xml_rc_object);
    }

    $xml_main->appendChild($xml_race_classes);

    // RACE TYPES
    $xml_race_types = $xml->createElement("RaceTypes");

    foreach ($race_types as $race_type) {
        $xml_rt_id = $xml->createElement("ID");
        $xml_rt_id->nodeValue = $race_type[0];
        $xml_rt_name = $xml->createElement("NAME");
        $xml_rt_name->nodeValue = $race_type[1];

        $xml_rt_object = $xml->createElement("RaceClass");
        $xml_rt_object->appendChild($xml_rt_id);
        $xml_rt_object->appendChild($xml_rt_name);

        $xml_race_types->appendChild($xml_rt_object);
    }

    $xml_main->appendChild($xml_race_types);

    // EVENTS
    $xml_events = $xml->createElement("Events");

    foreach ($events as $event) {
        // array($obj->id, $obj->name, $obj->start_date, $obj->end_date);
        $xml_e_id = $xml->createElement("ID");
        $xml_e_id->nodeValue = $event[0];
        $xml_e_name = $xml->createElement("NAME");
        $xml_e_name->nodeValue = $event[1];
        $xml_e_start = $xml->createElement("START");
        $xml_e_start->nodeValue = $event[2];
        $xml_e_end = $xml->createElement("END");
        $xml_e_end->nodeValue = $event[3];

        $xml_event = $xml->createElement("Event");
        $xml_event->appendChild($xml_e_id);
        $xml_event->appendChild($xml_e_name);
        $xml_event->appendChild($xml_e_start);
        $xml_event->appendChild($xml_e_end);

        $xml_events->appendChild($xml_event);
    }

    $xml_main->appendChild($xml_events);

    $xml->appendChild($xml_main);
    $xml->save($file_path);

    $finished = true;
    //redirect_to(url_for('settings/export/' . $file_path));
}

$addURL = "";
$title = "Export Races";
include include_url_for('header.php');

if ($update_success === false) { echo display_error($general_err); }
?>
<div class="container">
    <div class="row">
        <form id="mainForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post" enctype="multipart/form-data">
            <input type="submit" name="submit" value="Export" />
        </form>
    </div>
<?php
if ($finished === true) {
    ?>
    <a href="<?php echo url_for('settings/export/' . $file_path);?>" target="_blank"><?php echo $file_path; ?></a>
    <?php
}
?>
</div>
<?php
include include_url_for('footer.php');
?>