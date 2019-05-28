<?php
include_once('../control/signed-in-check.php');

if ($signedIn === false){
    header("location: login.php");
    exit;
}

require_once "../control/database.php";

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

$event_id = 0;

if (isset($_GET["event"])) {
    $event_id = $_GET["event"];
} else {
    header("location: index.php");
    exit;
}

$person_id = $_SESSION["id"];

$event = [];

$events = [
    [
        "id"=>"01",
        "date"=>"June 1-2, 2019",
        "location"=>"Augusta Motorsports Park",
        "description"=>"Information about the event here."
    ],
    [
        "id"=>"02",
        "date"=>"June 15-16, 2019",
        "location"=>"Toronto Motorsports Park",
        "description"=>"Information about the event here."
    ],
    [
        "id"=>"03",
        "date"=>"July 27-28, 2019",
        "location"=>"Augusta Motorsports Park",
        "description"=>"Information about the event here."
    ],
    [
        "id"=>"04",
        "date"=>"August 24-25, 2019",
        "location"=>"Shannonville",
        "description"=>"Newly announced location"
    ],
];

$classes = [
    [
        "value"=>"1",
        "desc"=>"1600 Buggy"
    ],
    [
        "value"=>"2",
        "desc"=>"Class 4"
    ],
    [
        "value"=>"3",
        "desc"=>"Tuff Truck"
    ]
];

foreach ($events as $curEvent) {
    if ($curEvent['id'] === $event_id) {
        $event=[
            "id"=>$curEvent['id'],
            "date"=>$curEvent['date'],
            "location"=>$curEvent['location'],
            "description"=>$curEvent['description']
        ];
    }
}

$name = $phone = $email = $address = $city = $phone = $pCode = $vehicle = "";
$vehicle_err = "";

$address_id = "";

$sql = "SELECT a.address, a.city, a.postalCode, p.name, p.phone, p.email FROM person p, address a WHERE p.id = $person_id and p.address = a.id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $phone = $row['phone'];
    $email = $row['email'];
    $address = $row['address'];
    $city = $row['city'];
    $pCode = $row['postalCode'];
}

$title="Register";
include('header.php'); ?>

<div class="wrapper">
<div class="container">
    <h2 class="header center orange-text">Register</h2>
</div>
    <p>Please fill out this form to register for the <?php echo $event['date'];?> event at <?php echo $event['location'];?>.</p>
    <table class="table table-borderless table-sm">
        <tr>
            <th scope="row">Name:</th>
            <td><?php echo $name;?></td>
        </tr>
        <tr>
            <th scope="row">Email:</th>
            <td><?php echo $email;?></td>
        </tr>
        <tr>
            <th scope="row">Phone:</th>
            <td><?php echo $phone;?></td>
        </tr>
        <tr>
            <th scope="row">Address:</th>
            <td><?php echo "$address, $city $pCode";?></td>
        </tr>
    </table>
    <p>Incorrect information? <a href="account.php">Update your account</a>.</p>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Class</label>
            <select class="form-control" id="classSelect">
                <?php
                    foreach ($classes as $class) {
                        print_r($class);
                        echo "<option value=\"{$class['value']}\">{$class['desc']}</option>";
                    }
                ?>
            </select>
        </div>
        <div class="form-group <?php echo (!empty($vehicle_err)) ? 'has-error' : ''; ?>">
            <label>Vehicle Number</label>
            <input type="text" name="name" class="form-control" value="<?php echo $vehicle; ?>">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
    </form>
</div>
<?php include('footer.php');?>