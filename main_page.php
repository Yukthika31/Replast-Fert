<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: signUpPg.html");
    exit;
}

// Display main page content
$username = $_SESSION['username'];
?>
