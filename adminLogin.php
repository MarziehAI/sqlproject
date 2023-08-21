<?php
session_start();

// Include database connection logic
include_once 'sunnyspot.inc.php';

// Process the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query the database to check if the username and password match
    $sql = "SELECT * FROM admin WHERE userName = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Login successful, set the session variable and log the login event
        $row = $result->fetch_assoc();
        $_SESSION['authorised'] = true;
        $_SESSION['staffID'] = $row['staffID'];

        // Insert the login event into the log table
        $staffID = $row['staffID'];
        $loginDateTime = date('Y-m-d H:i:s'); // Current timestamp
        $sql = "INSERT INTO log (staffID, loginDateTime) VALUES ('$staffID', '$loginDateTime')";
        $conn->query($sql);

        // Redirect to the adminDashboard.php page
        header("Location: adminDashboard.php");
        exit();
    } else {
        // Login failed, display an error message
        $error_message = "Invalid username or password.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-top: 30px;
    }

    form {
        max-width: 300px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border: 1px solid #ccc;
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
        width: 80%;
        padding: 10px;
        border-radius: 3px;
        border: 1px solid #ccc;
    }

    input[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        background-color: #4CAF50;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>


<body>
    <h1>Admin Login</h1>

    <?php if (isset($errorMessage)): ?>
        <p>
            <?php echo $errorMessage; ?>
        </p>
    <?php endif; ?>

    <form action="adminLogin.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

</body>

</html>