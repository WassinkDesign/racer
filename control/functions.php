<?php

function formatDateDisplay($date)
{
    if ($date !== "" &&
        empty(trim($date)) === false) {
        $tempDate = DateTime::createFromFormat('m/d/Y', $date);
        return $tempDate->format('M d, Y');
    }
}

function formatDateSave($date)
{
    if ($date !== "" &&
        empty(trim($date)) === false) {
        $tempDate = DateTime::createFromFormat('M d, Y', $date);
        return $tempDate->format('m/d/Y');
    }
}

function formatTimeDisplay($time)
{
    if ($time !== "" &&
        empty(trim($time)) === false) {
        $tempTime = DateTime::createFromFormat('G:i', $time);
        return $tempTime->format('g:i a');
    }
}

function formatTimeSave($time)
{
    if ($time !== "" &&
        empty(trim($time)) === false) {
        $tempTime = DateTime::createFromFormat('g:i a', $time);
        return $tempTime->format('G:i');
    }
}

function include_url_for($script_path)
{
    // add the leading '/' if not present
    if ($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }

    return WWW_ROOT . $script_path;
}

function url_for($script_path)
{
    // add the leading '/' if not present
    if ($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }

    return "http://" . HEADER_ROOT . $script_path;
}

function redirect_to($location)
{
    header("Location: " . $location);
    exit;
}

function is_post_request()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {return true;}
    return false;
}

function is_get_request()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {return true;}
    return false;
}

function h($input) {
    return htmlentities(trim($input));
}

function display_error($error)
{
    $output = "";
    $output .= "<div class=\"row alert-dismissible red darken-4 white-text z-depth-1 \" id=\"alert-div\">
    <div class=\"col s10\">";
    $output .= $error;
    $output .= "</div><div class=\"col s2\"><a class=\"btn red darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a></div></div>";
    return $output;
}

function display_update()
{
    $output = "";
    $output .= "<div class=\"row alert-dismissible green darken-4 white-text z-depth-1 \" id=\"alert-div\">
            <div class=\"col s10\">
            Your information has been successfully updated.
            </div>
            <div class=\"col s2\">
                <a class=\"btn green darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
            </div>
        </div>";
    return $output;
}

function display_submit_cancel($redirect_url) {
    $output = "";
    $output .= "<div class=\"input-field col s12\">
    <a class=\"waves-effect waves-light btn\" onclick=\"document.forms[0].submit();\">Submit</a>
    <a class=\"btn blue-grey lighten-5 black-text\" href=\"" . url_for($redirect_url) . "\">Cancel</a>
</div>";
    return $output;
}

function text_input($label, $id, $display) {
    $output = "<label class=\"col s12\" for=\"$id\">$label</label>
        <input id=\"$id\" name=\"$id\" type=\"text\" value=\"$display\">";
    return $output;
}
function password_input($label, $id) {
    $output = "<label class=\"col s12\" for=\"$id\">$label</label>
        <input id=\"$id\" name=\"$id\" type=\"password\">";
    return $output;
}

function date_input($label, $id, $display) {
    $output = "<label class=\"col s12\" for=\"$id\">$label</label>
            <div style=\"\">
                <div class=\"form-group\">
                    <div class=\"row\">
                        <div class=\"col-md-12\">
                            <input id=\"$id\" class=\"showDate\" name=\"$id\" value=\"$display\">
                        </div>
                    </div>
                </div>
            </div>";
    return $output;
}

function time_input($label, $id, $display) {
    $output = "<label class=\"col s12\" for=\"$id\">$label</label>
    <div style=\"\">
        <div class=\"form-group\">
            <div class=\"row\">
                <div class=\"col-md-12\">
                    <input id=\"$id\" class=\"showTime\" name=\"$id\" value=\"$display\">
                </div>
            </div>
        </div>
    </div>";
    return $output;
}

function dropdown_input_basic($label, $id, $autofocus, array $array, $valueName, $displayName, $selectedId) {
    $output = "<label class=\"col s12\" for=\"$id\">$label</label>
    <select id=\"$id\" name=\"$id\" class=\"browser-default\"";

if ($autoFocus === true) {
    $output .= " autofocus ";
} 

foreach($array as $item) {
    $output .= "
        <option value=\"{$item[$valueName]}\"";
    if ((int)$item[$valueName] === (int)$selectedId) {$output .= " selected ";}
    $output .= ">{$item[$displayName]}</option>        ";
}
    
$output .= "
    </select>";
return $output;
}
function dropdown_input($label, $id, $display, $autoFocus, array &$array, $valueName, $displayName, 
    $selectedId, $editLink, $editName) {
    $output = "<label class=\"col s12\" for=\"$id\">$label</label>
        <select id=\"$id\" name=\"$id\" class=\"browser-default\"";

    if ($autoFocus === true) {
        $output .= " autofocus ";
    }

    $output .= ">
            <option value=\"\" disabled selected>Choose your $display</option>        ";    

    foreach($array as $item) {
        $output .= "
            <option value=\"{$item[$valueName]}\"";
        if ((int)$item[$valueName] === (int)$selectedId) {$output .= " selected ";}
        $output .= ">{$item[$displayName]}</option>        ";
    }
        
    $output .= "
        </select> 
        <a href='" . url_for($editLink) . "' class=\"col s12 waves-effect waves-light\"><span class=\"right small-caps\">$editName</span></a>";
    return $output;
}

function table_heading_row($labelArray) {
    $output = "<thead>";
    foreach($labelArray as $label) {
        $output .= "<th>" . $label . "</th>";
    }
    $output .= "</thead>";
    return $output;
}

function table_row($rowArray) {
    $output = "<tr>";
    foreach($rowArray as $column) {
        $output .= "<td>" . $column . "</td>";
    }
    $output .= "</tr>";
    return $output;
}
function table_horz_row($rowArray) {
    $output = "<tr>";
    $counter = 0;
    foreach($rowArray as $column) {
        if ($counter === 0) {
            $output .= "<th>" . $column . "</th>";
        } else {
            $output .= "<td>" . $column . "</td>";
        }        
        ++$counter;
    }
    $output .= "</tr>";
    return $output;
}

function table_link($url, $name, $extraClasses) {
    return "<a href=\"$url\"><span class=\"left small-caps $extraClasses\">$name</span></a>";
}





function u($string = "")
{
    return urlencode($string);
}

function raw_u($string = "")
{
    return rawurlencode($string);
}

function hsc($string = "")
{
    return htmlspecialchars($string);
}

function error_404()
{
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
}

function error_500()
{
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
}

function display_errors($errors = array())
{
    $output = '';
    if (!empty($errors)) {
        $output .= "<div class=\"errors\">";
        $output .= "Please fix the following errors:";
        $output .= "<ul>";
        foreach ($errors as $error) {
            $output .= "<li>" . h($error) . "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}
