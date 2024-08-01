<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $type = $_POST['select'];
    $password = $_POST['password'];

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

    // SQL query to check if user exists and fetch password and LoginType
    $sql = "SELECT Password, LoginType FROM login WHERE LOWER(Email) = LOWER(?) AND LOWER(LoginType) = LOWER(?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Check if preparation was successful
    if (!$stmt) {
        die("Statement preparation failed: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $email, $type);

    // Execute statement
    mysqli_stmt_execute($stmt);

    // Store result
    mysqli_stmt_store_result($stmt);

    // Check if user exists
    if (mysqli_stmt_num_rows($stmt) == 1) {
        // Bind result variables
        mysqli_stmt_bind_result($stmt, $db_Password, $db_LoginType);
        mysqli_stmt_fetch($stmt);

        // Debugging output
        echo "Email: $email<br>";
        echo "Password: $password<br>";
        echo "Stored Password: $db_Password<br>";
        echo "LoginType: $db_LoginType<br>";

        // Compare passwords directly (plaintext comparison for debugging)
        if ($password === $db_Password) {
            // Password is correct, set session variables
            $_SESSION['email'] = $email;

            // Redirect based on LoginType
            switch ($db_LoginType) {
                case 'Organiser':
                    header("Location: org_details.html");
                    break;
                case 'Identifier':
                    header("Location: plasticCol.html");
                    break;
                case 'Consumer':
                    header("Location: Buy.html");
                    break;
                default:
                    header("Location: aboutUs.html");
                    break;
            }
            exit();
        } else {
            // Password is incorrect
            header('Location: wrongpass.php?error=wrong_password');
            exit();
        }
    } else {
        // User is not registered
        header('Location: notregistered.php?error=not_registered');
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
