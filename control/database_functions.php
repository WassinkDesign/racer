<?php
require_once('database.php');

// CREATE
function insert_address($conn, $address, $city, $prov) {
    if ($address == "") {$address = " ";}

    $stmt = $conn->prepare("INSERT INTO address (address, city, prov) VALUES (?, ?, ?)");
    if ($stmt &&
        $stmt->bind_param("sss", $address, $city, $prov) &&
        $stmt->execute()) { return $stmt->insert_id; }
    return false;
}
function insert_event($conn, $name, $description, $location, $start_date, $end_date) {
    if ($name == "") {$name = " ";}
    if ($description == "") {$description = " ";}
    $start_date = formatDateSave($start_date);
    $end_date = formatDateSave($end_date);

    $stmt = $conn->prepare("INSERT INTO event (name, description, location, start_date, end_date) VALUES (?,?,?,?,?)");
    if ($stmt &&
        $stmt->bind_param("ssiss", $name, $description, $location, $start_date, $end_date) &&
        $stmt->execute()) { return $stmt->insert_id; }
    return false;
}
function insert_location($conn, $name, $address_id) {
    $stmt = $conn->prepare("INSERT INTO location (name, address) VALUES (?, ?)");
    if ($stmt &&
        $stmt->bind_param("ss", $name, $address_id) &&
        $stmt->execute()) { return $stmt->insert_id; }
    return false;
}
function insert_person($conn, $address, $name, $phone, $email, $password) {
    $stmt = $conn->prepare("INSERT INTO person (address, name, phone, email, password) VALUES (?, ?, ?, ?, ?)");
    if ($stmt &&
        $stmt->bind_param("issss", $address, $name, $phone, $email, $password) &&
        $stmt->execute()) {return $stmt->insert_id;}
    return false;
}
function insert_race($conn, $event_id, $race_type, $race_class, $date, $time,$description) {
    if ($description == "") {$description = " ";}
    $date = formatDateSave($date);
    $time = formatTimeSave($time);

    $stmt = $conn->prepare("INSERT INTO race (event, race_type, class_type, date, time, description) VALUES (?,?,?,?,?,?)");
    if ($stmt &&
        $stmt->bind_param("ssiss", $event_id, $race_type,$race_class,$date,$time,$description) &&
        $stmt->execute()) { return $stmt->insert_id; }
    return false;
}
function insert_race_class($conn, $name, $description) {
    if ($name == "") {return false;}
    if ($description == "") {$description = " ";}

    $stmt = $conn->prepare("INSERT INTO race_class (name, description) VALUES (?, ?)");
    if ($stmt &&
        $stmt->bind_param("ss", $name, $description) &&
        $stmt->execute()) { return $stmt->insert_id; }
    return false;
}
function insert_race_type($conn, $name, $points) {
    if ($name == "") {return false;}

    $stmt = $conn->prepare("INSERT INTO race_type (name, points) VALUES (?, ?)");
    if ($stmt &&
        $stmt->bind_param("ss", $name, $points) &&
        $stmt->execute()) { return $stmt->insert_id; }
    return false;
}
function insert_registration($conn, $vehicle, $event) {
    $stmt = $conn->prepare("INSERT INTO registration (vehicle, event) VALUES (?,?)");
    if ($stmt &&
        $stmt->bind_param("ss", $vehicle, $event) &&
        $stmt->execute()) { return $stmt->insert_id; }
    return false;
}
function insert_team($conn, $name, $notes) {
    if ($notes == "") {$notes = " ";}
    $stmt = $conn->prepare("INSERT INTO team (name, notes) VALUES (?, ?)");
    if ($stmt &&
        $stmt->bind_param("ss", $name, $notes) &&
        $stmt->execute()) {return $stmt->insert_id;}
    return false;
}
function insert_vehicle($conn, $person, $race_class, $number, $details) {
    if ($details == "") {$details = " ";}
    $stmt = $conn->prepare("INSERT INTO vehicle (person, race_class, number, details) VALUES (?, ?, ?, ?)");
    if ($stmt && 
        $stmt->bind_param("iiss", $person, $race_class, $number, $details) &&
        $stmt->execute()) {return $stmt->insert_id;}
    return false;
}

// READ
function get_locations($conn) {
    $locations = [];
    $stmt = "SELECT id, name FROM location";
    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $location = array(
                "ID"=>$row["id"],
                "NAME"=>$row["name"]
            );
            array_push($locations, $location);
        }
    }    
    return $locations;
}
function get_locations_display($conn) {
    $locations=[];
    $name = $address = $city = $prov = "";
    $result = $conn->query("SELECT 
        l.id as id, 
        l.name as name, 
        a.address as address, 
        a.city as city, 
        a.prov as prov 
    FROM location l LEFT JOIN address a on l.address = a.id ORDER BY l.name"); 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $location = array(
                "ID"=>$row["id"], 
                "NAME"=>$row["name"],
                "ADDRESS"=>$row["address"],
                "CITY"=>$row["city"],
                "PROV"=>$row["prov"]
            );
            array_push($locations, $location);
        }
    }
    return $locations;
}
function get_location($conn, $id) {
    $name = $address = "";

    $stmt = $conn->prepare("SELECT 
        name, 
        address
    FROM location 
    WHERE id = ?"); 
    
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($name, $address) &&
        $stmt->fetch()) {
            $location = array(
                "ID"=>$id,
                "NAME"=>$name,
                "ADDRESS"=>$address
            );
            return $location;
        }
    return false;
}
function get_location_display($conn, $id) {
    $name = $address = $city = $prov = "";

    $stmt = $conn->prepare("SELECT 
        l.name as name, 
        a.address as address, 
        a.city as city, 
        a.prov as prov 
    FROM location l LEFT JOIN address a on l.address = a.id
    WHERE id = ?"); 
    
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($name, $address, $city, $prov) &&
        $stmt->fetch()) {
            $location = array(
                "ID"=>$id, 
                "NAME"=>$name,
                "ADDRESS"=>$address,
                "CITY"=>$city,
                "PROV"=>$prov
            );
            return $location;
        }
    return false;
}
function get_race_classes($conn) {
    $classes = [];
    $stmt = "SELECT id, name, description FROM race_class ORDER BY name";
    $result = $conn->query($stmt);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $class = array(
                "ID"=>$row["id"],
                "NAME"=>$row["name"],
                "DESCRIPTION"=>$row["description"]
            );
            array_push($classes, $class);
        }
    }
    
    return $classes;
}
function get_race_class($conn, $id) {
    $name = $description = "";

    $stmt = $conn->prepare("SELECT r.name, r.description FROM race_class r WHERE r.id = ?");

    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($name, $description) &&
        $stmt->fetch()) {
            $class = array(
                "ID"=>$id,
                "NAME"=>$name,
                "DESCRIPTION"=>$description
            );
            return $class;
        }
    return false;
}
function get_event($conn, $id) {
    $name = $description = $location = $start_date = $end_date = "";

    $stmt = $conn->prepare("SELECT 
        e.name as name, 
        e.description as description, 
        l.name as location, 
        e.start_date as start_date, 
        e.end_date as end_date 
    FROM event e LEFT JOIN location l ON e.location = l.id 
    WHERE e.id = ?"); 

    if ($stmt &&
    $stmt->bind_param('i', $id) &&
    $stmt->execute() &&
    $stmt->store_result() &&
    $stmt->bind_result($name, $description, $location, $start_date, $end_date) &&
    $stmt->fetch()) {
        $event = array(
            "ID"=>$id,
            "NAME"=>$name,
            "DESCRIPTION"=>$description,
            "LOCATION"=>$location,
            "START_DATE"=>formatDateDisplay($start_date),
            "END_DATE"=>formatDateDisplay($end_date)
        );
        return $event;
    }
    return false;
}
function get_events($conn) {
    $events = [];
    $stmt = "SELECT id, name, description, location, start_date, end_date FROM event e ORDER BY start_date"; 
    $result = $conn->query($stmt);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $event = array(
                "ID"=>$row["id"],
                "NAME"=>$row["name"],
                "DESCRIPTION"=>$row["description"],
                "LOCATION"=>$row["location"],
                "START_DATE"=>formatDateDisplay($row["start_date"]),
                "END_DATE"=>formatDateDisplay($row["end_date"])
            );
            array_push($events, $event);
        }
    }
    
    return $events;
}
function get_events_display($conn) {
    $events = [];
    $stmt = "SELECT 
        e.id as id, 
        e.name as name, 
        e.description as description, 
        l.name as location, 
        e.start_date as start_date, 
        e.end_date as end_date 
    FROM event e LEFT JOIN location l ON e.location = l.id 
    ORDER BY start_date"; 
    $result = $conn->query($stmt);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $event = array(
                "ID"=>$row["id"],
                "NAME"=>$row["name"],
                "DESCRIPTION"=>$row["description"],
                "LOCATION"=>$row["location"],
                "START_DATE"=>formatDateDisplay($row["start_date"]),
                "END_DATE"=>formatDateDisplay($row["end_date"])
            );
            array_push($events, $event);
        }
    }
    
    return $events;
}
function get_race($conn, $id) {
    $event_id = 0;
    $event = $type = $class = $date = $time = $description = "";

    $stmt = $conn->prepare("SELECT 
            e.id as event_id,
            e.name as event,
            rt.name as type,
            rc.name as class,
            r.date as date,
            r.time as time,
            r.description as description
        FROM 
            race r 
            LEFT JOIN event e ON r.event = e.id
            LEFT JOIN race_type rt on r.race_type = rt.id
            LEFT JOIN race_class rc ON r.class_type = rc.id
        WHERE r.id = ?"); 

    if ($stmt &&
    $stmt->bind_param('i', $id) &&
    $stmt->execute() &&
    $stmt->store_result() &&
    $stmt->bind_result($event_id,$event,$type,$class,$date,$time,$description) &&
    $stmt->fetch()) {
        $race = array(
            "ID"=>$id,
            "EVENT_ID"=>$row["event_id"],
            "EVENT"=>$row["event"],
            "TYPE"=>$row["type"],
            "CLASS"=>$row["class"],
            "DATE"=>formatDateDisplay($row["date"]),
            "TIME"=>formatTimeDisplay($row["time"]),
            "DESCRIPTION"=>$row["description"]
        );
        return $race;
    }
    return false;
}
function get_person($conn, $id) {
    $address = $city = $prov = $name = $phone = $email = $team = "";

    $stmt = $conn->prepare("SELECT 
        a.address, 
        a.city, 
        a.prov, 
        p.name, 
        p.phone, 
        p.email, 
        p.team 
        FROM 
            person p 
            LEFT JOIN address a ON p.address = a.id
        WHERE p.id = ?");

    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($address, $city, $prov, $name, $phone, $email, $team) &&
        $stmt->fetch()) {
            $person = array(
                "ID"=>$id,
                "ADDRESS"=>$address,
                "CITY"=>$city,
                "PROV"=>$prov,
                "NAME"=>$name,
                "PHONE"=>$phone,
                "EMAIL"=>$email,
                "TEAM"=>$team
            );
            return $person;
        }
    return false;
}
function get_person_email($conn, $email) {
    $id = 0;
    $address = $city = $prov = $name = $phone = $email = $team = $password = "";

    $stmt = $conn->prepare("SELECT
        p.id, 
        a.address, 
        a.city, 
        a.prov, 
        p.name, 
        p.phone, 
        p.email, 
        p.team,
        p.password 
        FROM 
            person p 
            LEFT JOIN address a ON p.address = a.id
        WHERE p.email = ?");

    if ($stmt &&
        $stmt->bind_param('s', $email) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($id, $address, $city, $prov, $name, $phone, $email, $team, $password) &&
        $stmt->fetch()) {
            $person = array(
                "ID"=>$id,
                "ADDRESS"=>$address,
                "CITY"=>$city,
                "PROV"=>$prov,
                "NAME"=>$name,
                "PHONE"=>$phone,
                "EMAIL"=>$email,
                "TEAM"=>$team
            );
            return $person;
        }
    return false;
}
function get_races($conn) {
    $races = [];
    $stmt = "SELECT 
        r.id as id,
        e.id as event_id,
        e.name as event,
        rt.name as type,
        rc.name as class,
        r.date as date,
        r.time as time,
        r.description as description
    FROM 
        race r 
        LEFT JOIN event e ON r.event = e.id
        LEFT JOIN race_type rt on r.race_type = rt.id
        LEFT JOIN race_class rc ON r.class_type = rc.id
    ORDER BY e.id"; 
    $result = $conn->query($stmt);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $race = array(
                "ID"=>$row["id"],
                "EVENT_ID"=>$row["event_id"],
                "EVENT"=>$row["event"],
                "TYPE"=>$row["type"],
                "CLASS"=>$row["class"],
                "DATE"=>formatDateDisplay($row["date"]),
                "TIME"=>formatTimeDisplay($row["time"]),
                "DESCRIPTION"=>$row["description"]
            );
            array_push($races, $race);
        }
    }
    return $races;
}
function get_race_type($conn, $id) {
    $name = $points = "";

    $stmt = $conn->prepare("SELECT name, points FROM race_type WHERE id = ?");

    if ($stmt &&
    $stmt->bind_param('i', $id) &&
    $stmt->execute() &&
    $stmt->store_result() &&
    $stmt->bind_result($name,$points) &&
    $stmt->fetch()) {
        $type = array(
            "ID"=>$id,
            "NAME"=>$name,
            "POINTS"=>$points
        );
        return $type;
    }
    return false;
}
function get_race_types($conn) {
    $types = [];
    $stmt = "SELECT id, name, points FROM race_type ORDER BY name"; 
    $result = $conn->query($stmt);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $type = array(
                "ID"=>$row["id"],
                "NAME"=>$row["name"],
                "POINTS"=>$row["points"]
            );
            array_push($types, $type);
        }
    }
    return $types;
}
function get_registrations_person_event($conn, $id, $event_id) {
    $registrations = [];

    $stmt = "SELECT 
        r.id as id, 
        r.vehicle as vehicle,
        r.signed_waiver as waiver,
        r.paid_fee as fee
    FROM registration r 
    WHERE r.vehicle IN ( 
            SELECT v.id as id 
            FROM vehicle v
            WHERE v.person = $person_id)
    AND r.event = $event_id";
    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $registration = array(
                "ID"=>$row["id"],
                "VEHICLE"=>$row["vehicle"],
                "WAIVER"=>$row["waiver"],
                "FEE"=>$row["fee"]
            );
            array_push($registrations, $registration);
        }
    }
    return $registrations;
}
function get_team($conn, $id) {
    $name = $notes = "";

    $stmt = $conn->prepare("SELECT team.name, team.notes FROM team WHERE team.id = ?");

    if ($stmt &&
    $stmt->bind_param('i', $id) &&
    $stmt->execute() &&
    $stmt->store_result() &&
    $stmt->bind_result($name,$notes) &&
    $stmt->fetch()) {
        $team = array(
            "ID"=>$id,
            "NAME"=>$name,
            "NOTES"=>$notes
        );
        return $team;
    }
    return false;
}
function get_teams($conn) {
    $teams = [];

    $stmt = "SELECT id, name, notes FROM team";
    $result = $conn->query($stmt);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $team = array(
                "ID"=>$row["id"],
                "NAME"=>$row["name"],
                "NOTES"=>$row["notes"]
            );
            array_push($teams, $team);
        }
    }
    return $teams;
}
function get_vehicles_person_display($conn, $id) {
    $vehicles = [];

    $stmt = "SELECT 
        v.id as id, 
        rc.name as class, 
        v.number as number, 
        v.details as details 
    FROM 
        vehicle v 
        LEFT JOIN race_class rc ON v.race_class = rc.id
    WHERE v.person = $id 
    ORDER BY number, race_class";
    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $vehicle = array(
                "ID"=>$row["id"],
                "CLASS"=>$row["class"],
                "NUMBER"=>$row["number"],
                "DETAILS"=>$row["details"]
            );
            array_push($vehicles, $vehicle);
        }
    }
    return $vehicles;
}
function get_vehicle($conn, $id) {
    $class = $number = $details = "";

    $stmt = $conn->prepare("SELECT 
            rc.name as class, 
            v.number as number, 
            v.details as details 
        FROM 
            vehicle v 
            LEFT JOIN race_class rc ON v.race_class = rc.id
        WHERE v.id = ?");

    if ($stmt &&
    $stmt->bind_param('i', $id) &&
    $stmt->execute() &&
    $stmt->store_result() &&
    $stmt->bind_result($class,$number,$details) &&
    $stmt->fetch()) {
        $vehicle = array(
            "ID"=>$id,
            "CLASS"=>$class,
            "NUMBER"=>$number,
            "DETAILS"=>$details
        );
        return $vehicle;
    }
    return false;
}
// UPDATE
function update_address($conn, $id, $address, $city, $prov) {
    if ($address == "") {$address = " ";}
    if ($city == "") {$city = " ";}
    if ($prov == "") {$prov = " ";}

    $stmt = $conn->prepare("UPDATE address a SET a.address = ?, city = ?, prov = ? WHERE a.id = ?");

    if ($stmt &&
        $stmt->bind_param('sssi', $address, $city, $prov, $id) &&
        $stmt->execute()) { return true; }
    return false;
}
function update_location($conn, $id, $name) {
    if ($name == "") {$name = " ";}

    $stmt = $conn->prepare("UPDATE location SET name= ? WHERE id = ?");

    if ($stmt &&
        $stmt->bind_param('si', $name, $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function update_event($conn, $id, $name, $description, $location, $start_date, $end_date) {
    if ($name == "") {$name = " ";}
    if ($description == "") {$description = " ";}
    $start_date = formatDateSave($start_date);
    $end_date = formatDateSave($end_date);

    $stmt=$conn->prepare("UPDATE event e SET e.name = ?, e.description = ?, e.location = ?, e.start_date = ?, e.end_date = ? WHERE id = ?"); 

    if ($stmt &&
        $stmt->bind_param('ssissi', $name, $description, $location, $start_date, $end_date, $id) &&
        $stmt->execute()) { return true; }
    return false;
}
function update_password($conn, $id, $password) {
    if ($password == "") {return false;}
    $stmt = $conn->prepare("UPDATE person SET password = ? WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('si', $password, $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function update_race($conn, $id, $event_id, $race_type, $race_class, $date, $time,$description) {
    if ($description == "") {$description = " ";}
    $date = formatDateSave($date);
    $time = formatTimeSave($time);

    $stmt = $conn->prepare("UPDATE race 
        SET (
            event = ?, 
            race_type = ?, 
            class_type = ?, 
            date = ?, 
            time = ?, 
            description = ?)
        WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param("iiiss", $event_id, $race_type,$race_class,$date,$time,$description, $id) &&
        $stmt->execute()) { return true; }
    return false;
}
function update_race_class($conn, $id, $name, $description) {
    if ($name == "") {$name = " ";}
    if ($description == "") {$description = " ";}

    $stmt = $conn->prepare("UPDATE race_class r SET r.name = ?, r.description = ? WHERE r.id = ?");

    if ($stmt &&
        $stmt->bind_param('ssi', $name, $description, $id) && 
        $stmt->execute()) {return true;}
    return false;
}
function update_race_type($conn, $id, $name, $points) {
    if ($name == "") {$name = " ";}

    $stmt = $conn->prepare("UPDATE race_type r SET r.name = ?, r.points = ? WHERE r.id = ?");

    if ($stmt &&
        $stmt->bind_param('sii', $name, $points, $id) && 
        $stmt->execute()) {return true;}
    return false;
}
function update_person($conn, $id, $address, $city, $prov, $name, $phone, $email, $team) {
    if ($address == "") {$address = " ";}
    if ($city == "") {$city = " ";}
    if ($phone == "") {$phone = " ";}
    if ($prov == "") {$prov = " ";}

    $stmt = $conn->prepare("UPDATE address a, person p SET a.address = ?, a.city = ?, a.prov = ?, p.name = ?, p.phone = ?, p.email = ?, p.team = ? WHERE a.id = p.address AND p.id = ?");

    if ($stmt &&
        $stmt->bind_param('ssssssii', $address, $city, $prov, $name, $phone, $email, $team, $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function update_team($conn, $id, $name, $notes) {
    if ($notes == "") {$notes = " ";}
    if ($name == "") {return false;}

    $stmt = $conn->prepare("UPDATE team SET team.name = ?, team.notes = ? WHERE team.id = ?");
    if ($stmt &&
        $stmt->bind_param('ssi', $name, $notes, $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function update_vehicle($conn, $id, $class, $number, $details) {
    if ($details == "") {$details = " ";}

    $stmt = $conn->prepare("UPDATE vehicle SET race_class = ?, number = ?, details = ? WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('issi', $class, $number, $details, $id) &&
        $stmt->execute()) {return true;}
    return false;
}

// DELETE
function delete_event($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM event WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function delete_class($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM race_class WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function delete_location($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM location WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function delete_race($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM race WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function delete_race_type($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM race_type WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function delete_registration($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM registration WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function delete_team($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM team WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
function delete_vehicle($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM vehicle WHERE id = ?");
    if ($stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()) {return true;}
    return false;
}
?>