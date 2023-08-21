<?php
session_start();

// Include database connection logic
include_once 'sunnyspot.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (isset($_SESSION['authorised']) && $_SESSION['authorised'] === true) {
        // Get the staffID from the session
        $staffID = $_SESSION['staffID'];

        // Update the logoutDateTime field in the log table
        $logoutDateTime = date('Y-m-d H:i:s'); // Current timestamp
        $sql = "UPDATE log SET logoutDateTime = '$logoutDateTime' WHERE staffID = '$staffID' AND logoutDateTime IS NULL";
        $conn->query($sql);

        // Clear the session variables
        session_unset();
        session_destroy();

        // Redirect to the login page
        header("Location: adminLogin.php");
        exit();
    } else {
        // User is not logged in, redirect to the login page
        header("Location: adminLogin.php");
        exit();
    }
} else {
    // Redirect to the login page if accessed directly without a form submission
    header("Location: adminLogin.php");
    exit();
}
?>