<!DOCTYPE html>
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

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cabinID = $_POST["txtCabinID"];
        $cabinType = $_POST["txtCabinType"];
        $cabinDescription = $_POST["txtCabinDescription"];
        $pricePerNight = $_POST["txtPricePerNight"];
        $pricePerWeek = $_POST["txtPricePerWeek"];

        // Check if a new image is uploaded
        if ($_FILES["txtCabinPhoto"]["name"]) {
            $photo = $_FILES["txtCabinPhoto"]["name"];
            $targetDir = "images/";
            $targetFilePath = $targetDir . basename($photo);
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Check if the file is a valid image
            $allowedTypes = array("jpg", "jpeg", "png", "gif");
            if (in_array($fileType, $allowedTypes)) {
                // Move the uploaded file to the target directory
                move_uploaded_file($_FILES["txtCabinPhoto"]["tmp_name"], $targetFilePath);

                // Update the cabin and photo in the database
                $sql = "UPDATE cabin SET cabinType='$cabinType', cabinDescription='$cabinDescription', 
                        pricePerNight='$pricePerNight', pricePerWeek='$pricePerWeek', photo='$photo' WHERE cabinID='$cabinID'";
                $result = $conn->query($sql);

                if ($result) {
                    echo "Cabin updated successfully.";
                    // Redirect to adminDashboard page
                    header("Location: adminDashboard.php");
                    exit;
                } else {
                    echo "Error updating cabin: " . $conn->error;
                }
            } else {
                echo "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        } else {
            // Update the cabin without changing the photo
            $sql = "UPDATE cabin SET cabinType='$cabinType', cabinDescription='$cabinDescription', 
                    pricePerNight='$pricePerNight', pricePerWeek='$pricePerWeek' WHERE cabinID='$cabinID'";
            $result = $conn->query($sql);

            if ($result) {
                echo "Cabin updated successfully.";
                // Redirect to adminDashboard page
                header("Location: adminDashboard.php");
                exit;
            } else {
                echo "Error updating cabin: " . $conn->error;
            }
        }
    }

    // Fetch the cabin details from the database based on the provided cabin ID
    if (isset($_GET['id'])) {
        $cabinID = $_GET['id'];

        $sql = "SELECT cabinID, cabinType, cabinDescription, pricePerNight, pricePerWeek, photo FROM cabin WHERE cabinID='$cabinID'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cabinID = $row["cabinID"];
            $cabinType = $row["cabinType"];
            $cabinDescription = $row["cabinDescription"];
            $pricePerNight = $row["pricePerNight"];
            $pricePerWeek = $row["pricePerWeek"];
            $photo = $row["photo"];
        } else {
            echo "Cabin not found.";
        }
    } else {
        echo "Invalid cabin ID.";
    }

    // Close the database connection
    $conn->close();
    ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Cabin</title>
    <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 20px;
    }

    header {
        text-align: center;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 28px;
        margin: 0;
    }

    form {
        max-width: 400px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 10px;
    }

    textarea {
        resize: vertical;
        height: 100px;
    }

    input[type="file"] {
        margin-bottom: 10px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .success-msg {
        color: green;
        margin-top: 10px;
    }

    .error-msg {
        color: red;
        margin-top: 10px;
    }
</style>

<body>
    <header>
        <img src="images/accommodation.png" alt="Accommodation">
        <h1>Sunnyspot Accommodation</h1>
    </header>

    <h2>Update Cabin</h2>

    <form method="post" action="updateCabin1.php" enctype="multipart/form-data">
        <input type="hidden" name="txtCabinID" value="<?php echo $cabinID; ?>">
        <label for="txtCabinType">Cabin Type:</label>
        <input type="text" name="txtCabinType" id="txtCabinType" value="<?php echo $cabinType; ?>" required><br>

        <label for="txtCabinDescription">Cabin Description:</label>
        <textarea name="txtCabinDescription" id="txtCabinDescription" rows="5"
            required><?php echo $cabinDescription; ?></textarea><br>

        <label for="txtPricePerNight">Price per Night:</label>
        <input type="text" name="txtPricePerNight" id="txtPricePerNight" value="<?php echo $pricePerNight; ?>"
            required><br>

        <label for="txtPricePerWeek">Price per Week:</label>
        <input type="text" name="txtPricePerWeek" id="txtPricePerWeek" value="<?php echo $pricePerWeek; ?>"
            required><br>

        <label for="txtCabinPhoto">Cabin Photo:</label>
        <input type="file" name="txtCabinPhoto" id="txtCabinPhoto"><br>

        <img src="images/<?php echo $photo; ?>" alt="Cabin Photo" style="width: 200px; height: auto;"><br>

        <input type="submit" value="Update Cabin">
    </form>
</body>

</html>
