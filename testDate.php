<?php
require_once("control/init.php");

$addURL = "";
$title = "TEST";

$headerStuff = "
    ";

include(include_url_for('header.php')); 

if ($email_err != "" || $password_err != "") {
    echo "<div class=\"row alert-dismissible red darken-4 white-text z-depth-1 \" id=\"alert-div\">        
            <div class=\"col s10\">
            $email_err <br/>
            $password_err
            </div>
            <div class=\"col s2\">
                <a class=\"btn red darken-4 white-text\" onclick=\"document.getElementById('alert-div').innerHTML='';\">X</a>
            </div>
        </div>";
}
?>

<div class="container">
    //Modify the Form Fields to suit the needs of your website.
    <div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">Schedule an Appointment</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">First Name</label>
                    <input type="text" class="form-control" name="fname" id="fname">
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Last Name</label>
                    <input type="text" class="form-control" name="lname" id="lname">
                </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control" name="email" id="email">
                </div>
                </div>
                <div class='col-md-6'>
                <div class="form-group">
                    <label class="control-label">Appointment Time</label>
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
    </div>
</div>
<?php include(include_url_for('footer.php'));?>