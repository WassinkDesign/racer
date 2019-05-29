<?php
// Include config file
require_once "control/database.php";

$stmt = $conn->prepare("INSERT INTO address (address, city, postalCode) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $param_address, $param_city, $param_pcode);

$param_address = "123 South Street";
$param_city = "Whoville";
$param_pcode = "123 567";

$stmt->execute();

$address_id = $stmt->insert_id;

echo "New record created successfully: $address_id <br/>";

$pStmt = $conn->prepare("INSERT INTO person (address, name, phone, email, password) VALUES (?, ?, ?, ?, ?)");
$pStmt->bind_param("sssss", $param_address, $param_name, $param_phone, $param_email, $param_password);

$param_address = $address_id;
$param_name = "George";
$param_phone = "555-555-5555";
$param_email = "foo@bar.com";
$param_password = "Password";

$pStmt->execute();

$person_id = $pStmt->insert_id;

echo "New person created successfully: $person_id <br/>";

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="wrapper">
        
    </div>    
</body>
</html>