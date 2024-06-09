<?php
$database = new mysqli("localhost", "root", "", "groupProject");
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

$sql = "SELECT * FROM specialties";
$result = $database->query($sql);

$specialties = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specialties[] = $row;
    }
}

echo json_encode($specialties);

$database->close();
?>
