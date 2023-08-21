<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabin Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    /* Existing styles */

    .inclusion-list {
        margin-top: 20px;
    }

    .inclusion-list li {
        margin-bottom: 5px;
    }
</style>


<body>
    <header>
        <h1>Cabin Details</h1>
    </header>

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

    if (isset($_GET['id'])) {
        $cabinID = $_GET['id'];

        $cabinSql = "SELECT cabinType, cabinDescription, pricePerNight, pricePerWeek, photo FROM cabin WHERE cabinID = $cabinID";
        $cabinResult = $conn->query($cabinSql);

        if ($cabinResult && $cabinResult->num_rows > 0) {
            $cabinRow = $cabinResult->fetch_assoc();

            echo "<div class='cabin-details'>";
            echo "<h2>" . $cabinRow["cabinType"] . "</h2>";
            echo "<img src='images/" . $cabinRow["photo"] . "' alt='" . $cabinRow["cabinType"] . "'>";
            echo "<p><span>Details: </span>" . $cabinRow["cabinDescription"] . "</p>";
            echo "<p><span>Price per night: </span>$" . $cabinRow["pricePerNight"] . "</p>";
            echo "<p><span>Price per week: </span>$" . $cabinRow["pricePerWeek"] . "</p>";

            // Get the inclusion details for the cabin
            $inclusionSql = "SELECT incName, incDetails FROM inclusion JOIN cabininclusion ON inclusion.incID = cabininclusion.incID WHERE cabininclusion.cabinID = $cabinID";
            $inclusionResult = $conn->query($inclusionSql);

            if ($inclusionResult && $inclusionResult->num_rows > 0) {
                echo "<ul class='inclusion-list'>";
                while ($inclusionRow = $inclusionResult->fetch_assoc()) {
                    echo "<li><strong>" . $inclusionRow["incName"] . ":</strong> " . $inclusionRow["incDetails"] . "</li>";
                }
                echo "</ul>";
            }

            echo "</div>";
        } else {
            echo "Cabin not found.";
        }

        $conn->close();
    } else {
        echo "Invalid cabin ID.";
    }
    ?>

    <a class="back-button" href="allCabins.php">Back to Dashboard</a>
</body>

</html>
