<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cabin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Add Cabin</h1>
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

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $cabinType = $_POST["cabinType"];
            $cabinDescription = $_POST["cabinDescription"];
            $pricePerNight = $_POST["pricePerNight"];
            $pricePerWeek = $_POST["pricePerWeek"];

            // Check if an image was uploaded
            if ($_FILES["photo"]["error"] === 0) {
                $photo = $_FILES["photo"]["name"];
                $photoPath = "images/" . $photo;
                move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);
            } else {
                // Default image if no image was uploaded
                $photo = "testCabin.jpg";
            }

            // Validate price per night and price per week
            if ($pricePerNight <= 0) {
                $error = "Price per night must be a positive number.";
            } elseif ($pricePerWeek > ($pricePerNight * 5)) {
                $error = "Price per week cannot be more than 5 times the price per night.";
            }

            // If no validation errors, insert the cabin into the database
            if (!isset($error)) {
                $sql = "INSERT INTO cabin (cabinType, cabinDescription, pricePerNight, pricePerWeek, photo) 
                        VALUES ('$cabinType', '$cabinDescription', $pricePerNight, $pricePerWeek, '$photo')";

                if ($conn->query($sql) === TRUE) {
                    // Redirect to a success page or display a success message
                    header("Location: adminDashboard.php");
                    exit();
                } else {
                    echo "Error inserting cabin into database: " . $conn->error;
                }
            }
        }

        // Close the database connection
        $conn->close();
        ?>
        <form action="insertCabin.php" method="POST" enctype="multipart/form-data">
            <?php if (isset($error)): ?>
                <p class="error">
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>
            <label for="cabinType">Cabin Type:</label>
            <input type="text" id="cabinType" name="cabinType" required>
            <label for="cabinDescription">Cabin Description:</label>
            <textarea id="cabinDescription" name="cabinDescription" rows="4" required></textarea>
            <label for="pricePerNight">Price per Night:</label>
            <input type="number" id="pricePerNight" name="pricePerNight" min="0" required>
            <label for="pricePerWeek">Price per Week:</label>
            <input type="number" id="pricePerWeek" name="pricePerWeek" min="0" required>
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo">
            <input type="submit" value="Add Cabin">
        </form>
    </div>
</body>

</html>