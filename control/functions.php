<?php

function formatDateDisplay($date) {
  $tempDate = DateTime::createFromFormat('m/d/Y', $date);
  return $tempDate->format('M d, Y');
}

function formatDateSave($date) {
  $tempDate = DateTime::createFromFormat('M d, Y', $date);
  return $tempDate->format('m/d/Y');
}

function formatTimeDisplay($time) {
  $tempTime = DateTime::createFromFormat('G:i', $time);
  return $tempTime->format('g:i a');  
}

function formatTimeSave($time) {
  $tempTime = DateTime::createFromFormat('g:i a', $time);
  return $tempTime->format('G:i');
}

function include_url_for($script_path) {
    // add the leading '/' if not present
     if($script_path[0] != '/') {
       $script_path = "/" . $script_path;
     }

    return WWW_ROOT . $script_path;
  }
 
function url_for($script_path) {
    // add the leading '/' if not present
    if ($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }
    
    return "http://" . HEADER_ROOT . $script_path;
}
  
  function u($string="") {
    return urlencode($string);
  }
  
  function raw_u($string="") {
    return rawurlencode($string);
  }
  
  function h($string="") {
    return htmlspecialchars($string);
  }
  
  function error_404() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
  }
  
  function error_500() {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
  }
  
  function redirect_to($location) {
    header("Location: " . $location);
    exit;
  }
  
  function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }
  
  function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }
  
  function display_errors($errors=array()) {
    $output = '';
    if(!empty($errors)) {
      $output .= "<div class=\"errors\">";
      $output .= "Please fix the following errors:";
      $output .= "<ul>";
      foreach($errors as $error) {
        $output .= "<li>" . h($error) . "</li>";
      }
      $output .= "</ul>";
      $output .= "</div>";
    }
    return $output;
  }
  ?>