<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sunnyspot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cabin ID is set
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Delete the cabin without confirmation
    $sql = "DELETE FROM cabin WHERE cabinID = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Cabin deleted successfully.";
    } else {
        echo "Error deleting cabin: " . $conn->error;
    }
} else {
    echo "Invalid cabin ID.";
}

// Close the database connection
$conn->close();
?>