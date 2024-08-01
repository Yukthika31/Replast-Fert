<?php
if (isset($_POST['submit'])) {
    // Retrieve form data
    $fullname = $_POST['fname'];
    $email = $_POST['email'];
    $number = $_POST['number'];
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

    // Check for existing record
    $check_sql = "SELECT Email FROM login WHERE Email = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);

    if ($check_stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    // If a record with the same email exists
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo "<script>alert('Email already registered.');</script>";
        header('Location: duplicateEntry.php?error=duplicate_entry');
        mysqli_stmt_close($check_stmt);
        exit;
    } else {
        // SQL query with prepared statement
        $sql = "INSERT INTO login (Name, Email, Number, LoginType, Password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if prepare statement succeeded
        if ($stmt === false) {
            die('MySQL prepare error: ' . mysqli_error($conn));
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssiss", $fullname, $email, $number, $type, $password);

        // Execute statement
        $execute_result = mysqli_stmt_execute($stmt);

        // Check if execution was successful
        if ($execute_result === false) {
            die('MySQL execute error: ' . mysqli_stmt_error($stmt));
        }

        // Check if insertion was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Registration successful!');</script>";
            header("Location: loginPg.html");
            exit;
        } else {
            echo "Error: Insertion failed or no rows affected.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close the check statement and connection
    mysqli_stmt_close($check_stmt);
    mysqli_close($conn);
}
?>
