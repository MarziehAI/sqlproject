<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Cabin List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Update Cabin List</h1>

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

    // Retrieve cabin records from the database
    $sql = "SELECT cabinID, cabinType FROM cabin";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='adminUpdateCabin.php?id=" . $row["cabinID"] . "'>" . $row["cabinType"] . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No cabins found.</p>";
    }

    $conn->close();
    ?>

    <a href="adminMenu.php">Back to Menu</a>

</body>

</html>