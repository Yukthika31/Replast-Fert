<?php
header('Content-Type: application/json'); // Ensure the content type is JSON

// Database connection parameters
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'projectdb';

// Create connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    echo json_encode(array("error" => "Connection failed: " . mysqli_connect_error()));
    exit();
}

// SQL query to fetch all records from the organiser table
$sql = "SELECT Name, Organisation_Name, ID_Proof, Phone_Number, E_mail, Instagram_ID, Facebook_ID FROM organiser";
$result = mysqli_query($conn, $sql);

if ($result) {
    $records = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }
    echo json_encode(array("records" => $records));
} else {
    echo json_encode(array("error" => "Query failed: " . mysqli_error($conn)));
}

mysqli_close($conn);
?>
