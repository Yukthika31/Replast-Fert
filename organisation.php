<?php
session_start();

// Ensure the session variable is set
if (!isset($_SESSION['email'])) {
    die("Session not started or user not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $email = $_SESSION['email']; // Email from session
    $id_proof = $_POST['id_proof'];
    $org_name = $_POST['org_name'];
    $phone = $_POST['phone'];
    $form_email = $_POST['email']; // Email from form (might be different from session email)
    $instagram = $_POST['instagram'];
    $facebook = $_POST['facebook'];

    // Database connection parameters
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'projectdb';

    // Create connection
    $conn = mysqli_connect($host, $user, $pass, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the table exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'organiser'");
    if (mysqli_num_rows($table_check) == 0) {
        die("Table 'organiser' does not exist.");
    }

    // Retrieve the user's name from the login table based on email
    $name_sql = "SELECT Name FROM login WHERE Email = ?";
    $name_stmt = mysqli_prepare($conn, $name_sql);

    if ($name_stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($name_stmt, "s", $email);
    mysqli_stmt_execute($name_stmt);
    mysqli_stmt_bind_result($name_stmt, $name);
    mysqli_stmt_fetch($name_stmt);
    mysqli_stmt_close($name_stmt);

    if (!$name) {
        die("User not found.");
    }

    // Check for existing record in the organiser table
    $check_sql = "SELECT ID_Proof FROM organiser WHERE ID_Proof = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);

    if ($check_stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($check_stmt, "s", $id_proof);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        mysqli_stmt_close($check_stmt);
        mysqli_close($conn);
        exit;
    }

    // SQL query to insert data into the organiser table
    $sql = "INSERT INTO organiser (Name, Organisation_Name, ID_Proof, Phone_Number, E_mail, Instagram_ID, Facebook_ID) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sssisss", $name, $org_name, $id_proof, $phone, $form_email, $instagram, $facebook);

    // Execute statement
    $execute_result = mysqli_stmt_execute($stmt);

    if ($execute_result === false) {
        die('MySQL execute error: ' . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Registration successful!');</script>";
        // Optionally redirect to the profile-container view or another page
        header("Location : finalOrganiser.html") ;
    } else {
        echo "Error: Insertion failed or no rows affected.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
