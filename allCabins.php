<!doctype html>
<html lang="en">

<head>
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
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunnspot Accommodation</title>
    <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>


<body>
    <header> <img src="images/accommodation.png" alt="Accommodation">
        <h1>Welcom to Sunnyspot Accomodation</h1>
    </header>
    <nav>
        <a href="adminLogin.php"><span>Admin</span></a>
    </nav>

    <?php
    $sql = "SELECT cabinID, cabinType, cabinDescription, pricePerNight, pricePerWeek, photo FROM cabin";
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Loop through each row and display cabin information
        while ($row = $result->fetch_assoc()) {
            echo "<article>";
            echo "<h2>" . $row["cabinType"] . "</h2>";
            echo "<img src='images/" . $row["photo"] . "' alt='" . $row["cabinType"] . "'>";
            echo "<p><span>Details: </span>" . $row["cabinDescription"] . "</p>";
            echo "<p><span>Price per night: </span>$" . $row["pricePerNight"] . "</p>";
            echo "<p><span>Price per week: </span>$" . $row["pricePerWeek"] . "</p>";
            echo "<a class='back-button' href='cabindetails.php?id=" . $row["cabinID"] . "'>Details</a>";
            echo "</article>";
        }
    } else {
        echo "No cabins found.";
    }

    // Close the database connection
    $conn->close();
    ?>
</body>

</html>